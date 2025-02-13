<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClassManagementMtController extends Controller
{
    public function list(Request $request){
        $teach_seq = session()->get('teach_seq');
        $main_code = session()->get('main_code') ?? $_COOKIE['main_code'] ?? '';

        // 선생님이 속한 전체 리스트를 가져온다.
        $classes = \App\ClassTb::
            select('classes.*', 'codes.code_name as grade_name')
            ->where('teach_seq', $teach_seq)
            ->leftJoin('codes', 'codes.id', '=', 'classes.grade')
            ->get();

        // 학년
        $grade_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'grade')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();

        return view(
            'teacher.teacher_after_class_management',
            [
                'classes' => $classes,
                'grade_codes' => $grade_codes,
            ]
        );
    }

    // 수업리스트 가져오기
    public function classSelect(Request $request){
        $teach_seq = session()->get('teach_seq');
        $stat_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $search_type = $request->input('search_type');
        $search_str = $request->input('search_str');
        $class_seq = $request->input('class_seq');

        // 만약에 class_seq 가 없으면
        // 해당 선생님의 모든 클래스 seq를 가져온다.
       if(strlen($class_seq) < 1){
             // select * from classes where teach_seq = 2
            $classes = \App\ClassTb::where('teach_seq', $teach_seq)->get();
            $class_seq = $classes->pluck('id')->toArray();
       }

        // 보강 리스트

        $absent_refs = \App\Absent::
            selectRaw(
                'date, day, count(*) cnt'
            )
            ->leftJoin('dates', 'dates.date', '=', 'absents.ref_date')
            ->where('class_seq', $class_seq)
            ->whereBetween('ref_date', [$stat_date, $end_date])
            ->groupBy('ref_date');

            // $result['sql']= $absent_refs->toSql();
            // $result['bind'] = $absent_refs->getBindings();
            $absent_refs = $absent_refs->get();

        // 정규수업 리스트

        $class_study_dates = \App\ClassStudyDate::
            selectRaw(
                'date, day, count(*) cnt'
            )
            ->leftJoin('dates', 'dates.day', '=', 'class_study_dates.class_day')
            ->where('class_study_dates.class_seq', $class_seq)
            ->whereBetween('date', [$stat_date, $end_date])
            ->groupBy('date');

            // $result['sql']= $class_study_dates->toSql();
            // $result['bind'] = $class_study_dates->getBindings();
            $class_study_dates = $class_study_dates->get();

        // 결과
        $result['resultCode'] = 'success';
        $result['class_study_dates'] = $class_study_dates;
        $result['absent_refs'] = $absent_refs;
        return response()->json($result);

    }

    // 수업 상세 리스트 가져오기
    public function classDetailSelect(Request $request){
        $teach_seq = session()->get('teach_seq');
        $sel_date = $request->input('sel_date');
        $class_seq = $request->input('class_seq');

        // 추후 쓸지 모르겠음.
        $types = $request->input('types');

        // 클래스 seq 가 없으면 해당 선생님의 모든 클래스 seq를 가져온다.
        if(strlen($class_seq) < 1){
            $classes = \App\ClassTb::where('teach_seq', $teach_seq)->get();
            $class_seq = $classes->pluck('id')->toArray();
        }

        // 해당 날짜의 수업 리스트와, 보충 리스트를 가져온다.
        $class_study_dates = \App\ClassStudyDate::
            selectRaw(
                'class_study_dates.*'
            )
            ->leftJoin('dates', 'dates.day', '=', 'class_study_dates.class_day')
            ->where('class_study_dates.class_seq', $class_seq)
            ->where('date', $sel_date);

            // $result['sql']= $class_study_dates->toSql();
            // $result['bind'] = $class_study_dates->getBindings();
            $class_study_dates = $class_study_dates->get();

        $absent_refs = \App\Absent::
            selectRaw(
                'absents.*, students.student_name'
            )
            ->leftJoin('students', 'students.id', '=', 'absents.student_seq')
            ->where('absents.ref_date', $sel_date)
            ->where('absents.class_seq', $class_seq)
            ->get();

        // 결과
        $result['resultCode'] = 'success';
        $result['class_study_dates'] = $class_study_dates;
        $result['absent_refs'] = $absent_refs;
        return response()->json($result);
    }

    // 보충 수업 삭제하기.
    public function absentRefDelete(Request $request){
        $absent_seq = $request->input('absent_seq');
        $student_seq = $request->input('student_seq');
        // 안전장치 까지 하려면 선생님 seq의 클래스인지까지 확인해야함.
        $teach_seq = session()->get('teach_seq');

        $absents = \App\Absent::where('id', $absent_seq)->where('student_seq', $student_seq)->first();
        $absents->absent_reason = null;
        $absents->is_ref_complete = null;
        $absents->ref_date = null;
        $absents->ref_start_time = null;
        $absents->ref_end_time = null;
        $absents->save();

        // 결과
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    // 보충 수업 학생 목록 가져오기.
    public function absentRefSelect(Request $request){
        $teach_seq = session()->get('teach_seq');
        $class_seq = $request->input('class_seq');
        $order_by = $request->input('order_by');

        if(strlen($class_seq) < 1){
            $classes = \App\ClassTb::where('teach_seq', $teach_seq)->get();
            $class_seq = $classes->pluck('id')->toArray();
        }

        // 보충 학생 목록
        $ref_students = \App\Absent::
            selectRaw(
                'absents.*,
                students.student_name,
                students.student_id,
                students.class_name as student_class_name,
                students.student_phone,
                codes.code_name as grade_name,
                classes.class_name'
            )
            ->leftJoin('students', 'absents.student_seq', '=', 'students.id')
            ->leftJoin('codes', 'students.grade', '=', 'codes.id')
            ->leftJoin('classes', 'absents.class_seq', '=', 'classes.id')
            ->whereIn('class_seq', $class_seq)
            ->whereNull('is_ref_complete')
            ->whereNull('ref_date');

        if($order_by == 'absent_abc'){
            $ref_students = $ref_students->orderBy('absent_date', 'asc');
        }else{
            $ref_students = $ref_students->orderBy('absent_date', 'asc');
        }

        $ref_students = $ref_students->get();

        // 결과
        $result['resultCode'] = 'success';
        $result['ref_students'] = $ref_students;
        return response()->json($result);
    }

    // 선택 학생 보강/보충 추가.
    public function absentRefInsert(Request $request){
        // absent_seqs: absent_seqs,
        // ref_start_time:ref_start_time,
        // ref_end_time: ref_end_time,
        // ref_date:ref_date
        $teach_seq= session()->get('teach_seq');
        $absent_seqs = $request->input('absent_seqs');
        $ref_start_time = $request->input('ref_start_time');
        $ref_end_time = $request->input('ref_end_time');
        $ref_date = $request->input('ref_date');

        // 여기서 중요한것은 결석시 absents 테이블에 무조건 insert를 하는 조건으로 진행한다.
        // 보충 추가
        $absents = \App\Absent::whereIn('id', $absent_seqs)->update([
            'ref_start_time' => $ref_start_time,
            'ref_end_time' => $ref_end_time,
            'ref_date' => $ref_date,
        ]);

        // 결과
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    // 보강 리스트 전체목록 리스트 불러오기.
    public function absentRefAllSelect(Request $request){
        $teach_seq = session()->get('teach_seq');
        $class_seq = $request->input('class_seq');

        $order_by = $request->input('order_by');
        $is_ref_complete = $request->input('is_ref_complete');
        $is_ref_expected = $request->input('is_ref_expected');
        $is_ref_notthing = $request->input('is_ref_notthing');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        // 페이징
        $page = $request->input('page');
        $page = $page ?? 1;
        $page_max = $request->input('page_max');
        $page_max = $page_max ?? 5;

        // 클래스 전체 일때.
        if(strlen($class_seq) < 1){
            $classes = \App\ClassTb::where('teach_seq', $teach_seq)->get();
            $class_seq = $classes->pluck('id')->toArray();
        }

        // 결석인데 결석 테이블에 없는 학생 생성해주기.
        $this->makeAbsentStudent($request);

        // 보충 학생 목록 + 결석했는데 absent에 테이블이 없는 학생도 같이 가져온다. 보강 미등록이 Y일 경우.
        $ref_students = \App\Absent::
            selectRaw(
                'absents.*,
                students.student_name,
                students.student_id,
                students.class_name as student_class_name,
                students.student_phone,
                codes.code_name as grade_name,
                classes.class_name'
            )
            ->leftJoin('students', 'absents.student_seq', '=', 'students.id')
            ->leftJoin('codes', 'students.grade', '=', 'codes.id')
            ->leftJoin('classes', 'absents.class_seq', '=', 'classes.id')
            ->whereIn('class_seq', $class_seq)
            ->where(function($query) use ($is_ref_complete, $is_ref_expected, $is_ref_notthing){
                if($is_ref_complete == 'Y'){
                    $query->where('is_ref_complete', 'Y');
                }

               if($is_ref_expected == 'Y'){
                    $query->orWhere(function($queryin1){
                        $queryin1->whereNull('is_ref_complete')
                            ->whereNotNull('ref_date');
                    });
                }

                if($is_ref_notthing == 'Y'){
                    $query->orWhere(function($queryin2){
                        $queryin2->whereNull('is_ref_complete')
                            ->whereNull('ref_date');
                    });
                }
            })
            ->where(function($query) use ($start_date, $end_date){
                $query->whereBetween('absent_date', [$start_date, $end_date])
                ->orWhereBetween('ref_date', [$start_date, $end_date]);
            });
            // ->whereNull('is_ref_complete')
            // ->whereNull('ref_date');

        if($order_by == 'absent_abc'){
            $ref_students = $ref_students->orderBy('absent_date', 'asc');
        }else{
            $ref_students = $ref_students->orderBy('absent_date', 'asc');
        }
        // $result['sql1'] = $ref_students->toSql();
        $ref_students_page = $ref_students->paginate($page_max, ['*'], 'page', $page);
        $ref_students = $ref_students->get();
        $student_seqs = $ref_students->pluck('student_seq')->toArray();


        // 총 출결해야할 일수와, 총 출결일수를 가져온다.
        // 요일을 가져온다.
        $total_days = \App\ClassStudyDate::where('class_seq', $class_seq)->get()->pluck('class_day')->toArray();

        // 총 를래스 (동영상을 보는 출석은 제외) 출석해야할 일수.
        $total_attend_cnt = \App\Date::
            selectRaw(
                'student_seq, class_seq, count(*) cnt'
            )
            ->leftJoin('class_mates', function($join) use ($class_seq){
                $join->on('dates.date', '<=', 'class_mates.end_date')
                    ->on('dates.date', '>=', 'class_mates.start_date')
                    ->whereIn('class_mates.class_seq', $class_seq)
                    ->whereNotNull('class_mates.student_seq');
            })
            ->whereBetween('date', [$start_date, $end_date])
            ->whereIn('day', $total_days)
            ->whereIn('class_mates.student_seq', $student_seqs)
            ->groupBy('student_seq', 'class_seq')
            ->get()
            ->groupBy('student_seq');


        // 클래스 출석한 일수.
        $attend_cnt = \App\Date::
            selectRaw(
                'student_seq, class_seq, attend_date, max(1) cnt'
            )
            ->leftJoin('attend_details', function($join) use ($class_seq){
                $join->on('dates.date', '=', 'attend_details.attend_date')
                    ->whereIn('attend_details.class_seq', $class_seq)
                    ->whereNotNull('attend_details.student_seq');
            })
            ->whereBetween('date', [$start_date, $end_date])
            ->whereIn('day', $total_days)
            ->whereIn('attend_details.student_seq', $student_seqs)
            ->groupBy('student_seq', 'class_seq', 'attend_date');
            $result['sql'] = $attend_cnt->toSql();
            $attend_cnt = $attend_cnt->get()
            ->groupBy('student_seq');

        // 결과
        $result['resultCode'] = 'success';
        $result['ref_students'] = $ref_students_page;
        $result['total_attend_cnts'] = $total_attend_cnt;
        $result['attend_cnts'] = $attend_cnt;
        return response()->json($result);
    }

    // 보강 일정 변경하기.
    public function absentRefDateUpdate(Request $request){
        $absent_seq = $request->input('absent_seq');
        $student_seq = $request->input('student_seq');
        $ref_date = $request->input('ref_date');
        $ref_start_time = $request->input('ref_start_time');
        $ref_end_time = $request->input('ref_end_time');

        // 추후 혹 안전장치 까지 하려면 선생님 seq의 클래스인지까지 확인해야함.
        $teach_seq = session()->get('teach_seq');
        $absents = \App\Absent::where('id', $absent_seq)->where('student_seq', $student_seq)->first();
        $absents->ref_date = $ref_date;
        $absents->ref_start_time = $ref_start_time;
        $absents->ref_end_time = $ref_end_time;
        $absents->save();

        // 결과
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    // 로그인 선생님의 반 클래스 결석 테이블 만들기.
    public function makeAbsentStudent(Request $request){
        $teach_seq = session()->get('teach_seq');
        $class_seq = $request->input('class_seq');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        if(strlen($class_seq) < 1){
            $classes = \App\ClassTb::where('teach_seq', $teach_seq)->get();
            $class_seq = $classes->pluck('id')->toArray();
        }

        // 클래스 가져온다.
        $classes = \App\ClassTb::whereIn('id', $class_seq)->get();
        foreach($classes as $class){
            // 클래스마다, 요일을 가져와서
            $class_study_dates = \App\ClassStudyDate::selectRaw('group_concat(class_day) as class_day')
                ->whereIn('class_seq', $class_seq)
                ->first();
            $class_days = explode(',', $class_study_dates->class_day);

            // 결석인데 결석쪽 테이블에 없는 학생의 날을 가져온다.
            $dates = \App\Date::
                selectRaw(
                    'dates.*,
                    class_mates.team_code,
                    class_mates.class_seq,
                    class_mates.student_seq,
                    class_mates.start_date,
                    class_mates.end_date,
                    attend_details.attend_date,
                    absents.absent_date'
                )
                ->leftJoin('class_mates', function($join) use ($class_seq){
                    $join->on('dates.date', '<=', 'class_mates.end_date')
                           ->on('dates.date', '>=', 'class_mates.start_date')
                            ->whereIn('class_mates.class_seq', $class_seq)
                            ->whereNotNull('class_mates.student_seq');
                })
                ->leftJoin('attend_details', function($join) use ($class_seq){
                    $join->on('dates.date', '=', 'attend_details.attend_date')
                        ->whereIn('attend_details.class_seq', $class_seq)
                        ->whereColumn('class_mates.student_seq', 'attend_details.student_seq');
                })
                ->leftJoin('absents', function($join) use ($class_seq){
                    $join->on('dates.date', '=', 'absents.absent_date')
                        ->whereIn('absents.class_seq', $class_seq)
                        ->whereColumn('class_mates.student_seq', 'absents.student_seq');
                })
                ->whereBetween('date', [$start_date, $end_date])
                ->where('dates.date', '<', date('Y-m-d'))
                ->whereIn('day', $class_days)
                ->whereNull('attend_date')
                ->whereNull('absent_date')
                ->get();



            // 결석 테이블에 넣는다.
            foreach($dates as $date){
                $absent = new \App\Absent;
                $absent->team_code = $date->team_code;
                $absent->class_seq = $date->class_seq;
                $absent->student_seq = $date->student_seq;
                $absent->absent_date = $date->date;
                $absent->absent_day = $date->day;
                $absent->save();
            }

        }
        $result['resultCode'] = 'success';
        return response()->json($result);
    }
}
