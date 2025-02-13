<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CodeMTController extends Controller
{
    //
    public function code(){
        $main_code = $_COOKIE['main_code'];
        $codes_all = \App\Code::where('main_code', $main_code)->get();
        return view('admin.admin_code', ['codes_all'=>$codes_all]);
    }
    
    // 코드 조회
    public function codeSelect(Request $request){
        $main_code = $_COOKIE['main_code'];
        $code_step = $request->input('code_step');
        $code_category = $request->input('code_category'); 
        $code_pt = $request->input('code_pt');
        $search_str = $request->input('search_str');

        // selct left join
        $codes = \App\Code::select('codes.*', DB::raw('group_concat(c.code_name) as code_names'), DB::raw('max_step_tb.max_step'));
        $codes = $codes->leftJoin('codes as c', function($join) use ($code_step, $main_code){
            $join->on('codes.id', '=', 'c.code_pt');
            $join->on('c.code_step', '=', DB::raw($code_step+1));
            $join->on('c.main_code', '=', DB::raw("'".$main_code."'"));
        });
        $codes = $codes->groupBy('codes.id');
        $codes = $codes->groupBy('max_step_tb.max_step');

        // 조건 
        // code_step
        if(strlen($code_step) > 0){
            $codes = $codes->where('codes.code_step', $code_step);
        } 
        if($code_step.'' == '0'){ 
            $main_code = ''; 
            // 검색어 where code_name, having code_names
            if(strlen($search_str) > 0){
                $codes = $codes->where(function($query) use ($search_str){
                    $query->where('codes.code_name', 'like', '%'.$search_str.'%')
                    ->orWhere('c.code_name', 'like', '%'.$search_str.'%');
                });
            }
        }
        if($main_code != ''){ $codes = $codes->where('codes.main_code', $main_code); }
        if($code_category != ''){ $codes = $codes->where('codes.code_category', $code_category); }
        if($code_pt != ''){ $codes = $codes->where('codes.code_pt', $code_pt); }

        $codes = $codes->orderBy('codes.code_idx', 'asc');
        $codes = $codes->orderBy('codes.code_name', 'asc');

        $code_functions = null;
        //step 0 이 아니면 Code_function의 태이블 조회
        if($code_step != '0'){
            // where code_step, code_category
            $code_functions = \App\CodeFunction::where('code_step', $code_step)
            ->where('code_category', $code_category)
            ->get();
        }
        //codes group by code_category 묶어서 max(code_step)를 가져온다.
        $main_code = $_COOKIE['main_code'];
        $max_step_tb = \App\Code::
            select('codes.code_category', DB::raw('max(codes.code_step) as max_step'))
            ->where('codes.main_code', $main_code)
            ->groupBy('codes.code_category');

        //$max_step_tb를 $codes에 code_category를 기준으로 묶어서 max_step을 넣어준다.
        $codes = $codes->leftJoinSub($max_step_tb, 'max_step_tb', function($join){
            $join->on('codes.code_category', '=', 'max_step_tb.code_category');
        });

        
        DB::enableQueryLog();
        $result = array();
        $result['resultCode'] = 'success';
        $result['codes'] = $codes->get();
        $result['code_functions'] = $code_functions;
        $result['sql'] = last(DB::getQueryLog());
        return response()->json($result, 200); 
    }

    // 코드 등록
    public function codeInsert(Request $request){
        $main_code = $_COOKIE['main_code'];
        $code_category = $request->input('code_category');
        $step = $request->input('step');
        $code_data  = $request->input('code_data');

        for($i=0; $i<count($code_data); $i++){
            $code = \App\Code::updateOrCreate(
                ['id' => $code_data[$i]['code_seq']], // 검색 조건
                [
                    'code_name' => $code_data[$i]['code_name'],
                    'code_idx' => $code_data[$i]['code_idx'],
                    'group_seq' => $code_data[$i]['group_seq'],
                    'code_step' => $step,
                    'code_pt' => $code_data[$i]['code_pt'],
                    'code_category' => $code_category,
                    'open_size' => $code_data[$i]['open_size'],
                    'is_use' => $code_data[$i]['is_use'],
                    'main_code' => $main_code,
                    'group_seq' => $code_data[$i]['group_seq'],
                    'function_code' => $code_data[$i]['function_code']
                ]
            );
        }
        //기획변경으로 인해 max를 main_code로 분할해서 가져와야 해서 컴럼 삭제.
        // 'code_category', $code_category 의 max(code_step)을 가져와서 max_step에 넣어준다.
        // $max_step = \App\Code::where('code_category', $code_category)->max('code_step');
        // $max_step = $max_step == null ? 0 : $max_step;
        // \App\Code::where('code_category', $code_category)
        // ->where('code_step', '0')
        // ->update(['max_step' => $max_step]);

        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result, 200);
    }

    // 코드 삭제
    public function codeDelete(Request $request){
        $code_seqs = $request->input('code_seqs');
        $code_seqs = explode(',', $code_seqs);

        $codes = \App\Code::whereIn('id', $code_seqs)->get();
        $code_category = $codes[0]->code_category;

        for($i=0; $i<count($codes); $i++){
            $this->codeDeleteRecursive($codes[$i]->id);
        }
    
        // 위에 내용.
        // 'code_category', $code_category 의 max(code_step)을 가져와서 max_step에 넣어준다.
        // $max_step = \App\Code::where('code_category', $code_category)->max('code_step');
        // $max_step = $max_step == null ? 0 : $max_step;
        // \App\Code::where('code_category', $code_category)
        // ->where('code_step', '0')
        // ->update(['max_step' => $max_step]);

        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result, 200);
    }

    // 삭제 재귀 함수
    public function codeDeleteRecursive($code_seq){
        $code = \App\Code::where('code_pt', $code_seq)->get();
        for($i=0; $i<count($code); $i++){
            $this->codeDeleteRecursive($code[$i]->id);
        }
        \App\Code::where('id', $code_seq)->delete();
    }

    // 코드 다대다 연결 
    public function codeConnectInsert(Request $request){
        $main_code = $_COOKIE['main_code'];
        $code_seq = $request->input('code_seq');
        $code_pts = $request->input('code_pts');

        // 배열로 변경.
        $code_pts = explode(',', $code_pts);

        //code_seq 로 들어온 코드 삭제
        $code_connects = \App\CodeConnect::where('code_seq', $code_seq)->delete();
        
        //code_pts 로 들어온 코드 추가
        for($i=0; $i<count($code_pts); $i++){
            $code_connect = new \App\CodeConnect;
            $code_connect->main_code = $main_code;
            $code_connect->code_seq = $code_seq;
            $code_connect->code_pt = $code_pts[$i];
            $code_connect->save();
        }

        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result, 200);
    }

    // 코드 다대다 연결 조회
    public function codeConnectSelect(Request $request){
        $main_code = $_COOKIE['main_code'];
        $code_seq = $request->input('code_seq');

        $code_connects = \App\CodeConnect::where('code_seq', $code_seq)->get();

        $result = array();
        $result['resultCode'] = 'success';
        $result['code_connects'] = $code_connects;
        return response()->json($result, 200);
    }
}
