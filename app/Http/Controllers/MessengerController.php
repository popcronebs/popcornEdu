<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessengerController extends Controller
{
    // 쪽지함 분기
    public function list(){
        // 선생님일 경우 login_type = teacher
        if(session()->get('login_type') == 'teacher'){
            return $this->teacherList();
        }
        // 학생일 경우 login_type = student
        else if(session()->get('login_type') == 'student'){
            return $this->studentList();
        }
        // 학부모 일경우 login_type == parent
        else if(session()->get('login_type') == 'parent'){
            return $this->parentList();
        }

    }
    // 선생님 쪽지함
    public function teacherList(){
        // 우선 로그인 선생님 정보 가져오기.
        $teach_seq = session()->get('teach_seq');
        $teachers = \App\Teacher::
            select('teachers.*')
            ->leftJoin('user_groups', 'user_groups.id', '=', 'teachers.group_seq')
            ->where('teachers.id', $teach_seq)
            ->addSelect('user_groups.group_name', 'user_groups.group_type2')
            ->first();

        // 로그인 선생님이 팀장급, 총매니저급 일경우
        $is_leader = false;
        if($teachers->group_type2 != 'run'){
            $is_leader = true;
        }

        // [추가 코드] 기획 변경 총 매니저가 여러 소속을 가질 수 있게 변경.하..
        // 변경에 따른 DB변경후 추가 코드 주석 삭제.

        // 선생님의 소속 가져오기.
        $region_seq = $teachers->region_seq;
        $regions = \App\Region::where('id', $region_seq)->get();

        // 문의유형분류 가져오기
        $main_code = $teachers->main_code;
        $contact_codes = \App\Code::where('code_step', '1')->where('code_category', 'contact')->where('main_code', $main_code)->get();

        return view('teacher.teacher_messenger', [  'teachers' => $teachers,
            'is_leader' => $is_leader,
            'regions' => $regions,
            'contact_codes' => $contact_codes,
            'login_type' => 'teacher']);
    }

    // 학생 쪽지함
    private function studentList(){
        // 방과후인지 확인.
        $team_code = session()->get('team_code');
        $is_after_school = false;
        $team_type = \App\Team::where('team_code', $team_code)->value('team_type');
        if($team_type == 'after_school'){
            $is_after_school = true;
        }
        // $teachers < 담당 선생님 정보 가져오기.
        $student_id = session()->get('student_id');
        $student_seq = session()->get('student_seq');
        $teach_seq = \App\Student::where('student_id', $student_id)->value('teach_seq');

        // 방과후라면 우선은 첫 선생님 가져오기.
        // TODO: 이후 blade 페이지에서 선생님 리스트 선택 관련 업데이트.
        if($is_after_school && !$teach_seq){
            // select teach_seq From classes where id in (
            //     select class_seq from class_mates where student_seq =  2821
            // )
            $teach_seq = \App\ClassTb::whereIn('id', function($query) use ($student_seq){
                $query->select('class_seq')->from('class_mates')->where('student_seq', $student_seq);
            })->value('teach_seq');
        }
        $teachers = \App\Teacher::
            select('teachers.*')
            ->leftJoin('user_groups', 'user_groups.id', '=', 'teachers.group_seq')
            ->where('teachers.id', $teach_seq)
            ->addSelect('user_groups.group_name', 'user_groups.group_type2')
            ->first();

        // 학생이라서 리더 여부는 false
        $is_leader = false;

        // 문의유형분류 가져오기
        // 선생님 main_code(초등/중등) 어차피 학생과 같기 때문에 선생님 main_code로 가져옴.
        if($teachers) $main_code = $teachers->main_code;
        else $main_code = session()->get('main_code');

        $contact_codes = \App\Code::where('code_step', '1')->where('code_category', 'contact')->where('main_code', $main_code)->get();

        return view('teacher.teacher_messenger', [  'teachers' => $teachers,
            'is_leader' => $is_leader,
            // 'regions' => $regions, // 학생이라 사용안함.
            'contact_codes' => $contact_codes,
            'login_type' => 'student']);
    }

    // 학부모 쪽지함
    private function parentList(){
        $parent_seq = session()->get('parent_seq');
        $paernt = \App\ParentTb::find($parent_seq);
        $students = \App\Student::where('parent_seq', $parent_seq)
            ->select('students.*')
            ->addSelect('grade.code_name as grade_name')
            ->leftJoin('codes as grade', 'grade.id', '=', 'students.grade')
            ->get();
        $is_leader = false;
        $cheering_codes = \App\Code::where('code_step', '1')->where('code_category', 'cheering')->get();

        //학생 테이블의 main_code를 가지고 와서 배열로 만듬.
        $main_codes = \App\Student::where('parent_seq', $parent_seq)->select('main_code')->distinct()->get()->toArray();
        $contact_codes = \App\Code::where('code_step', '1')
            ->where('code_category', 'contact')
            ->whereIn('main_code', $main_codes)
            ->get()
            ->groupBy('main_code');

        return view('teacher.teacher_messenger', [
            'parent' => $paernt,
            'students' => $students,
            'is_leader' => $is_leader,
            'login_type' => 'parent',
            'cheering_codes' => $cheering_codes,
            'contact_codes_pt' => $contact_codes
        ]);
    }

    // 쪽지함에서 쪽지 리스트 가져오기
    public function select(Request $request){
        $login_type = session()->get('login_type');
        $messenger_seq = $request->input('messenger_seq');
        $parent_seq = $request->input('parent_seq');
        $teach_seq = $request->input('teach_seq');
        $student_seq = $request->input('student_seq');
        $search_str = $request->input('search_str');
        $type = $request->input('type');
        // type 은 send, receive
        // 쪽지 가져오기
        $messengers = \App\Messenger::
        select(
            'messengers.id',
            'messengers.send_type',
            'messengers.teach_seq',
            'parents.parent_name',
            'teachers.teach_name',
            'students.student_name as st_student_name',
            'user_groups.group_name as teach_group',
            'messengers.student_seq',
            'messengers.student_grade',
            'messengers.student_name',
            'messengers.parent_seq',
            'messengers.contact_type',
            'messengers.status',
            'messengers.created_at',
            'messengers.updated_at'
        );
        //messenger_seq 가 있으면 messge, coment를 전체길이로 가져온다.
        if(strlen($messenger_seq) > 0){
            $messengers = $messengers->addSelect('messengers.message');
            $messengers = $messengers->addSelect('messengers.comment');
            $messengers = $messengers->addSelect('students.school_name');
        }
        //아니면 제한 1000자로 가져온다.
        else{
            $messengers = $messengers->addSelect(DB::raw('substring(messengers.message, 1, 1000) as message'));
            $messengers = $messengers->addSelect(DB::raw('substring(messengers.comment, 1, 1000) as comment'));
        }

        $messengers = $messengers->addSelect('students.profile_img_path');
        $messengers = $messengers->addSelect('teachers.profile_img_path as teach_profile_img_path');
        $messengers = $messengers->addSelect('parents.profile_img_path as pt_profile_img_path');

        $messengers = $messengers->leftJoin('students', 'students.id', '=', 'messengers.student_seq');
        $messengers = $messengers->leftJoin('teachers', 'teachers.id', '=', 'messengers.teach_seq')
            ->leftJoin('user_groups', 'user_groups.id', '=', 'teachers.group_seq');
        $messengers = $messengers->leftJoin('parents', 'parents.id', '=', 'messengers.parent_seq');
        //조건
        // 전광판은 제외한다. cheering_type <> display or cheering_type is null
        // 학부모일때는 제외힌다.
        if($login_type != 'parent'){
            $messengers = $messengers->where(function($query){
                $query
                    ->where('messengers.cheering_type', '<>', 'display')
                    ->orWhereNull('messengers.cheering_type');
            });
        }
        // messenger_seq가 있으면 해당 seq만 가져온다.
        if(strlen($messenger_seq) > 0){
            // $messengers = $messengers->leftJoin('students', 'students.id', '=', 'messengers.student_seq');
            $messengers = $messengers->where('messengers.id', $messenger_seq);
            // first
            $messengers = $messengers->first();
            // 받은 쪽지 일경우 status를 read로 변경.
            if($type == 'receive' && $messengers->status == 'new'){
                $messengers->status = 'read';
                $messengers->save();
            }
        }
        // 리스트로 가져올때
        else {
            // 로그인 타입이 선생님일 경우
            if($login_type == 'teacher' && strlen($teach_seq) > 0){
                $messengers = $messengers->where('messengers.teach_seq', $teach_seq);
                if($type == 'send'){
                    $messengers = $messengers->where('send_type', 'teacher');
                }else if($type == 'receive'){
                    $messengers = $messengers->where('send_type', '<>', 'teacher');
                }
            }
            // 로그인 타입이 학생일 경우
            else if($login_type == 'student' && strlen($student_seq) > 0){
                $messengers = $messengers->where('student_seq', $student_seq);
                if($type == 'send'){
                    $messengers = $messengers->where('send_type', 'student');
                }else if($type == 'receive'){
                    $messengers = $messengers->where('send_type', '<>', 'student');
                }
            }
            // 로그인 타입이 학부모일 경우.
            else if($login_type == 'parent' && strlen($parent_seq) > 0){
                $messengers = $messengers->where('messengers.parent_seq', $parent_seq);
                if($type == 'send'){
                    $messengers = $messengers->where('send_type', 'parent');
                }else if($type == 'receive'){
                    $messengers = $messengers->where('send_type', '<>', 'parent');
                }
            }
            // 아무것도 가져오지 못하게 조건 추가.
            else{
                $messengers = $messengers->where('student_seq', -1);
            }

            // [추가 코드]
            // search_str이 어떤역할을 하는지 추후 수정.
            if(strlen($search_str) > 0){
                $messengers = $messengers->where(function($query) use ($search_str){
                    $query->where('messengers.student_name', 'like', '%'.$search_str.'%')
                        ->orWhere('messengers.message', 'like', '%'.$search_str.'%');
                });

            }

            $result['sql'] = $messengers->toSql();
            $result['binding'] = $messengers->getBindings();
            $messengers = $messengers->orderBy('created_at', 'desc')->get();
        }

        // 결과
        // $result = array();
        $result['messengers'] = $messengers;
        $result['resultCode'] = 'success';
        return response()->json($result, 200);
    }

    // 쪽지보내기 학생 불러오기.
    public function studentSelect(Request $request){

        $search_type = $request->input('search_type');
        $search_str = $request->input('search_str');
        $teach_seq = $request->input('teach_seq');
        $user_type = $request->input('user_type');

        // 선생님 정보 불러오기.
        $teachers = \App\Teacher::
            select('teachers.team_code', 'user_groups.group_type2', 'teachers.region_seq')
            ->leftJoin('user_groups', 'user_groups.id', '=', 'teachers.group_seq')
            ->where('teachers.id', $teach_seq)
            ->first();
        $team_code = $teachers->team_code;
        $group_type2 = $teachers->group_type2;
        $region_seq = $teachers->region_seq;
        $test = "";

        $students = \App\Student::
            select( 'students.*',
                'parents.parent_name',
                'parents.id as parent_seq',
                'teachers.teach_name',
                'teams.team_name',
                'grade_codes.code_name as grade_name'
            )
            ->leftJoin('codes as grade_codes', 'grade_codes.id', '=', 'students.grade')
            ->leftJoin('parents', 'parents.id', '=', 'students.parent_seq')
            ->leftJoin('teachers', 'teachers.id', '=', 'students.teach_seq')
            ->leftJoin('teams', 'teams.team_code', '=', 'students.team_code');


        if($group_type2 == 'run'){
            //학생 담당 선생님 조건
            $students = $students->where('students.teach_seq', $teach_seq);
        }else if($group_type2 == 'leader'){
            // 같은 팀코드의 학생만 가져온다.
            $students = $students->where('students.team_code', $team_code);
        }else if($group_type2 == 'general'){
            $students = $students->whereIn('students.team_code', function($query) use ($region_seq){
                $query->select('team_code')->from('teams')->where('region_seq', $region_seq);
            });
        }


        //조건이 있을 경우 조건 실행.
        if(strlen($search_str) > 0){
            if($search_type == 'student_name'){
                $students = $students->where('student_name', 'like', '%'.$search_str.'%');
            }else if($search_type == 'grade'){
                $students = $students->where('grade_codes.code_name', 'like', '%'.$search_str.'%');
            }
        }
        if($user_type == 'parent'){
            $students = $students->whereNotNull('students.parent_seq');
        }

        //결과
        $result = array();
        $result['test'] = $test;
        $result['sql'] = $students->toSql();
        $result['students'] = $students->get();
        $result['resultCode'] = 'success';

        return response()->json($result, 200);
    }

    // 선생님 쪽지함 인트로 문구 변경.
    public function introInsert(Request $request){
        $message_intro = $request->input('message_intro');
        $teach_seq = $request->session()->get('teach_seq');

        // 선생님 teachers 테이블에 message_intro 업데이트
        $teachers = \App\Teacher::where('id', $teach_seq)->update(['message_intro' => $message_intro]);
        // 결과
        $result = array();
        $result['resultCode'] = $teachers > 0 ? 'success' : 'fail';
        return response()->json($result, 200);
    }

    // 쪽지 보내기
    public function sendInsert(Request $request){
        $send_type = $request->input('send_type');
        $student_seqs = $request->input('student_seqs');
        $student_name = $request->input('student_name');
        $student_grade = $request->input('student_grade');
        $parent_seqs = $request->input('parent_seqs');

        $teach_seq = $request->input('teach_seq');
        $student_seq = $request->input('student_seq');
        $parent_seq = $request->input('parent_seq');

        $message = $request->input('message');
        $contact_seq = $request->input('contact_seq');
        $contact_type = $request->input('contact_type');

        // student_seqs 배열로 변환
        $student_seqs = explode(',', $student_seqs);
        $student_names = explode(',', $student_name);
        $student_grades = explode(',', $student_grade);
        $parent_seqs = explode(',', $parent_seqs);

        // is_cheering
        $cheering_seq = $request->input('cheering_seq');
        $cheering_type = $request->input('cheering_type');


        // 쪽지 보내기 Messenger
        // 학생수만큼 반복
        // 트랜잭션 처리
        DB::transaction(function () use (
            $student_seqs,
            $teach_seq,
            $parent_seq,
            $parent_seqs,
            $student_names,
            $student_grades,
            $message,
            $send_type,
            $contact_type,
            $contact_seq,
            $cheering_seq,
            $cheering_type,
            $student_seq) {

                $login_type = session()->get('login_type');
                if($login_type == 'teacher'){
                    for($i = 0; $i < count($student_seqs); $i++){
                        $messenger = new \App\Messenger;
                        $messenger->teach_seq = $teach_seq;
                        // 학생seq가 없으면 부모seq로 저장
                        if($student_seqs[$i] == '')
                        $messenger->parent_seq = $parent_seqs[$i];
                        else
                        $messenger->student_seq = $student_seqs[$i];
                        $messenger->student_name = $student_names[$i];
                        $messenger->student_grade = $student_grades[$i];
                        $messenger->message = $message;
                        $messenger->send_type = $send_type;
                        $messenger->status = 'new';
                        $messenger->contact_seq = $contact_seq;
                        $messenger->contact_type = $contact_type;
                        $messenger->save();
                    }
                }
                else if($login_type == 'student'){
                    $student = \App\Student::where('id', $student_seq)->first();
                    $student_grade = \App\Code::where('id', $student->grade)->value('code_name');
                    // select *From messengers
                    $messenger = new \App\Messenger;
                    $messenger->teach_seq = $teach_seq;
                    $messenger->student_seq = $student_seq;
                    $messenger->student_name = $student->student_name;
                    $messenger->student_grade = $student_grade;
                    $messenger->message = $message;
                    $messenger->send_type = $send_type;
                    $messenger->status = 'new';
                    $messenger->contact_seq = $contact_seq;
                    $messenger->contact_type = $contact_type;
                    $messenger->save();
                }
                else if($login_type == 'parent'){
                    for($i = 0; $i < count($student_seqs); $i++){
                        $messenger = new \App\Messenger;
                        if($teach_seq) $messenger->teach_seq = $teach_seq;
                        $messenger->parent_seq = $parent_seq;
                        if($student_seqs[$i]) $messenger->student_seq = $student_seqs[$i];
                        $messenger->student_name = $student_names[$i];
                        $messenger->student_grade = $student_grades[$i];
                        $messenger->message = $message;
                        $messenger->send_type = $send_type;
                        $messenger->status = 'new';
                        $messenger->cheering_seq = $cheering_seq;
                        $messenger->cheering_type = $cheering_type;
                        // if($contact_seq) $messenger->contact_seq = $contact_seq; else if($cheering_seq) $messenger->contact_seq = $cheering_seq;
                        if($contact_seq) $messenger->contact_seq = $contact_seq;
                        if($contact_type) $messenger->contact_type = $contact_type; else if($cheering_type) $messenger->contact_type = $cheering_type == 'message' ? '응원메시지' : '전광판';
                        // if($contact_type) $messenger->contact_type = $contact_type;
                        $messenger->save();
                    }
                }
            });

        // TODO: 푸시나 메시지 날림.

        // 결과
        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result, 200);
    }

    // 쪽지 삭제
    public function delete(Request $request){
        $teach_seq = $request->input('teach_seq');
        $messenger_seqs = $request->input('messenger_seqs');

        // 배열 변환
        $messenger_seqs = explode(',', $messenger_seqs);

        // 쪽지 삭제
        $messengers = \App\Messenger::where('teach_seq', $teach_seq)->whereIn('id', $messenger_seqs)->delete();

        // 결과
        $result = array();
        $result['resultCode'] = $messengers > 0 ? 'success' : 'fail';
        return response()->json($result, 200);
    }

    // 쪽지함 답변 보내기
    public function commentInsert(Request $request){
        $messenger_seq = $request->input('messenger_seq');
        $message = $request->input('message');
        $teach_seq = $request->input('teach_seq');

        // 쪽지 답변
        $messenger = \App\Messenger::where('id', $messenger_seq)->first();
        $messenger->comment = $message;
        $messenger->status = 'complete';
        $messenger->save();

        // 결과
        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result, 200);
    }
}
