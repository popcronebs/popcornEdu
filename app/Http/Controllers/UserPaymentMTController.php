<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserPaymentMTController extends Controller
{
    // 결제관리
    public function list()
    {
        $main_code = $_COOKIE['main_code'] ?? session()->get('main_code'); 
        $group_type2 = session()->get('group_type2');
        $teach_seq = session()->get('teach_seq');
        $team_code = session()->get('team_code');

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

        $goods = \App\Goods::where('main_code', $main_code)->get();

        return view('admin.admin_user_payment', [
            'group_type2' => $group_type2,
            'regions' => $regions,
            'team' => $team,
            'goods' => $goods
        ]);
    }

    // 결제상세정보
    public function paymentDetail(Request $request){
        $main_code = $_COOKIE['main_code'] ?? session()->get('main_code'); 
        $payment_seq = $request->input('payment_seq');
        $student_seq = $request->input('student_seq');

        $payment = \App\Payment::select(
            'payments.*',
            'students.student_id',
            'students.student_name',
            'teachers.teach_name',
            'teams.team_name',
            'regions.region_name',
            'parents.parent_name',
            'parents.parent_id',
            'gd_due.goods_name as goods_due_name',
            'gd_due.goods_period as goods_due_period',
            'gd_due.start_date as goods_due_start_date',
            'gd_due.end_date as goods_due_end_date',
            'gd_due.stop_day_sum as goods_due_stop_day_sum',
            'gd_due.stop_cnt as goods_due_stop_cnt',
            'gd_due.is_use as goods_due_is_use',

            'gd.start_date as goods_start_date',
            'gd.end_date as goods_end_date',
            'gd.goods_name',
            'gd.goods_period',
            'gd.stop_day_sum',
            'gd.stop_cnt',
            'gd.is_use as goods_is_use'

        )
        ->leftJoin('students', 'payments.student_seq', '=', 'students.id')
        ->leftJoin('teachers', 'payments.created_id', '=', 'teachers.id')
        ->leftJoin('teams', 'teachers.team_code', '=', 'teams.team_code')
        ->leftJoin('regions', 'teams.region_seq', '=', 'regions.id')
        ->leftJoin('parents', 'students.parent_seq', '=', 'parents.id')
        ->leftJoin('goods_details as gd', 'gd.id', '=', 'students.goods_detail_seq')
        ->leftJoin('goods_details as gd_due', 'gd_due.id', '=', 'payments.goods_detail_seq')
        ->where('payments.id',$payment_seq)
        ->where('payments.student_seq', $student_seq);

        

        $goods = \App\Goods::where('main_code', $main_code)->get();

        //sql 확인
        $payment = $payment->first();
        return view('admin.admin_user_payment_detail', [
            'payment_seq' => $payment_seq,
            'student_seq' => $student_seq,
            'payment' => $payment,
            'goods' => $goods
        ]);
    }
    // 결제관리 > 결제요약 카운트 가져오기.
    private function paymentSummaryCount($teach_seq, $team_code, $is_complete){
        $teacher = \App\Teacher::select('teachers.*', 'user_groups.group_type2', 'user_groups.group_type3')
        ->leftJoin('user_groups', 'teachers.group_seq', '=', 'user_groups.id')
        ->where('teachers.id', $teach_seq)->first();

        $group_type2 = $teacher->group_type2;
        $group_type3 = $teacher->group_type3;

        $payments = \App\Payment::select(
            DB::raw('count(*) as cnt')
        )
        ->leftJoin('students', 'payments.student_seq', '=', 'students.id')
        ->leftJoin('goods_details as gd', 'gd.id', '=', 'students.goods_detail_seq');

        // 조건

        // 기본 조건.
        if($group_type2 == 'general'){
            // 자기 팀아래 선생님들 결제내역 보여줌
            $teacher_wh = \App\Teacher::select('id')->where('team_code', $team_code)->get();
            $teach_seqs = $teacher_wh->pluck('id')->toArray();
            $payments = $payments->whereIn('payments.created_id', $teach_seqs);
        }
        if($group_type2 == 'leader'){
            // 자기 팀아래 선생님들 결제내역 보여줌
            $teacher_wh = \App\Teacher::select('id')->where('team_code', $team_code)->get();
            $teach_seqs = $teacher_wh->pluck('id')->toArray();
            $payments = $payments->whereIn('payments.created_id', $teach_seqs);
        }
        if($group_type2 == 'run'){
            if($group_type3 == 'counsel'){
                $payments = $payments->where('payments.created_id', $teach_seq);
            }
            else{
                $payments = $payments->where('payments.created_id', $teach_seq);
            }
        }

        // all_stay
        $all_stay_cnt = (clone $payments)->where('payments.status_no', '9')->first()->cnt;
        // all_complete
        $all_complete_cnt = (clone $payments)->where('payments.status_no', '1')->first()->cnt;

        // 대기 / 완료 조건
        if($is_complete == 'Y'){
            $payments = $payments->where('payments.status_no', '1'); // 완료    
        }else{
            $payments = $payments->where('payments.status_no', '9'); // 대기
        }

        $all_cnt = (clone $payments)->first()->cnt;
        // 신규
        $new_cnt = (clone $payments)->where('payments.student_type', 'new')->first()->cnt;
        // 재등록 회원
        $readd_cnt = (clone $payments)->where('payments.student_type', 'readd')->first()->cnt;
        // 오늘 결제 예정 payment_due_date
        $today_payment_cnt = (clone $payments)->where('payments.payment_due_date', date('Y-m-d'))->first()->cnt; 

        // 만료임박회원 
        $expire_cnt = (clone $payments)->where('gd.goods_name', '!=', null)->where('gd.end_date', '<', date('Y-m-d'))->first()->cnt;
        // 정기결제 
        $regular_cnt = (clone $payments)->where('payments.is_regular', 'regular')->first()->cnt;

        $result = array();
        $result['all_cnt'] = $all_cnt;
        $result['all_stay_cnt'] = $all_stay_cnt;
        $result['all_complete_cnt'] = $all_complete_cnt;
        $result['new_cnt'] = $new_cnt;
        $result['readd_cnt'] = $readd_cnt;
        $result['today_payment_cnt'] = $today_payment_cnt;
        $result['expire_cnt'] = $expire_cnt;
        $result['regular_cnt'] = $regular_cnt;

        return $result;
    }

    // 결제관리 > 조건에 맞는 결제 리스트 가져오기.
    public function paymentSelect(Request $request){
        $teach_seq = $request->input('teach_seq');
        $search_type = $request->input('search_type');
        $team_code = $request->input('team_code');
        $is_complete = $request->input('is_complete'); 

        // :페이징 쿼리
        $page = $request->input('page');
        $page = $page ?? 1;
        $page_max = $request->input('page_max');
        $page_max = $page_max ?? 6;
        // $sql->paginate($page_max, ['*'], 'page', $page);

        $teacher = \App\Teacher::select('teachers.*', 'user_groups.group_type2', 'user_groups.group_type3')
        ->leftJoin('user_groups', 'teachers.group_seq', '=', 'user_groups.id')
        ->where('teachers.id', $teach_seq)->first();
        
        $group_type2 = $teacher->group_type2;
        $group_type3 = $teacher->group_type3;
        
        // 쿼리
        $payments = \App\Payment::select(
            'payments.*',
            'students.student_name',
            'students.student_id',
            'students.school_name',

            'gd.goods_seq',
            'gd.start_date as goods_start_date',
            'gd.end_date as goods_end_date',
            'gd.goods_name',
            'gd.goods_period',
            'gd.stop_day_sum',
            'gd.stop_cnt',

            'gd.is_use as goods_is_use',
            'gd_due.goods_name as goods_due_name',
            'gd_due.goods_period as goods_due_period',
            'gd_due.stop_day_sum as goods_due_stop_day_sum',
            'gd_due.stop_cnt as goods_due_stop_cnt',
            'gd_due.is_use as goods_due_is_use'
        )
        ->leftJoin('students', 'payments.student_seq', '=', 'students.id')
        ->leftJoin('goods_details as gd', 'gd.id', '=', 'students.goods_detail_seq')
        ->leftJoin('goods_details as gd_due', 'gd_due.id', '=', 'payments.goods_detail_seq')

        ->where('payments.status_no', $is_complete == 'Y' ? '1' : '9');

        // 조건

        // 기본 조건.
        if($group_type2 == 'general'){
            // 자기 팀아래 선생님들 결제내역 보여줌
            $teacher_wh = \App\Teacher::select('id')->where('team_code', $team_code)->get();
            $teach_seqs = $teacher_wh->pluck('id')->toArray();
            $payments = $payments->whereIn('payments.created_id', $teach_seqs);
        }
        if($group_type2 == 'leader'){
            // 자기 팀아래 선생님들 결제내역 보여줌
            $teacher_wh = \App\Teacher::select('id')->where('team_code', $team_code)->get();
            $teach_seqs = $teacher_wh->pluck('id')->toArray();
            $payments = $payments->whereIn('payments.created_id', $teach_seqs);
            $is_test = 2;
        }
        if($group_type2 == 'run'){
            if($group_type3 == 'counsel'){
                $payments = $payments->where('payments.created_id', $teach_seq);
            }
            else{
                $payments = $payments->where('payments.created_id', $teach_seq);
            }
        }

        if($search_type){
            if($search_type == 'new'){
                $payments = $payments->where('payments.student_type', 'new');
            }
            if($search_type == 'readd'){
                $payments = $payments->where('payments.student_type', 'readd');
            }
            if($search_type == 'today_payment_yet'){
                $payments = $payments->where('payments.payment_due_date', date('Y-m-d'));
            }
            if($search_type == 'expire'){
                $payments = $payments->where('gd.goods_name', '!=', null)->where('gd.end_date', '<', date('Y-m-d'));
            }
            if($search_type == 'regular'){
                $payments = $payments->where('payments.is_regular', 'regular');
            }
        }
        
        // $payments = $payments->get();
        // $sql->paginate($page_max, ['*'], 'page', $page);
        $payments = $payments->paginate($page_max, ['*'], 'page', $page);
        // 마지막 상담일 가져오기.
        $last_counsel_date_arr = $this->lastCounselSelect($payments);

        // 결과
        $result = array();
        $result['payments'] = $payments;
        $result['resultCode'] = 'success';
        //group_type2, group_type3
        $result['group_type2'] = $group_type2;
        $result['group_type3'] = $group_type3;
        $result['cnt_arr'] = $this->paymentSummaryCount($teach_seq, $team_code, $is_complete);
        $result['last_counsel_date_arr'] = $last_counsel_date_arr;
        return response()->json($result);   
    }

    // 결제관리에서 마지막 상담일 가져오기.
    private function lastCounselSelect($payments){
        // 최근 상담일지 불러오기.
        $student_seqs = $payments->getCollection()->pluck('student_seq')->toArray();
        // 마지막 상담일 가져오기.
        $last_counsel_dates = \App\Counsel::select('student_seq', DB::raw('max(is_counsel_date) as last_counsel_date'))
            ->where('is_counsel', 'Y')
            ->whereIn('student_seq', $student_seqs)
            ->groupBy('student_seq')
            ->get();
        
        // == 라라벨 함수를 이용해서 student_seq를 key로 하는 배열로 만든다.
        $last_counsel_date_arr = $last_counsel_dates->pluck('last_counsel_date', 'student_seq')->toArray();
        return $last_counsel_date_arr;
    }
    
    //
    public function paymentHistorySelect(Request $request){
        $payment_seq = $request->input('payment_seq');
        $student_seq = $request->input('student_seq');
        $start_date = $request->input('start_date');
        $end_date  = $request->input('end_date');

        $payments = \App\Payment::select(
            'payments.*'
        )
        ->where('id', '<=', $payment_seq)
        ->where('student_seq', $student_seq)
        ->whereBetween('created_at', [$start_date, $end_date])
        ->orderBy('id', 'desc')
        ->get();

        // 결과
        $result = array();
        $result['payments'] = $payments;
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    // 결제관리 > 결제상세정보 > 결제내역 부분 수정.
    public function paymentPartUpdate(Request $request){
        $payment_seq = $request->input('payment_seq');
        //컬럼_타입
        $column_type = $request->input('type');
        $data  = $request->input('data');

        $payment = \App\Payment::find($payment_seq);
        $goods_detail_seq = $payment->goods_detail_seq;
        $goods_detail = \App\GoodsDetail::find($goods_detail_seq);
        
        if($column_type == 'payment_memo'){
            $payment->payment_memo = $data;
            $payment->save();
        }
        //start
        if($column_type == 'goods_start_date'){
            $goods_detail->start_date = $data;
            $goods_detail->save();
        }
        //end
        if($column_type == 'goods_end_date'){
            $goods_detail->end_date = $data;
            $goods_detail->save();
        }

        // 결과
        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result);
    }
}
