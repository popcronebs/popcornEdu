<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashBoardMtController extends Controller
{
    //
    public function list(Request $request)
    {
        $teach_seq = session()->get('teach_seq');
        $group_type2 = session()->get('group_type2');
        $group_type3 = session()->get('group_type3');

        return view('teacher.teacher_dash_board', [
            'teach_seq' => $teach_seq,
            'group_type2' => $group_type2,
            'group_type3' => $group_type3
        ]);
    }

    // 대쉬보드 > to do list 불러오기
    public function todolistSelect(Request $request)
    {
        $ss_teach_seq = session()->get('teach_seq');
        $teach_seq = $request->input('teach_seq');
        if ($teach_seq != $ss_teach_seq) {
            $teach_seq = $ss_teach_seq;
        }

        // to do list 불러오기
        $todolists = \App\TodoList::where('teach_seq', $teach_seq)
            ->orderBy('is_complete', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        // 결과
        $result = array();
        $result['resultCode'] = 'success';
        $result['todolists'] = $todolists;
        return response()->json($result);
    }

    // 대쉬보드 > to do list UPDATE(is_complete)
    public function todolistUpdate(Request $request)
    {
        $ss_teach_seq = session()->get('teach_seq');
        $teach_seq = $request->input('teach_seq');
        if ($teach_seq != $ss_teach_seq) {
            $teach_seq = $ss_teach_seq;
        }

        $todo_seq = $request->input('todo_seq');
        $is_complete = $request->input('is_complete');

        // to do list UPDATE(is_complete)
        $todolist = \App\TodoList::where('teach_seq', $teach_seq)
            ->where('id', $todo_seq)
            ->update([
                'is_complete' => $is_complete
            ]);

        // 결과
        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    // 대쉬보드 > to do list INSERT
    public function todolistInsert(Request $request){
        $ss_teach_seq = session()->get('teach_seq');
        $teach_seq = $request->input('teach_seq');
        if ($teach_seq != $ss_teach_seq) {
            $teach_seq = $ss_teach_seq;
        }

        $todo_content = $request->input('todo_content');
        $todo_sub_content = $request->input('todo_sub_content');

        // to do list INSERT
        $todolist = new \App\TodoList();
        $todolist->teach_seq = $teach_seq;
        $todolist->todo_content = $todo_content;
        $todolist->todo_sub_content = $todo_sub_content;
        $todolist->is_complete = 0;
        $todolist->save();

        // 결과
        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    // 대쉬보드 > 총괄 > 팀별 결제 완료 리스트 불러오기
    public function teamCntSelect(Request $request)
    {
        $ss_teach_seq = session()->get('teach_seq');
        $teach_seq = $request->input('teach_seq'); // 페이크.
        $group_type2 = session()->get('group_type2');
        $cnt_types = $request->input('cnt_types');
        $base_type = $request->input('base_type');

        if ($teach_seq != $ss_teach_seq) {
            $teach_seq = $ss_teach_seq;
        }
        //제네럴인데 base_type 이 all이 아니면 팅굼.
        if ($group_type2 != 'general' && $base_type == 'all') {
            $result = array();
            $result['resultCode'] = 'no_general';
            return response()->json($result);
        }

        // 팀별 결제 기본 쿼리.
        $total_cnts =
        \App\Team::
            whereIn('teams.region_seq', function ($query) use ($teach_seq) {
                $query->select('id')
                    ->from('regions')
                    ->where('general_teach_seq', $teach_seq);
            });

        // 베이스 타입에 따라서 그룹바이 변경.
        // 기본은 temas.
        if($base_type == 'all'){
            $total_cnts = $total_cnts
            ->select(
                'teams.region_seq'
            )
            ->orderBy('teams.region_seq', 'asc')
            ->groupBy('teams.region_seq');
        }
        else{
            $total_cnts = $total_cnts
            ->select(
                'teams.team_code',
                'teams.team_name'
            )
            ->orderBy('teams.team_code', 'asc')
            ->groupBy('teams.team_code');
        }


        $new_cnts = null;
        $counsel_yet_cnts = null;
        $payment_yet_cnts = null;
        $payment_cnts = null;

        // 신규 배정 카운트
        if (in_array('new', $cnt_types)) {
            //결제가 오늘 되었고, 선생님 배정이 된 친구.
            $new_cnts = (clone $total_cnts)
                ->selectRaw('count(goods_details.id) as new_cnt')
                // team_code
                ->leftJoin('students', 'students.team_code', '=', 'teams.team_code')
                ->leftJoin('goods_details', function ($query) {
                    $query->
                        on('goods_details.id', '=','students.goods_detail_seq')
                        ->whereRaw('goods_details.student_seq = students.id')
                        ->where('goods_details.assignment_date','=', date('Y-m-d'))
                        ->where('goods_details.is_use','=','Y');
                });
        }

        // 삼담 예정 학생 카운트
        if (in_array('counsel_yet', $cnt_types)) {
            /* select * from counsels where start_date = today is_counsel != 'Y' */
            $counsel_yet_cnts = (clone $total_cnts)
                ->selectRaw('count(counsels.id) as counsel_yet_cnt')
                ->leftJoin('counsels', function ($query) {
                    $query->on('counsels.team_code', '=', 'teams.team_code')
                        ->where('counsels.start_date', '=', date('Y-m-d'))
                        ->where('counsels.is_counsel', '!=', 'Y');
                });
        }

        // 결제대기 카운트
        if (in_array('payment_yet', $cnt_types)) {
            //status_no = 9 , payment_due_date = today
            $payment_yet_cnts = (clone $total_cnts)
                ->selectRaw('count(payments2.id) as payment_yet_cnt')
                ->leftJoin('payments as payments2', function ($query) {
                    // 'payments.team_code', '=', 'teams.team_code'
                    $query->on('payments2.team_code', '=', 'teams.team_code')
                        ->where('payments2.status_no', 9)
                        ->where('payments2.payment_due_date', '>=', date('Y-m-d'))
                        ->where('payments2.payment_due_date', '<=', date('Y-m-d', strtotime('+1 day')));
                });
        }

        // 결제완료 카운트
        if (in_array('payment', $cnt_types)) {
            //select add DB::raw('count(payments.id) as payment_cnt')
            $payment_cnts = (clone $total_cnts)
                ->selectRaw('count(payments.id) as payment_cnt')
                ->leftJoin('payments', function ($query) {
                    // 'payments.team_code', '=', 'teams.team_code'
                    $query->on('payments.team_code', '=', 'teams.team_code')
                        ->where('payments.status_no', 1)
                        ->where('payments.payment_date', '=', date('Y-m-d'));
                });
        }

        /* $result['sql'] = $total_cnts->toSql(); */
        /* $result['bindings'] = $total_cnts->getBindings(); */
        /* $total_cnts_get = $total_cnts->get(); */

        // 결과
        // $result = array();
        $result['resultCode'] = 'success';
        /* $result['total_cnts'] = $total_cnts_get; */
        if($new_cnts) $result['new_cnts'] = $new_cnts->get();
        if($counsel_yet_cnts) $result['counsel_yet_cnts'] = $counsel_yet_cnts->get();
        if($payment_yet_cnts) $result['payment_yet_cnts'] = $payment_yet_cnts->get();
        if($payment_cnts) $result['payment_cnts'] = $payment_cnts->get();


        return response()->json($result);
    }
}
