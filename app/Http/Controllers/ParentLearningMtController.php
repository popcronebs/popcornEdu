<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\LearningMTController;

class ParentLearningMtController extends Controller
{
    // 학부모 > 학습 관리
    // NOTE: 더이상 사용하지 않음. 학생 페이지와 합쳐짐으로.
    // public function list(){
    //     $student_seq = session()->get('student_seq');
    //     $student = \App\Student::find($student_seq);
    //     $main_code = $student->main_code;
    //     // :과목
    //     $subject_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'subject')->where('code_step', '=', 1)
    //         ->orderBy('code_idx', 'asc')
    //         ->get();
    //     return view('parent.parent_learning_management', [
    //         'subject_codes' => $subject_codes,
    //         'student' => $student
    //     ]);
    // }

    // 오늘의 학습 불러오기
    public function studyPlannerSelect(Request $request)
    {
        $student_seq = session()->get('student_seq');

        // $request 안에 student_seqs 넣어주기.
        $request->merge([
            'student_seqs' => $student_seq
        ]);

        // LearningMTController 의 studyPlannerSelect사용
        $learningMTController = new LearningMTController();
        return $learningMTController->studyPlannerSelect($request);
    }

    // 학습 시작 시간 가져오기.
    public function studyTimeSelect(Request $request)
    {
        $student_seq = session()->get('student_seq');
        $request->merge([
            'student_seqs' => $student_seq
        ]);
        $search_start_date = $request->input('search_start_date');
        $search_end_date = $request->input('search_end_date');

        $learningMTController = new LearningMTController();
        $result = $learningMTController->studyTimeSelect($request);

        $attend = \App\Attend::where('student_seq', $student_seq)
        ->whereBetween('attend_date', [$search_start_date, $search_end_date])
        ->first();
        $data = $result->getData();
        $data->attend = $attend;
        $result->setData($data);
        return $result;
    }

    // :학생 접속중 여부 확인.
    public function studentConnectCheck(Request $request)
    {
        $student_seq = session()->get('student_seq');
        $sessionAuth = \App\Sessions::where('user_id', $student_seq)->first();
        $result['resultCode'] = 'success';
    
        if ($sessionAuth) {
            $sessionFilePath = storage_path('framework/sessions/' . $sessionAuth->session_id);
            if (file_exists($sessionFilePath)) {
                try {
                    $sessionData = unserialize(file_get_contents($sessionFilePath));
                    $last_activity = $sessionData['last_activity'] ?? null;
                    session()->put('last_activity', $last_activity);
                } catch (\Exception $e) {
                    // 직렬화 오류 처리
                    $last_activity = null;
                }
            } else {
                // 파일이 없을 경우 처리
                $last_activity = null;
            }
    
            if ($last_activity) {
                $now = time();
                $diff = $now - $last_activity;
                // 5분(300초) 이상 경과했는지 확인
                if ($diff > 300) {
                    $result['is_connect'] = 'false';
                } else {
                    $result['is_connect'] = 'true';
                }
            } else {
                $result['is_connect'] = 'false';
            }
        } else {
            // 세션 인증 정보가 없을 경우 처리
            $result['is_connect'] = 'false';
        }
    
        return response()->json($result);

                // $parent_seq = session()->get('parent_seq');
        // $student_seq = session()->get('student_seq');

        // $student = \App\Student::find($student_seq);
        // $last_date = $student->last_login_date;

        // // last_date 가 만약에 5분 이상이 지났다면, 접속중이 아님.
        // $result['resultCode'] = 'success';

        // $now = date('Y-m-d H:i:s');
        // $diff = strtotime($now) - strtotime($last_date);
        // if($diff > 300) {
        //     $result['is_connect'] = 'false';
        // } else {
        //     $result['is_connect'] = 'true';
        // }
        // return response()->json($result);
    }

}
