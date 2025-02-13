<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogMTController extends Controller
{
    //Request 예시
    //log_seq = 0 // update시에만 사용
    //teach_seq = 0
    //student_seq = 0
    //parent_seq = 0
    //log_title = little type
    //log_remark = 'text'
    //log_content = 'text'
    //log_type = 'edit'
    //write_type = 'student', 'parent', 'teacher'
    public function insert(Request $request){
        //쓴사람 타입
        $log_seq = $request->input('log_seq');
        $write_type = $request->input('write_type');
        $main_code = $request->cookie('main_code');
        $created_id = "";
        $updated_id = "";
        $created_id_type = $write_type;
        $updated_id_type = $write_type;

        $region_seq= 0;
        $team_code = "";

        $teach_seq = $request->input('teach_seq') ?? 0;
        $student_seq = $request->input('student_seq') ?? 0;
        $parent_seq = $request->input('parent_seq') ?? 0;

        $log_title = $request->input('log_title');
        $log_remark = $request->input('log_remark');
        $log_subject = $request->input('log_subject');
        $log_content = $request->input('log_content');
        $log_type = $request->input('log_type');

        $other1_seq = $request->input('other1_seq') ?? 0;
        $other2_seq = $request->input('other2_seq') ?? 0;
        

        //운영(선생님)일 경우
        if($write_type == 'teacher'){
            $created_id = session()->get('teach_seq'); 
            $updated_id = session()->get('teach_seq');
        }else if($write_type == 'student'){
            $created_id = $student_seq;
            $updated_id = $student_seq;
        }else if($write_type == 'parent'){
            $created_id = $parent_seq;
            $updated_id = $parent_seq;
        }

        //로그는 update가 없을 수 있으나, 우선 코드는 삽입.
        if(strlen($log_seq) > 0){
            $logs = \App\Log::where('id', $log_seq)->first();
            $logs->updated_id = $updated_id;
            $logs->updated_id_type = $updated_id_type;
        }else{
            $logs = new \App\Log;
            $logs->created_id = $created_id;
            $logs->created_id_type = $created_id_type;
        }
        $logs->main_code = $main_code;
        $logs->region_seq = $region_seq;
        $logs->team_code = $team_code;
        $logs->teach_seq = $teach_seq;
        $logs->student_seq = $student_seq;
        $logs->parent_seq = $parent_seq;
        $logs->log_title = $log_title;
        $logs->log_remark = $log_remark;
        $logs->log_subject = $log_subject;
        $logs->log_content = $log_content;
        $logs->log_type = $log_type;
        $logs->other1_seq = $other1_seq;
        $logs->other2_seq = $other2_seq;
        $logs->save();


        $result = array('result' => 'success', 'seq' => $logs->id);
        return response()->json($result, 200);
    } 

    // 로그 select
    public function select(Request $request){
        $select_type = $request->input('select_type');
        $select_seq = $request->input('select_seq');
        $max_count = $request->input('max_count');;
        
        //select_type = 'student', 'parent', 'teacher' 에 따라 다르게 처리
        $logs = \App\Log::select('logs.*', 'st.student_name', 'pt.parent_name', 'tr.teach_name')
            ->from('logs')
            ->leftJoin('students as st', 'st.id', '=', 'logs.student_seq')
            ->leftJoin('parents as pt', 'pt.id', '=', 'logs.parent_seq')
            ->leftJoin('teachers as tr', 'tr.id', '=', 'logs.teach_seq');

        if($select_type == 'student'){
            $logs = $logs->where('logs.student_seq', $select_seq);
        }else if($select_type == 'parent'){
            $logs = $logs->where('logs.parent_seq', $select_seq);
        }else if($select_type == 'teacher'){
            $logs = $logs->where('logs.teach_seq', $select_seq);
        }

        if(strlen($max_count) > 0){
            $logs = $logs->limit($max_count);
        }

        $logs = $logs->orderBy('logs.created_at', 'desc')->get();

        $result = array();
        $result['resultCode'] = 'success';
        $result['logs'] = $logs;
        return response()->json($result, 200);
    }

    // 로그 (비고) 저장 업데이트.
    function remarkUpdate(Request $request){
        $log_seqs = $request->input('log_seqs'); 
        $log_remarks = $request->input('log_remarks');

        $log_seqs = explode(',', $log_seqs);
        $log_remarks = explode(',', $log_remarks);

        foreach($log_seqs as $index => $log_seq){
            $log = \App\Log::find($log_seq);
            $log->log_remark = $log_remarks[$index];
            $log->save();
        }

        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result, 200);
    }
}
