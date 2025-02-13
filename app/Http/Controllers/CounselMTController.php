<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\TeachCounselMtController as TCselMt;
use App\Http\Controllers\LogMTController as LogMT;
use PDO;
class CounselMTController extends Controller
{
    // 상담 상세 화면.
    public function detail(Request $request)
    {
        $student_seq = $request->input('student_seq');
        $counsel_seq = $request->input('counsel_seq');
        $is_before = $request->input('is_before');

        // 이전 상담중 Y 3가져오기.
        if($is_before == 'Y'){
            $counsel_seq = $this->getBeforeCounselSeq($student_seq, $counsel_seq);
        }
        $counsel = $this->getCounselInfo($counsel_seq);

        $rtn_data = $this->getInfoInit($request);
        $student = $rtn_data['student'];
        $remain_date_cnt = $rtn_data['remain_date_cnt'];
        $counsel_gp = $rtn_data['counsel_gp'];
        $counsel_detail = $rtn_data['counsel_detail'];
        $leader_info = $rtn_data['leader_info'];
        $counsel_next = $rtn_data['counsel_next'];


        return view('admin.admin_counsel_detail', [
            'student_seq' => $student_seq,
            'counsel_seq' => $counsel_seq,
            'student' => $student,
            'remain_date_cnt' => $remain_date_cnt,
            'counsel_gp' => $counsel_gp,
            'counsel' => $counsel,
            'counsel_detail' => $counsel_detail,
            'leader_info' => $leader_info,
            'counsel_next' => $counsel_next
        ]);
    }

    // 상담 추가 화면.
    public function add(Request $request){
        $student_seq = $request->input('student_seq');
        $counsel_seq = $request->input('counsel_seq');

        $counsel = $this->getCounselInfo($counsel_seq);
        $rtn_data = $this->getInfoInit($request);
        $student = $rtn_data['student'];
        $remain_date_cnt = $rtn_data['remain_date_cnt'];
        $counsel_gp = $rtn_data['counsel_gp'];
        $leader_info = $rtn_data['leader_info'];
        $counsel_detail = $rtn_data['counsel_detail'];
        $counsel_next = $rtn_data['counsel_next'];

        return view('admin.admin_counsel_add', [
            'student_seq' => $student_seq,
            'counsel_seq' => $counsel_seq,
            'student' => $student,
            'remain_date_cnt' => $remain_date_cnt,
            'counsel_gp' => $counsel_gp,
            'counsel' => $counsel,
            'counsel_detail' => $counsel_detail,
            'leader_info' => $leader_info,
            'counsel_next' => $counsel_next
        ]);
    }
    
    // 이용권 상담 상세화면
    public function goodsDetail(Request $request){
        $student_seq = $request->input('student_seq');
        $counsel_seq = $request->input('counsel_seq');

        //request add is_goods
        $request = $request->merge(['is_goods' => 'Y']);

        $counsel = $this->getCounselInfo($counsel_seq);
        $rtn_data = $this->getInfoInit($request);
        $student = $rtn_data['student'];
        $counsel_detail = $rtn_data['counsel_detail'];
        $counsels_y = $rtn_data['counsels_y'];

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
        
        return view('admin.admin_counsel_goods_detail', [
            'student_seq' => $student_seq,
            'counsel_seq' => $counsel_seq,
            'student' => $student,
            'counsel' => $counsel,
            'group_type2' => $group_type2,
            'regions' => $regions,
            'team' => $team,
            'counsel_detail' => $counsel_detail,
            'counsels_y' => $counsels_y
        ]);
    }
    
    // 초기 정보 불러오기.
    private function getInfoInit(Request $request){
        $student_seq = $request->input('student_seq');
        $counsel_seq = $request->input('counsel_seq');
        $is_goods = $request->input('is_goods');

        $student = \App\Student::select(
            'students.*',
            'parents.parent_id',
            'parents.parent_name',
            'user_groups.group_name as group_name',
            'grade_codes.code_name as grade_name',
            'gd.start_date as goods_start_date',
            'gd.end_date as goods_end_date',
            'gd.goods_name',
            'gd.goods_period',
            'counsel_teach.teach_name as counsel_teach_name',
            'counsel_group.group_name as counsel_group_name'
        )
        ->leftJoin('parents', 'parents.id', '=', 'students.parent_seq')
        ->leftJoin('goods_details as gd', 'gd.id', '=', 'students.goods_detail_seq')
        ->leftJoin('codes as grade_codes', 'grade_codes.id', '=', 'students.grade')
        ->leftJoin('user_groups', 'user_groups.id', '=', 'students.group_seq')
        ->leftJoin('teachers as counsel_teach', 'counsel_teach.id', '=', 'students.counsel_teach_seq')
        ->leftJoin('user_groups as counsel_group', 'counsel_group.id', '=', 'counsel_teach.group_seq')
        ->where('students.id', $student_seq)->first();

    //형제 학생 정보
    $student->brother = \App\Student::select(DB::raw('group_concat(student_name) as brother'))
        ->where('parent_seq', $student->parent_seq)
        ->where('parent_seq', '<>', '')
        ->where('id', '<>', $student->student_seq)
        ->first();
    $student->brother = $student->brother->brother;
    $team_code = $student->team_code;

    // 팀 리터 정보
    $leader_info = \App\Teacher::select(
        'teachers.id',
        'teachers.teach_name',
        'teams.team_name',
        'regions.region_name'
    )
    ->leftJoin('teams', 'teachers.team_code', '=', 'teams.team_code')
    ->leftJoin('regions', 'teams.region_seq', '=', 'regions.id')
    ->whereIn('group_seq', function($query){
        $query->select('id')->from('user_groups')->where('group_type2', 'leader');
    })
    ->where('teachers.team_code', $team_code)
    ->first();

    // 이용권 남은 기간 일수
    $remain_date_cnt = \Carbon\Carbon::parse($student->goods_end_date)->diffInDays(now());

    // 6개월 이내 상담 
    $counsel_types = ['regular', 'no_regular'];
    $start_date = now()->subMonths(6)->format('Y-m-d');
    $end_date = now()->format('Y-m-d');
    $counsel_gp = \App\Counsel::select(
            DB::raw('sum(if(counsel_type = "regular", 1, 0)) as regular_cnt'),
            DB::raw('sum(if(counsel_type = "no_regular", 1, 0)) as no_regular_cnt')
        )
        ->where('student_seq', $student_seq)
        ->whereBetween('start_date', [$start_date, $end_date])
        ->whereIn('counsel_type', $counsel_types)
        ->where('is_counsel', 'Y')
        ->groupBy('counsel_type')
        ->first();
    
    // 삼당일지(상세) 정보
    $counsel_detail = \App\CounselDetail::where('counsel_seq', $counsel_seq)->first();

    // 상담 정기 상담(예정) 정보 
    $counsel_next = \App\Counsel::where('pt_seq', $counsel_seq)->first();
    // 만약 다음 상담이 수기일경우 그다음 상담을 가져온다.
    if($counsel_next && $counsel_next->counsel_type != 'regular'){
        $counsel_next = \App\Counsel::where('pt_seq', $counsel_next->id)->first();
    }

    $counsels_y = '';
    //접수기록 LOG
    if($is_goods){
        $counsels_y = \App\Counsel::
            select(
                'counsels.created_at', 
                'counsels.is_counsel',
                'counsels.is_transfer',
                'counsel_transfers.transfer_reg_date',
                'counsel_transfers.id as transfer_seq',
                'counsel_transfers.transfer_reason',
                'counsel_transfers.is_move',
                'counsel_transfers.before_teach_seq',
                'counsel_transfers.after_teach_seq',
                'counsel_transfers.updated_at as transfer_updated_at',
                'before_teacher.teach_name as before_teach_name',
                'after_teacher.teach_name as after_teach_name',
                'before_group.group_name as before_group_name',
                'after_group.group_name as after_group_name',
                'before_team.team_name as before_team_name',
                'after_team.team_name as after_team_name',
                'before_region.region_name as before_region_name',
                'after_region.region_name as after_region_name',
                'teachers.teach_name as counsel_teach_name',
                'user_groups.group_name as group_name'
            )
            ->leftJoin('counsel_transfers', 'counsel_transfers.counsel_seq', '=', 'counsels.id')
            ->leftJoin('teachers', 'teachers.id', '=', 'counsels.teach_seq')
            ->leftJoin('user_groups', 'user_groups.id', '=', 'teachers.group_seq')
            ->leftJoin('teachers as before_teacher', 'before_teacher.id', '=', 'counsel_transfers.before_teach_seq')
            ->leftJoin('teachers as after_teacher', 'after_teacher.id', '=', 'counsel_transfers.after_teach_seq')
            ->leftJoin('user_groups as before_group', 'before_group.id', '=', 'before_teacher.group_seq')
            ->leftJoin('user_groups as after_group', 'after_group.id', '=', 'after_teacher.group_seq')
            ->leftJoin('teams as before_team', 'before_team.team_code', '=', 'before_teacher.team_code')
            ->leftJoin('regions as before_region', 'before_region.id', '=', 'before_team.region_seq')
            ->leftJoin('teams as after_team', 'after_team.team_code', '=', 'after_teacher.team_code')
            ->leftJoin('regions as after_region', 'after_region.id', '=', 'after_team.region_seq')

            ->where('student_seq', $student_seq)
            ->where('counsel_type', 'no_regular')
            ->where('counsel_category', 'goods')
            ->orderBy('counsels.created_at', 'desc')
            ->get();
    }

        return [
            'student' => $student,
            'remain_date_cnt' => $remain_date_cnt,
            'counsel_gp' => $counsel_gp,
            'leader_info' => $leader_info,
            'counsel_detail' => $counsel_detail,
            'counsel_next' => $counsel_next,
            'counsels_y' => $counsels_y
        ];
    }

    // 이전 상담일지를 불러와야 할때 seq 전달
    public function getBeforeCounselSeq($student_seq, $counsel_seq){
        $counsel = \App\Counsel::find($counsel_seq);
        // 현재 들어온 상담보다 작지만 가장큰 상담일자 날짜의 id를 가져온다.
        $max_counsel = \App\Counsel::select(DB::raw('max(concat(start_date,\'|\',start_time, \'|\', end_time)) as start_end_datetime'))
            ->where('student_seq', $student_seq)
            ->where('start_date', '<', $counsel->start_date)
            ->where('is_counsel', 'Y')
            ->first();

        if($max_counsel->start_end_datetime == ''){
            return '';
        }
        $start_end_datetime = explode('|', $max_counsel->start_end_datetime);
        $start_date = $start_end_datetime[0];
        $start_time = $start_end_datetime[1];
        $end_time = $start_end_datetime[2];

        // select max(id) from counsels where student_key = $student_seq and start_date < $start_date and is_counsel = 'Y' 
        $before_counsel_seq = \App\Counsel::select(DB::raw('max(id) as id'))
            ->where('student_seq', $student_seq)
            ->where('start_date', $start_date)
            ->where('start_time', $start_time)
            ->where('end_time', $end_time)
            ->where('is_counsel', 'Y')
            ->first();
        $before_counsel_seq = $before_counsel_seq->id;    
        return $before_counsel_seq;
    }

    // 상담일지 정보 가져오기.
    public function getCounselInfo($counsel_seq){
        $counsel = \App\Counsel::find($counsel_seq);
        return $counsel;
    }


    // 상담일지 불러오기.
    public function detailSelect(Request $request){
        $search_type = $request->input('search_type'); 
        $serach_str = $request->input('serach_str');
        $student_seq  = $request->input('student_seq');


    }

    //상담일지(상세) 저장 / 변경
    public function detailInsert(Request $request){
        $tcounsel_mt = new TCselMt();
        $teach_seq = session()->get('teach_seq');

        // 변경사항 저장시.
        $is_update = $request->input('is_update');

        $student_seq = $request->input('student_seq');
        $counsel_seq = $request->input('counsel_seq');
        $counsel_next_seq = $request->input('counsel_next_seq');
        $content = $request->input('content');
        $target_type = $request->input('target_type');
        $counsel_special = $request->input('counsel_special');
        $counsel_type = $request->input('counsel_type');
        $start_datetime = $request->input('start_datetime');
        $end_datetime = $request->input('end_datetime');
        $parent_complaint = $request->input('parent_complaint');
        $pt_send_content = $request->input('parent_send');
        $report_target_seq = $request->input('report_target_seq');
        $is_temp = $request->input('is_temp');
        $is_fail = $request->input('is_fail');

        $regular_start_date = $request->input('regular_start_date');
        $regular_start_time = $request->input('regular_start_time');
        $regular_end_time = $request->input('regular_end_time');

        $is_chage_regular_date = $request->input('is_chage_regular_date');
        $change_regular_date = $request->input('change_regular_date');
        $change_regular_start_time = $request->input('change_regular_start_time');
        $change_regular_end_time = $request->input('change_regular_end_time');
        $change_regular_reason = $request->input('change_regular_reason');
        $change_regular_target = $request->input('change_regular_target');

        $counsel_how = $request->input('counsel_how');
        $student_type = $request->input('student_type');

        //로그 데이터 생성
        $log = new LogMT();
        $req = new Request();
        $req->merge([
            'teach_seq' => 0,
            'student_seq' => 0,
            'parent_seq' => 0,
            'log_title' => '',
            'log_remark' => '',
            'log_subject' => '',
            'log_content' => '',
            'log_type' => '',
            'write_type' => 'teacher'
        ]);

        // 트랜젝션 시작
        $is_commit = false;
        DB::beginTransaction();
        try {
            $log_title = "update_counsel";
            $other1_seq = "";
            $log_content = "";
            // $log_content = "학생 상세페이지 수정\n";
            // $req->merge([
            //     'student_seq' => $student_seq,
            //     'log_title' => 'user_detail_update',
            //     'log_content' => $log_content,
            //     'log_remark' => '',
            //     'log_type' => 'user_update',
            //     'other1_seq' => ''
            // ]);
            // $log->insert($req);
            //counsel_seq가 없으면 부모격 새로 생성.
            if(strlen($counsel_seq) < 1 && $student_type){
                $counsel_insert_req = new Request();
                $counsel_insert_req->merge([
                    'student_seqs' => [$student_seq],
                    'student_types' => [$student_type],
                    'sel_date' => substr($start_datetime, 0, 10),
                    'start_time' => substr($start_datetime, 11, 5),
                    'end_time' => substr($end_datetime, 11, 5),
                    'counsel_type' => 'no_regular',
                    'counsel_category' => 'goods'
                ]);
                $rt_data = $tcounsel_mt->insert($counsel_insert_req); 
                //return response()->json($result);
                //제이슨형태일때 받으려면?
                // $json_data = json_decode($rt_data);
                $counsel_seq = $rt_data->getData()->counsel_seq;
            }

            $counsel_detail = \App\CounselDetail::where('counsel_seq', $counsel_seq)->first();
            if($counsel_detail == null){
                $counsel_detail = new \App\CounselDetail;
                $counsel_detail->counsel_seq = $counsel_seq;
            }

            $counsel_detail->content = $content;
            $counsel_detail->target_type = $target_type;
            $counsel_detail->counsel_special = $counsel_special;
            $counsel_detail->counsel_type = $counsel_type;
            if($start_datetime) $counsel_detail->start_datetime = $start_datetime;
            if($end_datetime) $counsel_detail->end_datetime = $end_datetime;
            $counsel_detail->parent_complaint = $parent_complaint;
            $counsel_detail->pt_send_content = $pt_send_content;
            $counsel_detail->report_target_seq = $report_target_seq;
            $counsel_detail->is_temp = $is_temp;
            $counsel_detail->is_fail = $is_fail;

            if($regular_start_date) $counsel_detail->regular_start_date = $regular_start_date;
            if($regular_start_time) $counsel_detail->regular_start_time = $regular_start_time;
            if($regular_end_time) $counsel_detail->regular_end_time = $regular_end_time;

            $counsel_detail->is_chage_regular_date = $is_chage_regular_date;
            $is_chage_same = false;
            //수정하려는 값이 같을때 같다고 표시.
            if($counsel_detail->change_regular_date == $change_regular_date
            && $counsel_detail->change_regular_start_time == $change_regular_start_time
            && $counsel_detail->change_regular_end_time == $change_regular_end_time){
                $is_chage_same = true;
            }
            $counsel_detail->change_regular_date = $change_regular_date;
            $counsel_detail->change_regular_start_time = $change_regular_start_time;
            $counsel_detail->change_regular_end_time = $change_regular_end_time;
            $counsel_detail->change_regular_reason = $change_regular_reason;
            $counsel_detail->change_regular_target = $change_regular_target;

            if($counsel_how) $counsel_detail->counsel_how = $counsel_how;

            $counsel_detail->save();

            // 임시가 아닐때.
            // 업데이트로 들어왔을때.
            if($is_temp != 'Y' || $is_update == 'Y'){
                $pt_counsel = \App\Counsel::find($counsel_seq);
                $counsel_detail->is_temp = 'N';
                //정기 상담(예정) 일시 있을때.
                if(strlen($regular_start_date) > 0){
                    // 다음 상담이 있을때. 모두 삭제 처리.
                    if(strlen($counsel_next_seq) > 0){
                        // $counsel_next_seq 를 찾아서 삭제.
                        // $this->nextCounselDelete($counsel_next_seq);
                        // $counsel_next_seq = '';
                        $this->nextCcounselUpdate($counsel_next_seq, $regular_start_date, $regular_start_time, $regular_end_time);
                        
                    }
                    // 다음 정기 수업이 없을 경우에는 새로 생성.
                    if(strlen($counsel_next_seq) < 1 ){
                    // if($pt_counsel->counsel_type == 'no_regular'){
                        $counsel = new \App\Counsel;
                        $counsel->student_seq = $student_seq;
                        $counsel->counsel_type = 'regular';
                        // 상담일정 변경은 수시 이면서 정시 상담(예정)일시를 변경할때만 변경한다.
                        // 있으면 수기로 변경해준다.
                        if($is_chage_regular_date == 'Y' && !$is_chage_same){
                            $counsel->counsel_type = 'no_regular';
                            $counsel->start_date = $change_regular_date;
                            $counsel->start_time = $change_regular_start_time;
                            $counsel->end_time = $change_regular_end_time;
                            $counsel->is_change = 'Y';
                            // $counsel->start_time = substr($change_regular_date, 11, 5);
                            // $between_min = (strtotime($pt_counsel->fixed_end_time) - strtotime($pt_counsel->fixed_start_time)) / 60;
                            // $end_time = date('H:i', strtotime(substr($change_regular_date, 11, 5).' + '.$between_min.' minutes'));
                            // $counsel->end_time = $end_time;
                        }else{
                            $counsel->start_date = $regular_start_date;
                            $counsel->start_time = $regular_start_time;
                            $counsel->end_time = $regular_end_time;
                        }
                        $counsel->fixed_start_date = $regular_start_date;
                        $counsel->fixed_start_time = $regular_start_time;
                        $counsel->fixed_end_time = $regular_end_time;
                        $counsel->teach_seq = $teach_seq;
                        $counsel->pt_seq = $counsel_seq;
                        $counsel->is_repeat = 'Y';
                        // $counsel->pt_seq = $counsel_seq; // 일단은 주석처리.
                        $counsel->save();
                        $pt_seq1 = $tcounsel_mt->againInsert($counsel->id, 'regular');
                        $pt_seq2 = $tcounsel_mt->againInsert($pt_seq1, 'regular');
                        $tcounsel_mt->againInsert($pt_seq2, 'regular');
                    }
                }

                // 정규이면서 다음상담변경 체크 되어있을때.
                if($is_chage_regular_date == 'Y' && $pt_counsel->counsel_type == 'regular' && !$is_chage_same){
                    // 바로 다음 상담을 변경한다.
                    $next_counsel = \App\Counsel::where('pt_seq', $counsel_seq)->first();
                    if($next_counsel == null){
                        $next_counsel = new \App\Counsel;
                        $next_counsel->student_seq = $pt_counsel->student_seq;
                        
                        $next_counsel->fixed_start_date = date('Y-m-d', strtotime($pt_counsel->fixed_start_date.' + 7 days'));
                        $next_counsel->fixed_start_time = $pt_counsel->fixed_start_time;
                        $next_counsel->fixed_end_time = $pt_counsel->fixed_end_time;
                        $next_counsel->teach_seq = $pt_counsel->teach_seq;
                        $next_counsel->is_repeat = 'Y';
                        $next_counsel->pt_seq = $pt_counsel->id;
                    }
                    $next_counsel->counsel_type = 'no_regular';
                    $next_counsel->start_date = $change_regular_date;
                    $next_counsel->start_time = $change_regular_start_time;
                    $next_counsel->end_time = $change_regular_end_time;
                    $next_counsel->is_change = 'Y';
                    // $next_counsel->start_date = substr($change_regular_date, 0, 10);
                    // $next_counsel->start_time = substr($change_regular_date, 11, 5);
                    // $between_min = (strtotime($pt_counsel->fixed_end_time) - strtotime($pt_counsel->fixed_start_time)) / 60;
                    // $end_time = date('H:i', strtotime(substr($change_regular_date, 11, 5).' + '.$between_min.' minutes'));
                    // $next_counsel->end_time = $end_time;
                    $next_counsel->save();

                    $pt_counsel->is_use_repeat = 'Y';
                    $pt_counsel->save();
                }
                $pt_counsel->is_counsel = 'Y';
                $pt_counsel->is_counsel_date = date('Y-m-d H:i:s');
                $pt_counsel->save();
                $counsel_detail->save();

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
            $is_commit = true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        // $result = array();
        $result['is_chage_same'] = $is_chage_same;
        if($is_commit){
            $result['resultCode'] = 'success';
        }else{
            $result['resultCode'] = 'fail';
        }
        return response()->json($result);
    }

    // 상담 삭제
    public function delete(Request $request){
        $student_seq = $request->input('student_seq');
        $counsel_seq = $request->input('counsel_seq');

        $counsel = \App\Counsel::find($counsel_seq);
        $counsel->delete();
        //detail은 자동으로 삭제됨.

        //결과
        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    // counsel_seq 를 받으면 자식 row를 모두 삭제.
    private function nextCounselDelete($counsel_seq){
        $counsel = \App\Counsel::find($counsel_seq);
        $counsel->delete();
        $next_counsel = \App\Counsel::where('pt_seq', $counsel_seq)->first();
        if($next_counsel != null){
            $this->nextCounselDelete($next_counsel->id);
        }
    }
    
    // counsel_seq 를 받으면 자식 row를 모두 업데이트
    private function nextCcounselUpdate($counsel_seq, $start_date, $start_time, $end_time){
        $counsel = \App\Counsel::find($counsel_seq);
        // 완료하지 않았을때.
        if($counsel->is_counsel != 'Y'){
            $counsel->start_date = $start_date;
            $counsel->start_time = $start_time;
            $counsel->end_time = $end_time;
            $counsel->fixed_start_date = $start_date;
            $counsel->fixed_start_time = $start_time;
            $counsel->fixed_end_time = $end_time;
            $counsel->save();
        }
        $next_counsel = \App\Counsel::where('pt_seq', $counsel_seq)->first();
        if($next_counsel != null){
            $p_start_date = date('Y-m-d', strtotime($start_date.' + 7 days'));
            $this->nextCcounselUpdate($next_counsel->id, $p_start_date, $start_time, $end_time);
        }
    }

    // 관리메모 저장.
    public function manageMemoUpdate(Request $request){
        $student_seq = $request->input('student_seq');
        $manage_memo = $request->input('manage_memo');

        $student = \App\Student::find($student_seq);
        $student->student_manage_memo = $manage_memo;
        $student->save();

        //결과
        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result);
    }
}
