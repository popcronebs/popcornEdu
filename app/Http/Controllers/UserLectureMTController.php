<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserLectureMTController extends Controller
{
    //list( 관리자/운영선생님)
    public function list(){
        $login_type = session()->get('login_type');
        $region_seq = $region_seq = session()->get('region_seq');
        $main_code = $_COOKIE['main_code'];
        //소속 가져오기
        $regions = \App\Region::where('main_code', $main_code);
        if($login_type == 'teacher'){
            $regions = $regions->where('id', $region_seq);
        }else if($login_type == 'admin'){

        }
        $is_one_region = false;
        if(count($regions->get()) == 1) $is_one_region = true;

        // 과목
        $subject_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'subject')->where('code_step', '=', 1)
        ->orderBy('code_idx', 'asc')
        ->get();

                
        return view('admin.admin_user_lecture', ['regions'=>$regions->get(), 'is_one_region'=>$is_one_region, 'subject_codes'=>$subject_codes]);
    }

    public function studentList(){

    }


    //회원 검색
    public function userSelect(Request $request){
        $region_seq = $request->region_seq;
        $team_code = $request->team_code;
        $search_type = $request->search_type;
        $search_str = $request->search_str;


        // where students.team_code in (select team_code from regions where id = 1)
        $students = \App\Student::
            select( 'students.*', 
                    'parents.parent_name', 
                    'parents.parent_phone',
                    'regions.region_name',
                    'teams.team_name'
            )
            ->leftJoin('parents', 'students.parent_seq', '=', 'parents.id')
            ->leftJoin('teams', 'students.team_code', '=', 'teams.team_code')
            ->leftJoin('regions', 'teams.region_seq', '=', 'regions.id');

        //추가 코드 결제 마지막 날.

        //소속은 무조건 있어야하므로.
        $students = $students->whereIn('students.team_code', function($query) use ($region_seq){
            $query->select('team_code')->from('teams')->where('region_seq', $region_seq);
        });

        //조건
        if($team_code != ""){
            $students = $students->where('students.team_code', $team_code);
        }
        if($search_str != ""){
            if($search_type == "student_name"){
                $students = $students->where('student_name', 'like', '%'.$search_str.'%');
            }else if($search_type == "student_phone"){
                $students = $students->where('student_phone', 'like', '%'.$search_str.'%');
            }else if($search_type == "parent_name"){
                $students = $students->where('parents.parent_name', 'like', '%'.$search_str.'%');
            }else if($search_type == "parent_phone"){
                $students = $students->where('parents.parent_phone', 'like', '%'.$search_str.'%');
            }
        }

        $students = $students->orderBy('student_name', 'asc');

        // 결과
        $result = array();
        $result['students'] = $students->get();
        $result['resultCode'] = 'success';
        return response()->json($result, 200);
    }

    // 강좌 카운트 가져오기.
    public function cntSelect(Request $request){
        $region_seq = $request->region_seq;
        $team_code = $request->team_code;
        $student_seq = $request->student_seq;

        //학생의 강좌 카운트 가져오기.
        // select 
        //     sum(if(status = 'complete', 1, 0)) as complete_cnt,
        //     sum(if(is_like = 'Y', 1, 0)) as like_cnt,
        //     sum(if(status = 'ready' and sel_date <= date(now()), 1, 0)) as ready_cnt,
        //     sum(if(is_again = 'Y' and status = 'complete', 1, 0)) as again_cnt  
        // from student_lecture_details where student_seq = '2714'
        $student_lecture_details = \App\StudentLectureDetail::
            selectRaw('sum(if(student_lecture_details.status = "complete" and student_lectures.status = "complete", 1, 0)) as complete_cnt,
                        sum(if(student_lecture_details.is_like = "Y", 1, 0)) as like_cnt,
                        sum(if(student_lecture_details.status = "ready" and sel_date <= date(now()), 1, 0)) as ready_cnt,
                        sum(if(student_lecture_details.is_again = "Y" and student_lecture_details.status = "complete", 1, 0)) as again_cnt')
            ->leftJoin('student_lectures', 'student_lecture_details.student_lecture_seq', '=', 'student_lectures.id')
            ->where('student_lecture_details.student_seq', $student_seq)
            ->first();
        
        $result = array();
        $result['student_lecture_details'] = $student_lecture_details;
        $result['resultCode'] = 'success';
        return response()->json($result, 200); 
    }
}
