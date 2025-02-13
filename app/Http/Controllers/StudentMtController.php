<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UserMTController as userMt;


class StudentMtController extends Controller
{
    //
    public function list()
    {
        $teach_seq = session()->get('teach_seq');
        $group_type2 = session()->get('group_type2');
        $group_type3 = session()->get('group_type3');

        //로그인 세션에서 team_code 가져오기
        $team_code = session()->get('team_code');

        // teams 테이블에서 team_code로 team_name 가져오기
        $sel_team = \App\Team::select('teams.*', 'regions.region_name as region_name')
            ->leftJoin('regions', 'regions.id', '=', 'teams.region_seq')
            ->where('team_code', $team_code)->first();

        $team = \App\Team::orderBy('team_code')->get();
        $team = $team->toArray();


        //관리자나 총괄일경우
        //본부(리전) SELECT
        if($group_type2 == 'manage'){
            $regions = \App\Region::all();
        }
        else if($group_type2 == 'general'){
            $regions = \App\Region::where('general_teach_seq', $teach_seq)->get();
        }else{
            //그외 선생님
            $regions = \App\Region::whereIn('id', function ($query) use ($team_code) {
                $query->select('region_seq')
                    ->from('teams')
                    ->where('team_code', $team_code);
            })->get();
        }

        // return view('teacher.teacher_student', ['sel_team' => $sel_team]);
        return view('teacher.teacher_student', [
            'sel_team' => $sel_team,
            'team' => $team,
            'group_type2' => $group_type2,
            'group_type3' => $group_type3,
            'regions' => $regions
        ]);
    }

    // 학생 상세보기
    public function studentDetail(Request $request){
        $main_code = $_COOKIE['main_code'] ?? session()->get('main_code');
        $student_seq = $request->input('student_seq');
        $teach_seq = session()->get('teach_seq');

        // 학정 정보 가져오기
        $student = \App\Student::select(
            'students.*',
            'grade_codes.code_name as grade_name',
            'gd.goods_seq',
            'gd.start_date as goods_start_date',
            'gd.end_date as goods_end_date',
            'gd.goods_name',
            'gd.goods_period',
            'gd.stop_day_sum',
            'gd.stop_cnt',
            'gd.is_use as goods_is_use',
            'gd.origin_end_date',
            'teach.teach_name as teach_name',
            'teach.teach_id as teach_id',
            'teach.teach_phone',
            'teams.team_name',
            'teams.team_type',
            'regions.region_name'


        )
            ->leftJoin('codes as grade_codes', 'grade_codes.id', '=', 'students.grade')
            ->leftJoin('goods_details as gd', 'gd.id', '=', 'students.goods_detail_seq')
            ->leftJoin('teachers as teach', 'teach.id', '=', 'students.teach_seq')
            ->leftJoin('teams', 'teams.team_code', '=', 'students.team_code')
            ->leftJoin('regions', 'regions.id', '=', 'teams.region_seq')
            ->where('students.id', $student_seq)->first();

        // 팀 타입 가져오기
        $team_type = \App\Team::select('team_type')->where('team_code', $student->team_code)->first();

        // 학생이 속한 방과후 클래스 가져오기
        $after_class = \App\ClassMate::
            select(
                DB::raw('group_concat(classes.class_name) as class_name')
            )
            ->leftJoin('classes', 'classes.id', '=', 'class_mates.class_seq')
            ->where('class_mates.student_seq', $student_seq)
            ->where ('class_mates.is_use', 'Y')
            ->first()->class_name;

        // 연결된 학부모정보 가져오기.
        $parent = \App\ParentTb::select('*')->where('id', $student->parent_seq)->first();

        // 연결된 형제 정보 가져오기.
        $children = \App\Student::select('students.*')
            ->where('parent_seq', $student->parent_seq)
            ->get();

        // 학년.
        $grade_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'grade')->where('code_step', '=', 1)
        ->orderBy('code_idx', 'asc')
        ->get();

        // 담당하고 있는 클래스 리스트 가져오기
        $classes = \App\ClassTb::select('classes.*')
        ->where('team_code', $student->team_code)
        ->where('teach_seq', $teach_seq)
        ->get();

        return view('teacher.teacher_student_detail', [
            'student' => $student,
            'team_type' => $team_type,
            'after_classes' => $after_class,
            'parent' => $parent,
            'grade_codes' => $grade_codes,
            'classes' => $classes,
            'children' => $children
        ]);
    }

    // 방과후 학생 관리
    public function afterList()
    {
        // 로그인 세션에서 team_code 가져오기.
        $teach_seq = session()->get('teach_seq');
        $team_code = session()->get('team_code');
        $region_seq = session()->get('region_seq');

        // 담당하고 있는 클래스 리스트 가져오기
        $classes = \App\ClassTb::select('classes.*')
        ->where('team_code', $team_code)
        ->where('teach_seq', $teach_seq)
        ->get();

        return view('teacher.teacher_student_after', [
            'region_seq' => $region_seq,
            'team_code' => $team_code,
            'teach_seq' => $teach_seq,
            'classes' => $classes,
        ]);
    }

    // 방과후 학생 상세보기
    public function afterDetail(Request $request)
    {
        $main_code = $_COOKIE['main_code'] ?? session()->get('main_code');
        $student_seq = $request->input('student_seq');
        $class_seq = $request->input('class_seq');
        $teach_seq = session()->get('teach_seq');

        // 학정 정보 가져오기
        $student = \App\Student::select(
            'students.*',
            'teams.team_name',
            'teams.team_type',
            'grade_codes.code_name as grade_name'
        )
            ->leftJoin('codes as grade_codes', 'grade_codes.id', '=', 'students.grade')
            ->leftJoin('teams', 'teams.team_code', '=', 'students.team_code')
            ->where('students.id', $student_seq)->first();
        if($student){
            $student->class_seq = $class_seq;
        }
        // 팀 타입 가져오기
        $team_type = \App\Team::select('team_type')->where('team_code', $student->team_code)->first();

        // 학생이 속한 방과후 클래스 가져오기
        $after_class = \App\ClassMate::
            select(
                DB::raw('group_concat(classes.class_name) as class_name')
            )
            ->leftJoin('classes', 'classes.id', '=', 'class_mates.class_seq')
            ->where('class_mates.student_seq', $student_seq)
            ->where ('class_mates.is_use', 'Y')
            ->first()->class_name;

        // 연결된 학부모정보 가져오기.
        $parent = \App\ParentTb::select('*')->where('id', $student->parent_seq)->first();

        // 학년.
        $grade_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'grade')->where('code_step', '=', 1)
        ->orderBy('code_idx', 'asc')
        ->get();

        // 담당하고 있는 클래스 리스트 가져오기
        $classes = \App\ClassTb::select('classes.*')
        ->where('team_code', $student->team_code)
        ->where('teach_seq', $teach_seq)
        ->get();

        return view('teacher.teacher_student_after_detail', [
            'student' => $student,
            'team_type' => $team_type,
            'after_classes' => $after_class,
            'parent' => $parent,
            'grade_codes' => $grade_codes,
            'classes' => $classes
        ]);
    }


    //학생 목록 불러오기.
    public function studentSelect(Request $request)
    {

        $region_seq = $request->input('region_seq');
        $teach_seq = $request->input('teach_seq');
        $req_team_code = $request->input('team_code');

        $search_type = $request->input('search_type');
        $search_str = $request->input('search_str');
        $team_code = $request->session()->get('team_code');
        $group_type2 = session()->get('group_type2');
        $ss_teach_seq = session()->get('teach_seq');
        if($req_team_code){
            $team_code = $req_team_code;
        }

        // 페이징 쿼리
        $page = $request->input('page');
        $page = $page ?? 1;
        $page_max = $request->input('page_max');
        $page_max = $page_max ?? 6;
        $is_page = $request->input('is_page');
        // $sql->paginate($page_max, ['*'], 'page', $page);

        $students = \App\Student::select(
            'students.*',
            'pt.parent_name as parent_name',
            'pt.parent_phone as parent_phone',
            'gd.goods_seq',
            'gd.start_date as goods_start_date',
            'gd.end_date as goods_end_date',
            'gd.goods_name',
            'gd.goods_period',
            'gd.stop_day_sum',
            'gd.stop_cnt',
            'gd.is_use as goods_is_use',
            'gd.origin_end_date',
            'grade_codes.code_name as grade_name',
            'teams.team_name as team_name',
            'teach.teach_name as teach_name',
            'teach.teach_id as teach_id',
            'regions.region_name'
        )
            ->leftJoin('parents as pt', 'pt.id', '=', 'students.parent_seq')
            ->leftJoin('goods_details as gd', 'gd.id', '=', 'students.goods_detail_seq')
            ->leftJoin('codes as grade_codes', 'grade_codes.id', '=', 'students.grade')
            ->leftJoin('teams', 'teams.team_code', '=', 'students.team_code')
            ->leftJoin('teachers as teach', 'teach.id', '=', 'students.teach_seq')
            ->leftJoin('regions', 'regions.id', '=', 'teams.region_seq');
            // ->where('students.team_code', $team_code);


        // 관리자나 총괄일경우의 조건
        if($group_type2 == 'general' || $group_type2 == 'manage'){
            $result['test'] = '1';
            if($req_team_code){
                $result['test'] = '2';
                $students = $students->where('students.team_code', $req_team_code);
            }else{
                $result['test'] = '3';
                if($region_seq){
                    $students = $students->where('students.team_code', function($query) use ($region_seq){
                        $query->select('team_code')
                            ->from('teams')
                            ->where('region_seq', $region_seq);
                    });
                }else{
                    $result['test'] = '4';
                    $students = $students->whereIn('students.team_code',  function($query) use ($ss_teach_seq){
                        //regions에서 region_seq를 먼저 가져와서 temas에서 team_code를 가져온다.
                        $query->select('team_code')
                            ->from('teams')
                            ->whereIn('region_seq',  function($query) use ($ss_teach_seq){
                                $query->select('id')
                                    ->from('regions')
                                    ->where('general_teach_seq', $ss_teach_seq);
                            });
                    });
                }
            }
            if($teach_seq){
                $students = $students->where('students.teach_seq', $teach_seq);
            }
        }
        // 팀장 조건
        else if($group_type2 == 'leader'){
            // 소속 선생님만 선택 안해도 된다.
            $students = $students->where('students.team_code', $team_code);
            if($teach_seq)
                $students = $students->where('students.teach_seq', $teach_seq);
        }
        // 그외 조건
        else{
            // 모든 조건이 다 있어야한다.
            $students = $students->where('students.region_seq', $region_seq);
            $students = $students->where('students.team_code', $team_code);
            $students = $students->where('students.teach_seq', $teach_seq);
        }

        // 조건이 있을 경우 조건 실행.
        if(strlen($search_str) > 0) {
            if($search_type == 'student_name' || $search_type == 'name') {
                $students = $students->where('students.student_name', 'like', '%'.$search_str.'%');
            } else if($search_type == 'grade') {
                $students = $students->where('grade_codes.code_name', 'like', '%'.$search_str.'%');
            } else if($search_type == 'student_phone') {
                $students = $students->where('students.student_phone', 'like', '%'.$search_str.'%');
            } else if($search_type == 'student_id') {
                $students = $students->where('students.student_id', 'like', '%'.$search_str.'%');
            } else if($search_type == 'goods_name') {
                $students = $students->where('gd.goods_name', 'like', '%'.$search_str.'%');
            }
        }


        $result['sql'] = $students->toSql();
        $result['bindings'] = $students->getBindings();

        if($is_page == 'Y') {
            $students = $students->paginate($page_max, ['*'], 'page', $page);
        } else {
            $students = $students->get();
        }


        // 결과
        /* $result = array(); */
        $result['group_type2'] = $group_type2;
        $result['students'] = $students;
        $result['resultCode'] = 'success';
        return response()->json($result, 200);
    }

    public function afterDetailUpdate(Request $request){
        $student_seq = $request->input('student_seq');
        $student_id = $request->input('student_id');
        $student_name = $request->input('student_name');
        $student_grade = $request->input('student_grade');
        $st_class_name = $request->input('student_class_name');
        $student_pw = $request->input('student_pw');
        $student_phone = $request->input('student_phone');
        $student_email = $request->input('student_email');
        $student_address = $request->input('student_address');
        $class_seq = $request->input('class_seq');
        $prev_class_seq = $request->input('prev_class_seq');
        $school_name = $request->input('school_name');

        $parent_seq = $request->input('parent_seq');
        $parent_id = $request->input('parent_id');
        $parent_phone2 = $request->input('parent_phone2');
        $parent_address = $request->input('parent_address');

        // userMt 사용
        $userMt = new userMt();
        $req = new Request();
        $req2 = new Request();

        $req->merge([
            'grouptype' => 'student',
            'user_key' => $student_seq,
            // 'user_name' => $student_name,
            // 'grade' => $student_grade,
            // 'st_class_name' => $st_class_name,
            // 'user_pw' => $student_pw,
            // 'user_phone' => $student_phone,
            // 'user_email' => $student_email,
            // 'user_address' => $student_address,
        ]);
        $student_id = \App\Student::where('id', $student_seq)->first()->student_id;
        $parent_id = \App\ParentTb::where('id', $parent_seq)->first()->parent_id;

        if($student_id) $req->merge(['user_id' => $student_id]);
        if($student_name) $req->merge(['user_name' => $student_name]);
        if($student_grade) $req->merge(['grade' => $student_grade]);
        if($st_class_name) $req->merge(['st_class_name' => $st_class_name]);
        if($student_pw) $req->merge(['user_pw' => $student_pw]);
        if($student_phone) $req->merge(['user_phone' => $student_phone]);
        if($student_email) $req->merge(['user_email' => $student_email]);
        if($student_address) $req->merge(['user_addr' => $student_address]);
        if($school_name) $req->merge(['school_name' => $school_name]);


        $req2->merge([
            'grouptype' => 'parent',
            'user_key' => $parent_seq,
            // 'user_phone2' => $parent_phone2,
        ]);
        if($parent_id) $req2->merge(['user_id' => $parent_id]);
        if($parent_phone2) $req2->merge(['user_phone2' => $parent_phone2]);
        if($parent_address) $req2->merge(['user_addr' => $parent_address]);

        // 트랜잭션 시작.
        $is_transaction_suc = true;
        $error_message = '';
        DB::beginTransaction();
        try {

            $userMt->userInsert($req);
            $userMt->userInsert($req2);

            if($prev_class_seq??'' != ''){
                // 있으면 업데이트.
                $class_mate = \App\ClassMate::where('student_seq', $student_seq)->where('class_seq', $prev_class_seq)->first();
                if($class_mate){
                    $class_mate->class_seq = $class_seq;
                    $class_mate->save();
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            $is_transaction_suc = false;
            $error_message = $e->getMessage();
            DB::rollback();
            throw $e;
        }

        // 결과.
        $result = array();
        if($is_transaction_suc){
            $result['resultCode'] = 'success';
        }else{
            $result['resultCode'] = 'fail';
        }
        $result['error_message'] = $error_message;
        return response()->json($result, 200);
    }

    // 학생 정보관리리 > 학생 포인트 리스트.
    public function goodsSelect(Request $request){
        $student_seq = $request->input('student_seq');

        $page = $request->input('page');
        $page = $page ?? 1;
        $page_max = $request->input('page_max');
        $page_max = $page_max ?? 6;
        $is_page = $request->input('is_page');

        $goods_details = \App\GoodsDetail::select(
            'goods_details.*',
            'payments.payment_date',
            'payments.regular_date',
            'payments.amount',
            'payments.card_name',
            'payments.is_regular',
            'payments.student_type as pay_student_type'
         )
        ->leftJoin('payments', 'payments.goods_detail_seq', '=', 'goods_details.id')
        ->where('goods_details.student_seq', $student_seq);


        if($is_page == 'Y') {
            $goods_details = $goods_details->paginate($page_max, ['*'], 'page', $page);
        } else {
            $goods_details = $goods_details->get();
        }

        $result = array();
        $result['resultCode'] = 'success';
        $result['goods_details'] = $goods_details;
        return response()->json($result);
    }

}
