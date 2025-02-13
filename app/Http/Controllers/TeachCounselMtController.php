<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeachCounselMtController extends Controller
{
    //----------------------------------------------
    // 학습상담
    //----------------------------------------------

    //list
    public function list(Request $request)
    {
        $teach_seq = $request->session()->get('teach_seq');
        $main_code = \App\Teacher::where('id', $teach_seq)->first()->main_code;
        // 학년 분류 가져오기
        $grade_codes = \App\Code::where('code_category', 'grade')->where('code_step', '=', 1)->where('main_code', '=', $main_code)->get();
        $grade_codes = $grade_codes->toArray();

        return view('teacher.teacher_counsel', [
            'grade_codes' => $grade_codes
        ]);
    }

    // 담당 학생 목록
    public function studentSelect(Request $request){
        $teach_seq = $request->session()->get('teach_seq');
        $search_type = $request->input('search_type');
        $search_str = $request->input('search_str');
        $is_goods_expire = $request->input('is_goods_expire');
        $next_counsel_date1 = $request->input('next_counsel_date1');
        $next_counsel_date2 = $request->input('next_counsel_date2');

        $students = \App\Student::select(
            'students.*',
            'gd.goods_seq',
            'gd.start_date as goods_start_date',
            'gd.end_date as goods_end_date',
            'gd.goods_name',
            'gd.goods_period',
            'gd.stop_day_sum',
            'gd.stop_cnt',
            'gd.is_use as goods_is_use',
            'grade_codes.code_name as grade_name',
            DB::raw('count(payments.id) as payment_cnt')
            )
            ->leftJoin('payments', function($join){
                $join->on('payments.student_seq', '=', 'students.id');
                $join->where('payments.status_no', '=', 1);
            })
            ->leftJoin('goods_details as gd', 'gd.id', '=', 'students.goods_detail_seq')
            ->leftJoin('codes as grade_codes', 'grade_codes.id', '=', 'students.grade')
            ->groupBy('students.id');


        // 조건
        // 관리자일때
        if(session()->get('login_type') == 'admin'){

        }
        // 총괄일때.
        else if(session()->get('login_type') == 'teacher' && session()->get('group_type2') == 'general' ){
            //우선은 미정. 어덯게 바뀔지.
            $team_code = session()->get('team_code');
            $students = $students->where('students.team_code', $team_code);
        }
        // 팀장일때
        else if(session()->get('login_type') == 'teacher' && session()->get('group_type2') == 'leader' ){
            $team_code = session()->get('team_code');
            $students = $students->where('students.team_code', $team_code);
        }
        // 그외
        else{
            // students.teach_seq'=$teach_seq or counsel_teach_seq = $teach_seq
            $students = $students->where(function($query) use ($teach_seq){
                $query->where('students.teach_seq', $teach_seq)
                    ->orWhere('students.counsel_teach_seq', $teach_seq);
            });

        }
        if($search_type == 'student_name' && $search_str != ''){
            $students->where('students.student_name', 'like', '%'.$search_str.'%');
        }

        $student_seqs = $students->get()->pluck('id')->toArray();

        // :최근상담일 가져오기.
        // :마지막 상담일 가져오기.
        $last_counsel_dates = \App\Counsel::select('student_seq', DB::raw('max(is_counsel_date) as last_counsel_date'))
            ->where('is_counsel', 'Y')
            ->whereIn('student_seq', $student_seqs)
            ->groupBy('student_seq')
            ->get();

        // == 라라벨 함수를 이용해서 student_seq를 key로 하는 배열로 만든다.
        $last_counsel_date_arr = $last_counsel_dates->pluck('last_counsel_date', 'student_seq')->toArray();

        // 다음 상담일 가져오기.
        $next_counsel_dates = \App\Counsel::
            select(
                'counsels.student_seq',
                DB::raw('min(concat(start_date, \' \', start_time,\'|\',is_change, \'|\',pt_detail.change_regular_target)) as start_date')
            )
            ->leftJoin('counsel_details as pt_detail', 'pt_detail.counsel_seq', '=', 'counsels.pt_seq')
            ->where('is_counsel', null)
            ->where('start_date', '>', date('Y-m-d'))
            ->whereIn('counsels.student_seq', $student_seqs)
            ->groupBy('counsels.student_seq')
            ->get();

        // == 라라벨 함수를 이용해서 student_seq를 key로 하는 배열로 만든다.
        $next_counsel_date_arr = $next_counsel_dates->pluck('start_date', 'student_seq')->toArray();


        // 결과
        $result = array();
        $result['students'] = $students->get();
        $result['resultCode'] = 'success';
        $result['last_counsel_date_arr'] = $last_counsel_date_arr;
        $result['next_counsel_date_arr'] = $next_counsel_date_arr;
        return response()->json($result);
    }

    //----------------------------------------------
    // 이용권 상담
    //----------------------------------------------

    public function goodsList(Request $request){
        $teach_seq = session()->get('teach_seq');
        $team_code = session()->get('team_code');
        $group_type2 = session()->get('group_type2');

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

        $team = \App\Team::where('team_code', $team_code)->first();


        return view('teacher.teacher_counsel_goods', [
            'regions' => $regions,
            'group_type2' => $group_type2,
            'team' => $team
        ]);
    }

    //----------------------------------------------
    // 기능
    //----------------------------------------------

    // 상담 기록 저장.
    public function insert(Request $request){
        $student_seqs = $request->input('student_seqs');
        $counsel_type = $request->input('counsel_type');
        $sel_date = $request->input('sel_date');
        $start_time = $request->input('start_time');
        $end_time = $request->input('end_time');

        $teach_seq = session()->get('teach_seq');
        $team_code = session()->get('team_code');

        //이용권 상담 추가.
        $counsel_category = $request->input('counsel_category');
        $student_types = $request->input('student_types');

        // 트랜잭션 시작
        $is_transaction_suc = true;
        DB::beginTransaction();
        try {
            foreach($student_seqs as $idx => $student_seq){
                $counsel_cnt = \App\Counsel::
                    select(DB::raw('count(*) as cnt'))
                    ->where('student_seq', $student_seq)
                    ->where('counsel_category', $counsel_category)
                    // ->where('is_counsel', 'Y')
                    ->groupBy('student_seq')->first();
                $counsel_cnt = $counsel_cnt->cnt ?? 0;

                $counsel = new \App\Counsel;
                $counsel->student_seq = $student_seq;
                $counsel->team_code = $team_code;
                $counsel->counsel_type = $counsel_type;
                $counsel->start_date = $sel_date;
                $counsel->fixed_start_date = $sel_date;
                if($start_time != ':'){
                    $counsel->start_time = $start_time;
                    $counsel->fixed_start_time = $start_time;
                }
                if($end_time != ':'){
                    $counsel->end_time = $end_time;
                    $counsel->fixed_end_time = $end_time;
                }
                $counsel->teach_seq = $teach_seq;
                if($counsel_type == 'regular'){
                    $counsel->is_repeat = 'Y';
                }
                if($counsel_category) $counsel->counsel_category = $counsel_category;
                if($student_types[$idx]) $counsel->student_type = $student_types[$idx]; // 혹시 이부분 오류 날지 추후 확인.
                $counsel->counsel_cnt = ($counsel_cnt+1);

                $counsel->save();
                $pt_seq = $counsel->id;

                if($counsel_type == 'regular'){
                    //반복일경우 start_date만 다음주로 변경해서 한번더 저장한다.
                    $pt_seq1 = $this->againInsert($pt_seq, $counsel_type);
                    $pt_seq2 = $this->againInsert($pt_seq1, $counsel_type);
                    $this->againInsert($pt_seq2, $counsel_type);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            $is_transaction_succ = false;
            DB::rollback();
            throw $e;
        }


        //결과
        $result = array();
        if($is_transaction_suc)
            $result['resultCode'] = 'success';
        else
            $result['resultCode'] = 'fail';

        $result['counsel_seq'] = $pt_seq;
        return response()->json($result);
    }

    // 상담 목록 가져오기.
    public function select(Request $request){
        $counsel_category = $request->input('counsel_category');
        $sel_date = $request->input('sel_date');
        $counsel_types = $request->input('counsel_types');
        $search_type = $request->input('search_type');
        $search_str = $request->input('search_str');
        $is_order_by_detail_created_at = $request->input('is_order_by_detail_created_at');

        $student_seqs = $request->input('student_seqs');
        $is_counsel = $request->input('is_counsel');
        $is_transfer = $request->input('is_transfer');
        $get_type = $request->input('get_type');
        $page = $request->input('page');
        $page = $page ?? 1;
        $page_max = $request->input('page_max');
        $page_max = $page_max ?? 10;

        $search_start_date = $request->input('search_start_date');
        $search_end_date = $request->input('search_end_date');

        // $sql->paginate($page_max, ['*'], 'page', $page);

        $teach_seq = $request->session()->get('teach_seq');
        $group_type2 = $request->session()->get('group_type2');

        //조건
        $search_student_type = $request->input('search_student_type');


        // 관리자/총괄/팀장/관리자 일경우
        if($group_type2 == 'manage' || $group_type2 == 'general' || $group_type2 == 'leader'){
            $teach_seq = $request->input('teach_seq') ? $request->input('teach_seq') : $teach_seq;
        }

        $counsels = \App\Counsel::
            select(
                'counsels.*',
                'students.student_name',
                'students.student_id',
                'students.school_name',
                'students.student_phone',
                'students.point_now',
                'students.team_code',
                'user_groups.group_name as group_name',
                'gd.start_date as goods_start_date',
                'gd.end_date as goods_end_date',
                'gd.goods_name',
                'gd.goods_period',
                'pt.parent_name as parent_name',
                'pt.parent_phone as pt_parent_phone',
                'codes.code_name as grade_name',
                'pt_detail.change_regular_target as pt_change_regular_target'
            )
            ->leftJoin('students', 'students.id', '=', 'counsels.student_seq')
            ->leftJoin('parents as pt', 'pt.id', '=', 'students.parent_seq')
            ->leftJoin('user_groups', 'user_groups.id', '=', 'students.group_seq')
            ->leftJoin('goods_details as gd', 'gd.id', '=', 'students.goods_detail_seq')
            ->leftJoin('codes', 'students.grade', '=', 'codes.id')
            ->leftJoin('counsel_details as pt_detail', 'pt_detail.counsel_seq', '=', 'counsels.pt_seq')
            ->where('counsels.teach_seq', $teach_seq)
            ->whereIn('counsels.counsel_type', $counsel_types);

        // 조건 추가.
        // 신규회원, 만료회원, 휴먼해제 회원 조건
        if(strlen($search_student_type) > 0){
            if($search_student_type == 'new'){
                //신규회원
                $counsels = $counsels->where('counsels.student_type', 'new');
            }
            else if($search_student_type == 'expire'){
                //만료
                //goods_name is not null
                $counsels = $counsels->where('gd.goods_name', '!=', null);
                $counsels = $counsels->where('gd.end_date', '<', date('Y-m-d'));
            }
            else if($search_student_type == 'dormant'){
                //휴면해제
                // 만료이면서 goods_end_date 가 오늘과 차이가 1년 이상일때
                $counsels = $counsels->where('gd.goods_name', '!=', null);
                $counsels = $counsels->where('gd.end_date', '<', date('Y-m-d'));
                $counsels = $counsels->where('gd.end_date', '<', date('Y-m-d', strtotime('-1 year')));
            }
        }
        // 이관요청 인지 확인(이관요청은 이용권 상담만 가능함.)
        if($is_transfer == 'Y'){
            $counsels = $counsels->leftJoin('counsel_transfers', 'counsel_transfers.counsel_seq', '=', 'counsels.id');
            $counsels = $counsels->where('counsel_transfers.id', '!=', null);
            $counsels = $counsels->addSelect(
                'counsel_transfers.is_move as transfer_is_move',
                'counsel_transfers.transfer_reason',
                'counsel_transfers.transfer_reg_date'
            );
        }else{
            $counsels = $counsels->where('counsels.is_transfer', null);
        }
        // 이용권 상담 유무
        if($counsel_category == ''){
            $counsels = $counsels->where('counsels.counsel_category', null);
        }else{
            $counsels = $counsels
            ->addSelect(
                'teams.team_name',
                'regions.id as region_seq',
                'regions.region_name',
                'teachers.teach_name',
                'teach_user_groups.group_name as teach_group_name'

            )
            ->leftJoin('teams', 'teams.team_code', '=', 'students.team_code')
            ->leftJoin('regions', 'regions.id', '=', 'teams.region_seq')
            ->leftJoin('teachers', 'teachers.id', '=', 'counsels.teach_seq')
            ->leftJoin('user_groups as teach_user_groups', 'teach_user_groups.id', '=', 'teachers.group_seq')
            ->where('counsels.counsel_category', $counsel_category);
        }

        if(strlen($sel_date) > 0){
            $counsels = $counsels->where('counsels.start_date', $sel_date);
        }
        if(strlen($is_counsel) > 0){
            if($is_counsel == 'Y'){
                $counsels = $counsels->where('counsels.is_counsel', 'Y');

                //한곳에만 사용하므로 / 이용권 상세 화면. / content도 같이 가져온다.
                if($is_order_by_detail_created_at == 'Y'){
                    $counsels = $counsels->leftJoin('counsel_details', 'counsel_details.counsel_seq', '=', 'counsels.id');
                    $counsels = $counsels->addSelect(
                        'counsel_details.start_datetime',
                        'counsel_details.end_datetime',
                        'counsel_details.content',
                        'counsel_details.counsel_how'
                    );
                }
            }
            else
                $counsels = $counsels->where('counsels.is_counsel', null);
        }
        if(strlen($search_start_date) > 0 && strlen($search_end_date) > 0){
            $counsels = $counsels->where('counsels.start_date', '>=', $search_start_date);
            $counsels = $counsels->where('counsels.start_date', '<=', $search_end_date);
        }
        if($student_seqs != null && count($student_seqs) > 0){
            $counsels = $counsels->whereIn('counsels.student_seq', $student_seqs);
        }
        if(strlen($search_type) > 0){
            if($search_type == 'student_name'){
                $counsels = $counsels->where('students.student_name', 'like', '%'.$search_str.'%');
            }
            else if($search_type == 'student_phone'){
                $counsels = $counsels->where('students.student_phone', 'like', '%'.$search_str.'%');
            }
            else if($search_type == 'grade'){
                //codes.code_name like $search_str
                $counsels = $counsels->where('codes.code_name', 'like', '%'.$search_str.'%');
            }
        }

        // ORDER BY
        if($is_order_by_detail_created_at == 'Y'){
            $counsels = $counsels->orderBy('counsel_details.created_at', 'desc');
        }
        $counsel = $counsels->orderBy('counsels.start_date', 'asc')
        ->orderBy('counsels.start_time', 'asc')
        ->orderBy('students.student_name', 'asc');

        $result['sql'] = $counsels->toSql();
        $result_counsels_data = $counsels->get();

        // counsels 의 student_seq를 배열로 가져온다.
        // $student_seqs = $result_counsels_data->pluck('student_seq')->toArray();
        if($get_type == 'page'){
            $result_counsels = $counsels->paginate($page_max, ['*'], 'page', $page);
            // $result_counsels의 데이터를 넣어준다.
            $result_counsels_data = $result_counsels->items();
            $student_seqs = array_column($result_counsels_data, 'student_seq');
        }else{
            $student_seqs = $result_counsels_data->pluck('student_seq')->toArray();
        }

        // 마지막 상담일 가져오기.
        // 이용권일경우 조건
        //  == 라라벨 함수를 이용해서 student_seq를 key로 하는 배열로 만든다.
        $last_counsel_dates = \App\Counsel::select('student_seq', DB::raw('max(is_counsel_date) as last_counsel_date'))
            ->where('is_counsel', 'Y')
            ->whereIn('student_seq', $student_seqs)
            ->groupBy('student_seq');
        if($counsel_category == 'goods') $last_counsel_dates = $last_counsel_dates->where('counsel_category', 'goods');
        $last_counsel_date_arr = $last_counsel_dates->get()->pluck('last_counsel_date', 'student_seq')->toArray();

        // 다음 상담일 가져오기.
        // 이용권일경우 조건
        //  == 라라벨 함수를 이용해서 student_seq를 key로 하는 배열로 만든다.
        $next_counsel_dates = \App\Counsel::select('student_seq', DB::raw('min(concat(start_date, \' \', start_time)) as start_date'))
            ->where('is_counsel', null)
            ->where('start_date', '>', date('Y-m-d'))
            ->whereIn('student_seq', $student_seqs)
            ->groupBy('student_seq');
        if($counsel_category == 'goods') $next_counsel_dates = $next_counsel_dates->where('counsel_category', 'goods');
        $next_counsel_date_arr = $next_counsel_dates->get()->pluck('start_date', 'student_seq')->toArray();

        // 첫 상담일 가져오기.
        // 이용권일경우 조건
        //  == 라라벨 함수를 이용해서 student_seq를 key로 하는 배열로 만든다.
        $first_counsel_dates = \App\Counsel::select('student_seq', DB::raw('min(concat(start_date)) as start_date'))
            ->whereIn('student_seq', $student_seqs)
            ->groupBy('student_seq');
        if($counsel_category == 'goods') $first_counsel_dates = $first_counsel_dates->where('counsel_category', 'goods');
        $first_counsel_date_arr = $first_counsel_dates->get()->pluck('start_date', 'student_seq')->toArray();

        // 선택 날짜에


        $result['counsels'] = $get_type == 'page' ? $result_counsels : $result_counsels_data;
        $result['resultCode'] = 'success';
        $result['last_counsel_date_arr'] = $last_counsel_date_arr;
        $result['next_counsel_date_arr'] = $next_counsel_date_arr;
        $result['first_counsel_date_arr'] = $first_counsel_date_arr;

        return response()->json($result);

    }

    // 캘린더 목록 가져오기.
    public function calendarSelect(Request $request){
        $counsel_category = $request->input('counsel_category');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $counsel_types = $request->input('counsel_types');

        $search_type = $request->input('search_type');
        $search_str = $request->input('search_str');

        $teach_seq = $request->session()->get('teach_seq');

        // select counsel_type, date(start_date) as start_date, count(*) from counsels where teach_seq = 2 and start_date between '2024-04-01' and '2024-04-30' and counsel_type in ('regular', 'no_regular') group by counsel_type, start_date
        $counsels = \App\Counsel::
            leftJoin('students', 'students.id', '=', 'counsels.student_seq')
            ->leftJoin('codes', 'students.grade', '=', 'codes.id')
            ->where('counsels.teach_seq', $teach_seq)
            ->whereBetween('start_date', [$start_date, $end_date])
            ->whereIn('counsel_type', $counsel_types);
            //이용권 상담 목록일 경우.
            if($counsel_category == 'goods'){
                $counsels = $counsels
                ->select(
                    'student_type as counsel_type',
                    DB::raw('date(start_date) as start_date'),
                    DB::raw('count(*) as cnt')
                )
                ->where('is_transfer', null)
                ->groupBy('student_type', DB::raw('date(start_date)'));
            }
            //학습 상담 목록 일경우.
            else{
                $counsels = $counsels
                ->select(
                    'counsel_type',
                    DB::raw('date(start_date) as start_date'),
                    DB::raw('count(*) as cnt')
                )
                ->groupBy('counsel_type', DB::raw('date(start_date)'));
            }

        if($counsel_category == ''){
            $counsels->where('counsels.counsel_category', null);
        }else{
            $counsels->where('counsels.counsel_category', $counsel_category);
        }
        if(strlen($search_type) > 0){
            if($search_type == 'student_name'){
                $counsels->where('students.student_name', 'like', '%'.$search_str.'%');
            }
            else if($search_type == 'student_phone'){
                $counsels->where('students.student_phone', 'like', '%'.$search_str.'%');
            }
            else if($search_type == 'grade'){
                //codes.code_name like $search_str
                $counsels->where('codes.code_name', 'like', '%'.$search_str.'%');
            }
        }
        $counsels = $counsels->get();

        $result['counsels'] = $counsels;
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    public function againInsert($counsel_seq, $counsel_type){
        $counsel = \App\Counsel::find($counsel_seq);
        //을 가지고 와서
        // new Counsel를 만들어서 저장한다.

        $new_counsel = new \App\Counsel;
        $new_counsel->team_code = $counsel->team_code;
        $new_counsel->student_seq = $counsel->student_seq;
        $new_counsel->counsel_type = $counsel_type ?? $counsel->counsel_type;
        $new_counsel->start_date = date('Y-m-d', strtotime($counsel->fixed_start_date.' + 7 days'));
        $new_counsel->fixed_start_date = date('Y-m-d', strtotime($counsel->fixed_start_date.' + 7 days'));
        $new_counsel->start_time = $counsel->fixed_start_time;
        $new_counsel->fixed_start_time = $counsel->fixed_start_time;
        $new_counsel->end_time = $counsel->fixed_end_time;
        $new_counsel->fixed_end_time = $counsel->fixed_end_time;
        $new_counsel->teach_seq = $counsel->teach_seq;
        $new_counsel->is_repeat = 'Y';
        $new_counsel->pt_seq = $counsel->id;
        $new_counsel->save();

        $counsel->is_use_repeat = 'Y';
        $counsel->save();
        return $new_counsel->id;
    }

    // 상담 목록에서 상담일 변경기능
    public function changeDateUpdate(Request $request){
        $teach_seq = session()->get('teach_seq');
        $counsel_seq = $request->input('counsel_seq');
        $start_date = $request->input('start_date');
        $start_time = $request->input('start_time');
        $end_time = $request->input('end_time');

        $counsel = \App\Counsel::find($counsel_seq);
        $counsel->start_date = $start_date;
        $counsel->fixed_start_date = $start_date;
        $counsel->start_time = $start_time;
        $counsel->counsel_type = 'no_regular';
        $counsel->save();

        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    // 상담 (이용권 상담)의 상단탭 카운트가져오기 대기 / 완료 / 이관 요청
    function goodsCountSelect(Request $request){
        $group_type2 = session()->get('group_type2');
        $teach_seq = session()->get('teach_seq');
        $post_teach_seq = $request->input('teach_seq');
        // 만약 총괄/팀장/관리자 일경우 post_teach_seq가 있으면 그값을 사용한다.
        if($group_type2 == 'general' || $group_type2 == 'leader' || $group_type2 == 'manage'){
            if($post_teach_seq) $teach_seq = $post_teach_seq;
        }

        $counsels = \App\Counsel::
            select(
                DB::raw('count(*) as cnt')
            )
            ->where('teach_seq', $teach_seq)
            ->where('counsel_category', 'goods');

        // :라라벨 쿼리 클론
        $stay_cnt = (clone $counsels)->where('is_counsel', null)->where('is_transfer', null)->first()->cnt ?? 0;
        $complete_cnt = (clone $counsels)->where('is_counsel', 'Y')->where('is_transfer', null)->first()->cnt ?? 0;
        $transfer_cnt = (clone $counsels)->where('is_transfer', 'Y')->first()->cnt ?? 0;

        $result = array();
        $result['stay_cnt'] = $stay_cnt;
        $result['complete_cnt'] = $complete_cnt;
        $result['transfer_cnt'] = $transfer_cnt;
        $result['resultCode'] = 'success';
        return response()->json($result);

    }

    // 상담 이관 요청
    public function transferInsert(Request $request){
        $counsel_seq = $request->input('counsel_seq');
        $transfer_reason = $request->input('transfer_reason');
        $transfer_reg_date = $request->input('transfer_reg_date');

        // :트랙잭션 시작
        $is_transaction_suc = true;
        $is_already_transfer = false;
        DB::beginTransaction();
        try {
            $counsel = \App\Counsel::find($counsel_seq);
            $counsel->is_transfer = 'Y';
            $counsel->save();

            $counsel_transfer = \App\CounselTransfer::where('counsel_seq', $counsel_seq)->where('is_move', null)->first();
            //이미 있으므로 롤백
            if($counsel_transfer){
                $is_transaction_suc = false;
                $is_already_transfer = true;
                DB::rollback();
            }
            //이관요청 저장
            else{
                $counsel_transfer = new \App\CounselTransfer;
                $counsel_transfer->counsel_seq = $counsel_seq;
                $counsel_transfer->transfer_reason = $transfer_reason;
                $counsel_transfer->transfer_reg_date = $transfer_reg_date;
                $counsel_transfer->before_teach_seq = $counsel->teach_seq;
                $counsel_transfer->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            $is_transaction_suc = false;
            DB::rollback();
            throw $e;
        }

        //결과
        $result = array();
        if($is_transaction_suc){
            $result['resultCode'] = 'success';
        }else{
            if($is_already_transfer){
                $result['resultCode'] = 'already_transfer';
            }else{
                $result['resultCode'] = 'fail';
            }
        }
        return response()->json($result);
    }

    // 선택 상담 상태변경
    public function isCounselUpdate(Request $request){
        $counsel_seqs = $request->input('counsel_seqs');
        $is_counsel = $request->input('is_counsel');
        $teach_seq = $request->input('teach_seq');

        //트랜잭션 시작
        $is_transaction_suc = true;
        DB::beginTransaction();
        try {
            //상담 완료로 변경.
            foreach($counsel_seqs as $counsel_seq){
                $counsel = \App\Counsel::find($counsel_seq);
                $student_seq = $counsel->student_seq;
                //이용권
                if($is_counsel == 'Y'){
                    $counsel->is_counsel = 'Y';
                    $counsel->is_counsel_date = date('Y-m-d H:i:s');
                    $counsel->save();

                    // 이용권 detail 추가.
                    $counsel_detail = new \App\CounselDetail;
                    $counsel_detail->counsel_seq = $counsel_seq;
                    $counsel_detail->counsel_type = $counsel->counsel_type;
                    $counsel_detail->target_type = '';
                    $counsel_detail->content = '상태변경으로 인한 변경.';
                    $counsel_detail->start_datetime = date('Y-m-d H:i:s');
                    $counsel_detail->end_datetime = date('Y-m-d H:i:s');
                    $counsel_detail->save();
                }
                //상담 대기로 변경.
                else{
                    $counsel->is_counsel = null;
                    $counsel->is_counsel_date = null;
                    $counsel->save();

                    // 이용권 detail 삭제.
                    \App\CounselDetail::where('counsel_seq', $counsel_seq)->delete();
                }

                // 이용권 상담의 횟수를 업데이트 한다.
                {
                    // select * from counsels where student_seq = 2714 and counsel_category = 'goods' order by is_counsel desc,start_date
                    $counsel_cnt_update = \App\Counsel::
                    where('student_seq', $student_seq)
                    ->where('counsel_category', 'goods')
                    ->orderBy('is_counsel', 'desc')
                    ->orderBy('start_date')
                    ->get();
                    $counsel_cnt = 0;
                    foreach($counsel_cnt_update as $counsel){
                        if($counsel->is_transfer != 'Y'){
                            $counsel_cnt++;
                            $counsel->counsel_cnt = $counsel_cnt;
                            $counsel->save();
                        }else{
                            $counsel->counsel_cnt = null;
                            $counsel->save();
                        }
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            $is_transaction_suc = false;
            DB::rollback();
            throw $e;
        }

        //결과
        $result = array();
        if($is_transaction_suc)
            $result['resultCode'] = 'success';
        else
            $result['resultCode'] = 'fail';
        return response()->json($result);
    }

    // 이관 요청 승인
    public function transferConfirmUpdate(Request $request){
        $counsel_seq = $request->input('counsel_seq');
        $change_teach_seq = $request->input('change_teach_seq');
        $before_teach_seq  = $request->input('before_teach_seq');

        //트랜잭션 시작
        $is_transaction_suc = true;
        $rt_msg = '';
        DB::beginTransaction();
        try{
            $counsel = \App\Counsel::find($counsel_seq);
            if($counsel && $counsel->teach_seq == $before_teach_seq && strlen($change_teach_seq) > 0){
                $counsel->teach_seq = $change_teach_seq;
                $counsel->is_transfer = null;
                $counsel->save();
                $rt_msg = 'ok 1';

                //이관요청 업데이트
                $counsel_transfer = \App\CounselTransfer::
                where('counsel_seq', $counsel_seq)
                ->where('is_move', null)
                ->where('before_teach_seq', $before_teach_seq)
                ->first();

                if($counsel_transfer){
                    $counsel_transfer->is_move = 'Y';
                    $counsel_transfer->before_teach_seq = $before_teach_seq;
                    $counsel_transfer->after_teach_seq = $change_teach_seq;
                    $counsel_transfer->save();
                    $rt_msg = 'ok 2';
                }
                else{
                    $rt_msg = 1;
                    $is_transaction_suc = false;
                    DB::rollback();
                }
            }
            else{
                $rt_msg = 2;
                $is_transaction_suc = false;
                DB::rollback();
            }
            DB::commit();
        }catch(\Exception $e){
            $rt_msg = 3;
            $is_transaction_suc = false;
            DB::rollback();
            throw $e;
        }

        //결과
        $result = array();
        if($is_transaction_suc)
            $result['resultCode'] = 'success';
        else
            $result['resultCode'] = 'fail';
        $result['rt_msg'] = $rt_msg;
        return response()->json($result);
    }

    // 학생의 마지막 상담일 가져오기.
    public function lastCounselSelect(Request $request){
        $student_seq = $request->input('student_seq');

        $last_counsel = \App\Counsel::
            select(
            DB::raw('max(start_date) as last_counsel_date')
            )
            ->where('start_date', '<=', date('Y-m-d'))
            ->where('student_seq', $student_seq)
            ->where('counsel_category', null)
            ->first();

        $last_date = $last_counsel->last_counsel_date ?? '';

        $counsel = \App\Counsel::
        where('student_seq', $student_seq)
        ->where('start_date', $last_date)
        ->first();

        //결과
        $result = array();
        $result['resultCode'] = 'success';
        $result['counsel'] = $counsel;
        return response()->json($result);
    }
}
