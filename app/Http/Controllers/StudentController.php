<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\LearningMTController;
use Carbon\Carbon;

class StudentController extends Controller
{
    //
    public function tempMain()
    {
        return view('student.student_temp_main');
    }

    public function main()
    {
        // 전광판 가져오기.
        $student_seq = session()->get('student_seq');

        // select messengers.*, parents.parent_id, parents.parent_name from messengers
        // left join parents on parents.parent_seq = messengers.parent_seq
        // where cheering_type = 'display' and student_seq = ''
        $messengers = \App\Messenger::where('cheering_type', 'display')
            ->where('student_seq', $student_seq)
            ->leftJoin('parents', 'parents.id', '=', 'messengers.parent_seq')
            ->select('messengers.*', 'parents.parent_id', DB::raw('left(parents.parent_name, 1) as parent_name'))
            ->orderBy('messengers.id', 'desc')
            ->first();
        return view('student.student_main',['messengers' => $messengers]);
    }

    //로그인
    public function login(Request $request)
    {
        // $student_id = $request->input('id');
        // $password = $request->input('password');
        $student_id = $request->input('id');
        $password = $request->input('password');
        $is_not_login = $request->input('is_not_login');

        //post 값이 있으면
        if($student_id != null && $password != null) {
            //우선 세션 초기화.
            $request->session()->flush();

            $users = \App\Student::where('student_id', $student_id)
            ->whereRaw('student_pw = SHA1(?)', [$password])
            ->get();

            //학생이 있으면 세션 체우기.
            if(count($users) == 1) {
                $request->session()->put('student_seq', $users[0]->id);
                $request->session()->put('team_code', $users[0]->team_code);
                $request->session()->put('student_id', $student_id);
                $request->session()->put('student_pw', $password);
                $request->session()->put('student_name', $users[0]->student_name);
                $request->session()->put('group_seq', $users[0]->group_seq);
                $request->session()->put('region_seq', $users[0]->region_seq);
                $request->session()->put('login_type', 'student');
                setcookie('login_type', 'student', time() + (86400 * 30), "/");
                $request->session()->put('main_code', $users[0]->main_code);
                setcookie('main_code', $users[0]->main_code, time() + (86400 * 30), "/");

                $users[0]->last_login_date = date('Y-m-d H:i:s');
                $users[0]->login_cnt = $users[0]->login_cnt + 1;
                $users[0]->save();

                // TODO: 추후 로그인 점수 확인필요.
                // 하루에 로그인시 계속 주는지에 대해서도 확인 필요.
                \App\PointHistory::create([
                    'student_seq' => $users[0]->id,
                    'point' => 5,
                    'remark' => '로그인',
                    'point_type' => 'login',
                    'point_category' => 'activity',
                ]);

                return redirect('/student/main'); // 임시 메인.
            } else {
                //결과값을 is_not_login = true 넘겨주기.
                return redirect('/student/login?is_not_login=true');
            }
        }
        //session 의 student_id 에 값이 있고, session의 login_type 이 student
        else if($request->session()->has('student_id') && $request->session()->get('login_type') == 'student') {
            //세션에 menu_url 이 있으면
            return redirect('/student/main'); // 임시 메인.
        }
        if($is_not_login == 'true') {
            return view('student.student_login', ['is_not_login' => true]);
        } else {
            return view('student.student_login');
        }
    }

    //logout
    public function logout(Request $request)
    {

        $login_type = session('login_type');
        $user_id = null;

        switch($login_type) {
            case 'parent':
                $user_id = session('parent_seq');
                break;
            case 'student':
                $user_id = session('student_seq');
                break;
            case 'teacher':
                $user_id = session('teach_seq');
                break;
        }

        if($user_id) {
            // 세션 테이블에서 해당 사용자의 세션 삭제
            \App\Sessions::where('user_id', $user_id)
                ->where('login_type', $login_type)
                ->delete();
        }

        $request->session()->flush();
        // return view('student_login');
        // view가 아니라 페이지 이동을 해야함.
        return redirect('/login');
    }


    // 오늘의 학습 불러오기
    public function studyPlannerSelect(Request $request)
    {
        $student_seq = session()->get('student_seq');

        // $request 안에 student_seqs 넣어주기.
        $request->merge([
            'student_seqs' => $student_seq
        ]);

        // LearningMTController 의 studyPlannerSelect사용
        $learningMTController = new LearningMTController();
        return $learningMTController->studyPlannerSelect($request);
    }

    // 학습 시작 시간 가져오기.
    public function studyTimeSelect(Request $request)
    {
        $student_seq = session()->get('student_seq');
        $request->merge([
            'student_seqs' => $student_seq
        ]);
        $search_start_date = $request->input('search_start_date');
        $search_end_date = $request->input('search_end_date');

        $learningMTController = new LearningMTController();
        $result = $learningMTController->studyTimeSelect($request);

        $attend = \App\Attend::where('student_seq', $student_seq)
        ->whereBetween('attend_date', [$search_start_date, $search_end_date])
        ->first();
        $data = $result->getData();
        $data->attend = $attend;
        $result->setData($data);
        return $result;

    }

    // 학습시작시 출결 하기.
    public function attend(Request $request)
    {
        $sel_date = $request->input('sel_date');
        $student_seq = session()->get('student_seq');
        $today_date = Carbon::now()->toDateString();
        $current_time = Carbon::now()->toTimeString();
        $team_code = session()->get('team_code'); // 실제 구현에 맞게 수정 필요
        $login_type = session()->get('login_type');
        if($login_type == 'teacher'){
            return response()->json(['resultCode' => 'fail', 'msg' => '선생님은 학습시작시 출결하실 수 없습니다.']);
        }

        // 트랙잭션 시작.
        $is_transaction_suc = true;
        DB::beginTransaction();
        try {

            // 오늘 이면서 student_seq를 가져온다.
            $attend = \App\Attend::where('student_seq', $student_seq)->where('attend_date', date('Y-m-d'))->first();
            //없으면 생성.
            if (!$attend) {
                $attend = new \App\Attend();
                $attend->team_code = $team_code;
                $attend->student_seq = $student_seq;
                $attend->attend_date = date('Y-m-d');
                $attend->start_time = date('H:i:s');
                $attend->save();
            } else {
                // $attend->end_time = date('H:i:s');
                $attend->save();
            }
            $attend_seq = $attend->id;
            $attend_detail = \App\AttendDetail::where('student_seq', $student_seq)->where('attend_seq', $attend_seq)->where('class_seq', null)->first();
            // 없으면 생성.
            if (!$attend_detail) {
                $attend_detail = new \App\AttendDetail();
                $attend_detail->student_seq = $student_seq;
                $attend_detail->attend_datetime = date('Y-m-d H:i:s');
                $attend_detail->attend_date = date('Y-m-d');
                $attend_detail->attend_time = date('H:i:s');
                // $attend_detail->class_start_time = $class_start_time;
                $attend_detail->attend_seq = $attend_seq;
                // $attend_detail->class_seq = $class_seq;
                // $attend_detail->class_name = $class_name;
                $attend_detail->team_code = $team_code;
                $attend_detail->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            $is_transaction_suc = false;
            DB::rollback();
            throw $e;
        }

        // 결과
        $result['resultCode'] = $is_transaction_suc ? 'success' : 'fail';
        return response()->json($result);
    }

    // 학생 마지막 접속 시간 업로드.
    public function updateLastConnectTime(Request $request){
        $student_seq = session()->get('student_seq');
        $student = \App\Student::find($student_seq);
        $student->last_login_date = date('Y-m-d H:i:s');
        $student->save();
        $result['resultCode'] = 'success';
        return response()->json($result);
    }


}
