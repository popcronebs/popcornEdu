<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\LogMTController as LogMT;

class MemberInfoMtController extends Controller
{
    public function list(Request $request)
    {
        // 세션에서 가져오기.
        $login_type = $request->session()->get('login_type');

        // 추후 같은 화면이라도 다른 로그인 타입이 들어올 수 있으므로, 대비.
        // 학생일경우
        if($login_type == "student") {
            return $this->studentList($request);
        }
        // 선생님일경우
        elseif($login_type == "teacher") {
            return $this->teacherList($request);
        }
        // 학부모일경우
        elseif($login_type == "parent") {
            return $this->parentList($request);
        }
        // 이후 추가.

    }
    public function teacherList(Request $request)
    {
        $teach_seq = $request->session()->get('teach_seq');
        $teacher = null;
        if($teach_seq != "") {
            $teacher = \App\Teacher::find($teach_seq)
            ->select('teachers.*')
            ->addSelect('regions.region_name as region_name')
            ->leftJoin('regions', 'regions.id', '=', 'teachers.region_seq')
            ->where('teachers.id', $teach_seq)
            ->first();
        }
        return view('teacher.teacher_member_info', ['teacher' => $teacher]);
    }

    // 학생 정보 상세.
    public function studentList(Request $request)
    {
        $student_seq = $request->session()->get('student_seq');
        $student = null;


        $class_teachers = \App\Teacher::select('teachers.teach_name', 'teams.team_name')
            ->leftJoin('teams', 'teachers.team_code', '=', 'teams.team_code')
            ->whereIn('teachers.id', function ($query) use ($student_seq) {
                $query->select('teach_seq')
                    ->from('classes')
                    ->whereIn('id', function ($query) use ($student_seq) {
                        $query->select('class_seq')
                            ->from('class_mates')
                            ->where('student_seq', $student_seq);
                    });
            })
            ->groupBy('teachers.id', 'teams.team_name', 'teachers.teach_name');

        $class_teachers = $class_teachers->get();

        if($student_seq != "") {
            $student = \App\Student::find($student_seq)
                ->select(
                    'students.*',
                    'parents.parent_phone as pt_parent_phone',
                    'parents.is_auth_phone as pt_is_auth_phone',
                    'teams.team_name'
                )
            ->addSelect('grade.code_name as grade_name')
            ->addSelect('teachers.teach_name as teach_name')
            ->addSelect('teams.team_name as team_name')
            ->addSelect('teams.team_type as team_type')
            ->addSelect('regions.region_name as region_name')
            ->leftJoin('codes as grade', 'grade.id', '=', 'students.grade')
            ->leftJoin('teachers', 'teachers.id', '=', 'students.teach_seq')
            ->leftJoin('teams', 'teams.team_code', '=', 'students.team_code')
            ->leftJoin('regions', 'regions.id', '=', 'teams.region_seq')
            ->leftJoin('parents', 'parents.id', '=', 'students.parent_seq')
            ->find($student_seq);
        }


        return view('student.student_member_info', ['student' => $student, 'class_teachers' => $class_teachers]);
    }

    public function studentInfoDetail(Request $request)
    {

        $student_seq = $request->session()->get('student_seq');
        $student = null;
        if($student_seq != "") {
            $student = \App\Student::find($student_seq)
            ->select('students.*')
            ->addSelect('grade.code_name as grade_name')
            ->addSelect('teachers.teach_name as teach_name')
            ->addSelect('teams.team_name as team_name')
            ->addSelect('regions.region_name as region_name')
            ->leftJoin('codes as grade', 'grade.id', '=', 'students.grade')
            ->leftJoin('teachers', 'teachers.id', '=', 'students.teach_seq')
            ->leftJoin('teams', 'teams.team_code', '=', 'students.team_code')
            ->leftJoin('regions', 'regions.id', '=', 'teams.region_seq')
            ->first();
        }
        return view('student.student_member_info', ['student' => $student]);
    }

    // 학부모 정보 상세.
    public function parentList(Request $request)
    {
        $parent_seq = $request->session()->get('parent_seq');
        $parent = null;
        $students = null;
        $grade_codes = null;
        $type = $request->input('type');

        if($parent_seq != "") {
            $parent = \App\ParentTb::find($parent_seq);

            $students = \App\Student::where('parent_seq', $parent_seq)
                ->select(
                    'students.*',
                    'teachers.teach_name',
                    'teachers.teach_phone',
                    'gd.goods_seq',
                    'gd.start_date as goods_start_date',
                    'gd.end_date as goods_end_date',
                    'gd.goods_name',
                    'gd.goods_period',
                    'gd.stop_day_sum',
                    'gd.stop_cnt',
                    'gd.is_use as goods_is_use',
                    'grade_codes.code_name as grade_name'
                )
                ->leftJoin('teachers', 'teachers.id', '=', 'students.teach_seq')
                ->leftJoin('goods_details as gd', 'gd.id', '=', 'students.goods_detail_seq')
                ->leftJoin('codes as grade_codes', 'grade_codes.id', '=', 'students.grade')
                ->get();

            // NOTE: 나중에 여러 학년이 초등/중등으로 나뉜 아이를 가진 엄마일경우 대비 해야할듯.
            $main_code = $students[0]->main_code;
            $grade_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'grade')->where('code_step', '=', 1)
                ->orderBy('code_idx', 'asc')
                ->get();
        }
        return view('parent.parent_member_info', [
            'parent' => $parent,
            'students' => $students,
            'grade_codes' => $grade_codes,
            'type' => $type
        ]);
    }

    //선생님 정보 상세.
    public function teacherInfoDetail(Request $request)
    {
        $main_code = $_COOKIE['main_code'] ?? session()->get('main_code');
        $teach_seq = $request->input('teach_seq');
        $my_teach_seq = session()->get('teach_seq');
        $teacher = \App\Teacher::find($teach_seq);
        $group_type2 = session()->get('group_type2');

        $regions = \App\Region::select('id', 'region_name')
                            ->where('general_teach_seq', $my_teach_seq)
                            ->get();
        $teams = \App\Team::where('region_seq', $teacher['region_seq'])->get();
        $teach_region = \App\Region::find($teacher['region_seq']);

        $groups = \App\UserGroup::select('group_name', 'id', 'group_type')
        ->where('main_code', $main_code)
        ->where('group_type', '<>', 'admin');
        // if($group_type2 == 'general'){
        //     $groups = $groups->where('group_type2','<>', 'general');
        // }
        if($group_type2 == 'leader') {
            $groups = $groups->where('group_type2', '<>', 'general');
            $groups = $groups->where('group_type2', '<>', 'leader');
        } elseif($group_type2 == 'run') {
            $groups = $groups->where('group_type', '<>', 'teacher');
        }
        $groups = $groups->where('group_type', 'teacher');
        $groups = $groups->get();

        return view('teacher.teacher_info_detail', [
         'groups' => $groups,
         'regions' => $regions,
         'teams' => $teams,
         'teacher' => $teacher,
         'group_type2' => $group_type2,
         'teach_region' => $teach_region
        ]);
    }

    // 프로필 이미지 업로드
    public function uploadProfile(Request $request)
    {
        $user_seq = $request->input('user_seq');
        $user_type = $request->input('user_type');
        $user_img = $request->file('user_img');

        $profile_img_path = "";

        // 이미지 있으면 저장
        if($user_img) {
            // 확장자 가져오기.
            $ext = $user_img->getClientOriginalExtension();
            // 확장자가 이미지가 아니면 리턴. 확장자 더 필요시 추가.
            if($ext != "jpg" && $ext != "jpeg" && $ext != "png" && $ext != "gif") {
                return response()->json(['resultCode' => 'not_image']);
            }
            // 이미지 속성이 아니면 리턴.
            if(!getimagesize($user_img)) {
                return response()->json(['resultCode' => 'not_image']);
            }
            // 이미지 저장 경로 : storage/app/public/user_profile/ $user_seq.png
            $user_img->storeAs('public/uploads/user_profile/'.$user_type."/", $user_seq.'.'.$ext);

            $prev_img_path = "";
            $profile_img_path = $user_seq.'.'.$ext;
            if($user_type == 'student') {
                // 데이터 베이스 반영.
                $student = \App\Student::find($user_seq);
                $prev_img_path = $student->profile_img_path;
                // ? 부터 뒤에 랜덤값이 붙으므로 앞까지 잘라서 저장.
                $prev_img_path = explode('?', $prev_img_path)[0];
                $student->profile_img_path = $profile_img_path.'?'.Str::random(10);
                $student->save();
            }else if($user_type == 'teacher'){
                $teacher = \App\Teacher::find($user_seq);
                $prev_img_path = $teacher->profile_img_path;
                // ? 부터 뒤에 랜덤값이 붙으므로 앞까지 잘라서 저장.
                $prev_img_path = explode('?', $prev_img_path)[0];
                $teacher->profile_img_path = $profile_img_path.'?'.Str::random(10);
                $teacher->save();
            }
            // 이미지 경로가 있으면, 확장자가 다르면 삭제처리.
            if(strlen($prev_img_path) > 0 && $prev_img_path != $profile_img_path) {
                // 기존 이미지 삭제
                Storage::delete('public/uploads/user_profile/'.$user_type.'/'.$prev_img_path);
            }
        }
        return response()->json(['resultCode' => 'success', 'profile_img_path' => $profile_img_path.'?'.Str::random(10)]);
    }

    // 프로필 이미지 삭제.
    function deleteProfileImg(Request $request){
        $user_seq = $request->input('user_seq');
        $user_type = $request->input('user_type');

        // 트랜잭션 시작
        $is_transaction_suc = true;
        DB::beginTransaction();
        try {

            // 유저 테이블 프로필 이미지 경로 가져오기.
            if($user_type == 'student') {
                $student = \App\Student::find($user_seq);
                $profile_img_path = $student->profile_img_path;
                $student->profile_img_path = null;
                $student->save();
            }else if($user_type == 'parent'){
                $parent = \App\ParentTb::find($user_seq);
                $profile_img_path = $parent->profile_img_path;
                $parent->profile_img_path = null;
                $parent->save();
            }else if($user_type == 'teacher'){
                $teacher = \App\Teacher::find($user_seq);
                $profile_img_path = $teacher->profile_img_path;
                $teacher->profile_img_path = null;
                $teacher->save();
            }

            // ? 가 있으면 뒤 없애주기.
            if(strlen($profile_img_path) > 0 && strpos($profile_img_path, '?') !== false) {
                $profile_img_path = explode('?', $profile_img_path)[0];
            }
            Storage::delete('public/uploads/user_profile/'.$user_type.'/'.$profile_img_path);

            DB::commit();
        } catch (\Exception $e) {
            $is_transaction_succ = false;
            DB::rollback();
            throw $e;
        }

        $result['resultCode'] = 'fail';
        if($is_transaction_suc) {
            $result['resultCode'] = 'success';
        }
        return response()->json($result);
    }

    // 비밀번호 확인
    public function checkPw(Request $request)
    {
        $user_type = $request->input('user_type');
        $user_seq = $request->input('user_seq');
        if(empty($user_type)){
            $user_type = session()->get('login_type');
        }
        if(empty($user_seq)){
            if($user_type == 'teacher') $user_seq = session()->get('teach_seq');
            if($user_type == 'admin') $user_seq = session()->get('teach_seq');
            if($user_type == 'student') $user_seq = session()->get('student_seq');
            if($user_type == 'parent') $user_seq = session()->get('parent_seq');
        }
        $user_pw  = $request->input('user_pw');

        // 학생일경우
        if($user_type == 'student') {
            // user_pw SHA1() mysql 함수로 비밀번호 암호화.
            $password = DB::raw("SHA1(?)");
            $student = \App\Student::where('id', $user_seq)->whereRaw('student_pw = '.$password, [$user_pw])->first();
            if($student) {
                return response()->json(['resultCode' => 'success']);
            } else {
                return response()->json(['resultCode' => 'fail', 'user_pw' => $user_pw]);
            }
        }
        // 선생님 일경우.
        if($user_type == 'teacher' || $user_type == 'admin'){
            $password = DB::raw("SHA1(?)");
            $teacher = \App\Teacher::where('id', $user_seq)->whereRaw('teach_pw = '.$password, [$user_pw]);
            $teacher = $teacher->first();
            if($teacher) {
                return response()->json(['resultCode' => 'success']);
            } else {
                return response()->json([
                    'resultCode' => 'fail',
                    'user_pw' => $user_pw,
                ]);
            }
        }
    }

    // 회원 정보 변경
    public function userInfoUpdate(Request $request)
    {
        $user_type = $request->input('user_type');
        $user_seq = $request->input('user_seq');
        $user_pw = $request->input('user_pw');
        $user_current_pw = $request->input('user_current_pw');
        $user_phone = $request->input('user_phone');
        $user_tel = $request->input('user_tel');
        $user_name = $request->input('user_name');
        $user_address = $request->input('user_address');
        $user_mail = $request->input('user_mail');
        $is_pw = $request->input('is_pw');
        $is_phone = $request->input('is_phone');
        $is_address = $request->input('is_address');
        $is_mail = $request->input('is_mail');

        //로그 기록
        $log = new LogMT();
        $req = new Request();
        $req->merge([
            'teach_seq' => 0,
            'student_seq' => 0,
            'parent_seq' => 0,
            'log_title' => 'user_detail_update',
            'log_remark' => '',
            'log_subject' => '',
            'log_content' => '',
            'log_type' => 'user_update',
            'write_type' => session()->get('login_type')
        ]);
        $log_content = "";

        if($user_type == 'student') {
            $students = \App\Student::find($user_seq);
            if($is_pw == 'true') {
                // user_pw SHA1() mysql 함수로 비밀번호 암호화해서 저장.
                // 혹시 추후 기존암호를 받아와서 확인하는 작업 할 수도 있으므로 대비.
                // $user_current_pw
                $students->student_pw = DB::raw("SHA1('".$user_pw."')");
            }
            if($is_phone == 'true') {
                $students->student_phone = $user_phone;
                // 인증 취소 is_auth_phone
                $students->is_auth_phone = 'N';
            }
            if($is_address == 'true') {
                $students->student_address = $user_address;
            }
            if($is_mail == 'true') {
                $students->student_email = $user_mail;
                // 인증 취소 is_auth_email
                $students->is_auth_email = 'N';

            }
            $students->save();
            return response()->json(['resultCode' => 'success']);
        } else if($user_type == 'parent') {

            if(session()->get('login_type') == 'parent') {
                $user_seq = session()->get('parent_seq');
            }

            $parent = \App\ParentTb::find($user_seq);
            if($user_name) {
                if($parent->parent_name != $user_name){
                    $log_content .= "이름 : ".$parent->parent_name." -> ".$user_name."\n";
                }
                $parent->parent_name = $user_name;
            }
            if($user_tel) {
                if($parent->parent_tel != $user_tel){
                    $log_content .= "전화번호 : ".$parent->parent_tel." -> ".$user_tel."\n";
                }
                $parent->parent_tel = $user_tel;
            }
            if($user_mail) {
                if($parent->parent_email != $user_mail){
                    $log_content .= "이메일 : ".$parent->parent_email." -> ".$user_mail."\n";
                    $parent->is_auth_email = 'N';
                }
                $parent->parent_email = $user_mail;
            }
            if($user_address) {
                if($parent->parent_address != $user_address){
                    $log_content .= "주소 : ".$parent->parent_address." -> ".$user_address."\n";
                }
                $parent->parent_address = $user_address;
            }

            $req->merge([
                'parent_seq' => $user_seq,
                'log_subject' => "학부모 정보 수정",
                'log_content' => $log_content,
            ]);
            $log->insert($req);
            $parent->save();
            return response()->json(['resultCode' => 'success']);
        } else if($user_type == 'teacher'){
            if(session()->get('login_type') == 'teacher') {
                $user_seq = session()->get('teach_seq');
            }
            $teacher = \App\Teacher::find($user_seq);

            if($user_pw){
                $user_pw_hash = DB::raw("SHA1('".$user_pw."')");
                if($teacher->teach_pw != $user_pw_hash){
                    $log_content .= "비밀번호: 변경.\n";
                }
                $teacher->teach_pw = $user_pw_hash;
            }

            if($user_phone){
                if($teacher->teach_phone != $user_phone){
                    $log_content .= "전화번호 : ".$teacher->teach_phone." -> ".$user_phone."\n";
                }
                $teacher->teach_phone = $user_phone;
                $teacher->is_auth_phone = 'N';
            }

            if($user_mail){
                if($teacher->teach_email != $user_mail){
                    $log_content .= "이메일 : ".$teacher->teach_email." -> ".$user_mail."\n";
                }
                $teacher->teach_email = $user_mail;
                $teacher->is_auth_email = 'N';
            }

            if($user_address){
                if($teacher->teach_address != $user_address){
                    $log_content .= "주소 : ".$teacher->teach_address." -> ".$user_address."\n";
                }
                $teacher->teach_address = $user_address;
            }

            $req->merge([
                'teach_seq' => $user_seq,
                'log_subject' => "선생님 정보 수정",
                'log_content' => $log_content,
            ]);

            $log->insert($req);
            $teacher->save();
            return response()->json(['resultCode' => 'success']);
        }
    }

    // 자녀 등록.
    public function childInsert(Request $request)
    {
        $student_name = $request->input('student_name');
        $school_name = $request->input('school_name');
        $grade = $request->input('grade');
        $student_pw1 = $request->input('student_pw1');
        $student_phone = $request->input('student_phone');
        $student_email = $request->input('student_email');
        $student_address = $request->input('student_address');
        $student_id = $request->input('student_id');

        $parent_seq = session()->get('parent_seq');

        if($parent_seq == "") {
            return response()->json(['resultCode' => 'fail']);
        }

        $log = new LogMT();
        $req = new Request();
        $req->merge([
            'teach_seq' => 0,
            'student_seq' => 0,
            'parent_seq' => $parent_seq,
            'log_title' => 'child_insert',
            'log_remark' => '',
            'log_subject' => '학부모 > 자녀 등록',
            'log_content' => '',
            'log_type' => 'child_insert',
            'write_type' => session()->get('login_type')
        ]);
        $log_content = "";

        $student = new \App\Student();
        if($student_name) {
            $log_content .= "이름 : ".$student_name."\n";
            $student->student_name = $student_name;
        }
        if($school_name) {
            $log_content .= "학교 : ".$school_name."\n";
            $student->school_name = $school_name;
        }
        if($grade) {
            $log_content .= "학년 : ".$grade."\n";
            $student->grade = $grade;
        }
        if($student_pw1) {
            $log_content .= "비밀번호 : ".$student_pw1."\n";
            $student->student_pw = DB::raw('SHA1(?)', [$student_pw1]);
        }
        if($student_phone) {
            $log_content .= "전화번호 : ".$student_phone."\n";
            $student->student_phone = $student_phone;
        }
        if($student_email) {
            $log_content .= "이메일 : ".$student_email."\n";
            $student->student_email = $student_email;
        }
        if($student_address) {
            $log_content .= "주소 : ".$student_address."\n";
            $student->student_address = $student_address;
        }
        $student->student_id = $student_id;
        $student->parent_seq = $parent_seq;
        $student->save();

        // 결과
        return response()->json(['resultCode' => 'success']);
    }
}
