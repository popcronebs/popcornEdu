<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Code;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use \App\Http\Controllers\AuthController as AuthController;
class ParentController extends Controller
{
    // 학부모 회원가입
    public function __construct()
    {
        //미들웨어
    }


    // 로그인
    public function login(Request $request)
    {
        $parent_id = $request->input('id');
        $password = $request->input('password');
        $is_not_login = $request->input('is_not_login');

        //post 값이 있으면
        if ($parent_id != null && $password != null) {
            //우선 세션 초기화.
            $request->session()->flush();

            $users = \App\ParentTb::where('parent_id', $parent_id)
                ->whereRaw('parent_pw = SHA1(?)', [$password])
                ->get();

            //학생이 있으면 세션 체우기.
            if (count($users) == 1) {
                $request->session()->put('parent_seq', $users[0]->id);
                $request->session()->put('team_code', $users[0]->team_code);
                $request->session()->put('parent_id', $parent_id);
                $request->session()->put('parent_pw', $password);
                $request->session()->put('parent_name', $users[0]->parent_name);
                $request->session()->put('group_seq', $users[0]->group_seq);
                $request->session()->put('region_seq', $users[0]->region_seq);
                $request->session()->put('login_type', 'parent');
                setcookie('login_type', 'parent', time() + (86400 * 30), "/");
                $request->session()->put('main_code', $users[0]->main_code);
                setcookie('main_code', $users[0]->main_code, time() + (86400 * 30), "/");
                return redirect('/parent/index');
            } else {
                //결과값을 is_not_login = true 넘겨주기.
                return redirect('/parent/login?is_not_login=true');
            }
        }
        //session 의 parent_id 에 값이 있고, session의 login_type 이 parent
        else if ($request->session()->has('parent_id') && $request->session()->get('login_type') == 'parent') {
            //세션에 menu_url 이 있으면
            return redirect('/parent/index'); // 임시 메인.
        }
        if ($is_not_login == 'true') {
            if($request->session()->has('parent_id') && $request->session()->get('login_type') == 'parent'){
                return redirect('/parent/register');
            }
            return view('parent.parent_login', ['is_not_login' => true]);
        } else {
            return view('parent.parent_login');
        }
    }

    public function someMethod(Request $request)
    {
        // 쿼리스트링 값을 세션에 저장
        $request->session()->put('schoolCode', $request->query('schoolCode'));
        $request->session()->put('schoolName', \App\Team::where('team_code', $request->query('schoolName'))->first()->team_name);
        $request->session()->put('teachSeq', $request->query('teachSeq'));
        $request->session()->put('classSeq', $request->query('classSeq'));
        $request->session()->put('gradeCode', $request->query('gradeCode'));
        // 리다이렉션
        return redirect()->route('parent.register');
    }

    public function register(Request $request)
    {
        $code = Code::where('code_pt', 13)->get();
        $schoolCode = $request->query('code');
        $schoolName = $request->query('school');
        $teachSeq = $request->query('teachSeq');
        return view('parent.parent_register_v2',
            ['code' => $code,
            'schoolCode' => $schoolCode,
            'schoolName' => $schoolName,
            'teachSeq' => $teachSeq,
        ]);

    }

    public function registerInsert(Request $request)
    {
        $authController = new AuthController();
        $authController->checkPhoneAuthNumberForRegister($request);

        $validator = Validator::make($request->all(), [
            'parent_id' => 'required|min:4|unique:parents,parent_id',
            'password' => 'required|min:4',
            'passwordCheck' => 'required|same:password',
            'parentName' => 'required',
            'phoneNumber' => 'required|regex:/^[0-9]{10,11}$/',
            'email' => 'required|email',
            //학생 벨리데이션
            'studentId' => 'required|min:4|unique:students,student_id',
            'studentPassword' => 'required|min:8',
            'studentPasswordCheck' => 'required|same:studentPassword',
            'studentName' => 'required',
            'schoolName' => 'required',
            'schoolCode' => 'required',
            'studentGrade' => 'required',
        ]);

        $validator->validate();

        $request->merge([
            'user_seq' => null,
            'user_type' => 'parent',
            'user_phone' => $request->input('phoneNumber'),
            'user_auth' => $request->input('authNumber')
        ]);

        if($authController->checkPhoneAuthNumberForRegister($request) == 'success'){
            return response()->json(['resultCode' => 'success']);
        }elseif($authController->checkPhoneAuthNumberForRegister($request) == 'fail'){
            return redirect('/parent/register')->withErrors(['authNumber' => '인증번호가 일치하지 않습니다.'])->withInput();
        }

        if($request->parent_id == $request->studentId){
            return redirect('/parent/register')->withErrors(['parent_id' => '부모와 학생은 같은 아이디를 사용할 수 없습니다.'])->withInput();
        }

        DB::beginTransaction();
        try {
            $parent = \App\ParentTb::create([
                'main_code' => $request->input('main_code'),
                'parent_id' => $request->parent_id,
                'parent_name' => $request->parentName,
                'parent_pw' => DB::raw("SHA1('{$request->password}')"),
                'parent_phone' => $request->phoneNumber,
                'parent_email' => $request->email,
                'group_seq' => 3,
                'is_use' => $request->input('is_use', true),
            ]);
            $parent_seq = $parent->id;
            if($parent_seq == null){
                throw new \Exception('회원가입 중 오류가 발생했습니다: 부모 생성 실패');
            }
            $area = \App\TeamArea::where('team_code', $request->schoolCode)->first();
            \App\Student::create([
                'parent_seq' => $parent_seq,
                'area' => $area->tarea_sido,
                'main_code' => $request->input('main_code'),
                'student_id' => $request->studentId,
                'student_name' => $request->studentName,
                'teach_seq' => $request->input('teach_seq'),
                'student_pw' => DB::raw("SHA1('{$request->studentPassword}')"),
                'school_name' => $request->schoolName,
                'school_code' => $request->schoolCode,
                'team_code' => $request->schoolCode,
                'grade' => $request->studentGrade,
                'class_name' => $request->studentClass,
                'group_seq' => 1,
                'is_use' => $request->input('is_use', 'Y'),
            ]);
            DB::commit();
            return redirect('/parent/login');
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('회원가입 중 오류가 발생했습니다: ' . $e->getMessage());
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

    // 자녀가 여러명일 때 선택 자녀 변경.
    public function changeChild(Request $request)
    {
        $student_seq = $request->input('student_seq');
        $parent_seq = session()->get('parent_seq');
        // 자녀가 맞는지 한번더 확인.
        $student = \App\Student::where('id', $student_seq)->where('parent_seq', $parent_seq)->get();
        if (count($student) > 0) {
            $request->session()->put('student_seq', $student_seq);
        }

        // 결과
        $result['resultCode'] = 'success';
        $reuslt['student_seq'] = $student_seq;
        return response()->json($result);
    }


    // TEST PAGE
    public function test()
    {
        return view('utils.modal_goods_expiration');
    }
    // test1
    public function test1()
    {
        return view('utils.modal_national_evaluation_payment');
    }
    // test2
    public function test2()
    {
        return view('utils.modal_payment');
    }
    // test3
    public function test3()
    {
        return view('utils.modal_system_usage_inquiry');
    }



    public function usernameCheck(Request $request)
    {
        $username = $request->username;
        $type = $request->type;
        if ($username == null) {
            $result['resultCode'] = 'fail';
            return response()->json($result);
        }
        //학부모와 선생님 아이디 중복체크
        if($type == 'web'){
            $parent = \App\ParentTb::where('parent_id', $username)->get();
            $teacher = \App\Teacher::where('teach_id', $username)->get();
            $student = \App\Student::where('student_id', $username)->get();
            if (count($parent) > 0 || count($teacher) > 0 || count($student) > 0) {
                $result['resultCode'] = 'fail';
                $result['message'] = '이미 사용중인 아이디입니다.';
            } else {
                $result['resultCode'] = 'success';
                $result['message'] = '사용 가능한 아이디입니다.';
            }
        }
        return response()->json($result);
    }

    public function schoolList(Request $request)
    {
        $schoolName = $request->input('schoolName');
        if ($schoolName == null) {
            $result['resultCode'] = 'fail';
            return response()->json($result);
        }
        $schools = \App\SchoolInfo::where('SCHUL_NM', 'like', '%' . $schoolName . '%')
            ->where('SCHUL_KND_SC_NM', '초등학교')
            ->get();
        return response()->json($schools);
    }
}
