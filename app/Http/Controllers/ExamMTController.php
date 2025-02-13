<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage; // 이 줄을 추가하세요

class ExamMTController extends Controller
{
    public function list(){
        $main_code = session()->get('main_code') ?? $_COOKIE['main_code'];

        // 학년
        $grade_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'grade')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();

        // 과목
        $subject_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'subject')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();

        // 한자 급수
        $subject_codes2 = \App\Code::where('main_code', $main_code)
            ->whereIn('code_pt',  function($query){
                $query->select('id')->from('codes')->where('code_category', 'subject')->where('function_code', 'subject_hanja');
            })
            ->where('code_step', '=', 2)
            ->orderBy('code_idx', 'asc')
            ->get();

        // 학기
        $semester_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'semester')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();

        // :평가분류
        $evaluation_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'evaluation')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();

        return view('admin.admin_exam', [
            'grade_codes' => $grade_codes,
            'subject_codes' => $subject_codes,
            'subject_codes2' => $subject_codes2,
            'semester_codes' => $semester_codes,
            'evaluation_codes' => $evaluation_codes
        ]);
    }

    public function examInsert(Request $request){
        $main_code = session()->get('main_code') ?? $_COOKIE['main_code'];
        $teach_seq = session()->get('teach_seq');

        $exam_seq = $request->input('exam_seq');
        $subject_seq = $request->input('subject_seq');
        $subject_seq2 = $request->input('subject_seq2');
        $grade_seq = $request->input('grade_seq');
        $semester_seq = $request->input('semester_seq');
        $evaluation_seq = $request->input('evaluation_seq');
        $exam_title =  $request->input('title');

        $exam = \App\Exam::updateOrCreate(
            [
                'id' => $exam_seq
            ],
            [
                'main_code' => $main_code,
                'exam_title' => $exam_title,
                'exam_status' => 'Y',
                'subject_seq' => $subject_seq,
                'subject_seq2' => $subject_seq2,
                'grade_seq' => $grade_seq,
                'semester_seq' => $semester_seq,
                'evaluation_seq' => $evaluation_seq,
                'created_id' => $teach_seq
            ]
        );
        if (!$exam->wasRecentlyCreated) {
            // 업데이트일 때만 updated_at 필드를 설정
            $exam->updated_at = now();
            $exam->updated_id = $teach_seq;
            $exam->save();
        }


        // 결과.
        if($exam->id > 0){
            return response()->json([
                'resultCode' => 'success',
                'exam_id' => $exam->id
            ]);
        }else{
            return response()->json([
                'resultCode' => 'fail'
            ]);
        }
    }

    public function examBatchUpload(Request $request)
    {
        try {
            $main_code = session()->get('main_code') ?? $_COOKIE['main_code'];
            $teach_seq = session()->get('teach_seq');

            if (!$request->hasFile('excel_file')) {
                return response()->json([
                    'resultCode' => 'fail',
                    'message' => '업로드할 파일을 선택해주세요.'
                ]);
            }

            $file = $request->file('excel_file');

            // 엑셀 파일 읽기
            $excel = array_map('str_getcsv', file($file->getRealPath()));
            $rows = array_slice($excel, 1); // 첫 번째 행은 헤더이므로 제외

            // 코드 매핑 데이터 준비
            $subjectMap = [
                '국어' => 87,
                '영어' => 139,
                '수학' => 88,
                '과학' => 89,
                '사회' => 90,
                '한자' => 268
            ];

            $gradeMap = [
                '1학년' => 92,
                '2학년' => 93,
                '3학년' => 94,
                '4학년' => 95,
                '5학년' => 96,
                '6학년' => 97,
                '공통' => 239
            ];

            $semesterMap = [
                '1학기' => 98,
                '2학기' => 99,
                '여름방학' => 100,
                '겨울방학' => 101,
                '공통' => 240
            ];

            $evaluationMap = [
                '단원평가' => 41,
                '한자급수시험' => 42,
                '기본문제' => 269
            ];

            DB::beginTransaction();

            foreach ($rows as $row) {
                // 엑셀 데이터 매핑
                $exam = \App\Exam::create([
                    'main_code' => $main_code,
                    'exam_title' => $row[0], // 시험 제목
                    'subject_seq' => $subjectMap[$row[1]] ?? null, // 과목 코드
                    'grade_seq' => $gradeMap[$row[2]] ?? null,   // 학년 코드
                    'semester_seq' => $semesterMap[$row[3]] ?? null, // 학기 코드
                    'evaluation_seq' => $evaluationMap[$row[4]] ?? null, // 평가분류 코드
                    'exam_status' => 'Y',
                    'created_id' => $teach_seq
                ]);
            }

            DB::commit();

            return response()->json([
                'resultCode' => 'success',
                'message' => '엑셀 파일 업로드가 완료되었습니다.'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'resultCode' => 'fail',
                'message' => '엑셀 파일 업로드 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    // 문제 리스트 조회
    public function examSelect(Request $request){

        $main_code = session()->get('main_code') ?? $_COOKIE['main_code'];

        $subject_seq = $request->input('subject_code');
        $grade_seq = $request->input('grade_code');
        $semester_seq = $request->input('semester_code');
        $evaluation_seq = $request->input('evaluation_code');
        $exam_title =  $request->input('title');
        $is_not_page = $request->input('is_not_page');

        // 페이징 쿼리
        $page = $request->input('page');
        $page = $page ?? 1;
        $page_max = $request->input('page_max');
        $page_max = $page_max ?? 15;

        $exams = \App\Exam::
            select(
                'exams.*',
                'subject.code_name as subject_name',
                'subject2.code_name as subject_name2',
                'subject.id as subject_seq',
                'grade.code_name as grade_name',
                'grade.id as grade_seq',
                'semester.code_name as semester_name',
                'semester.id as semester_seq',
                'evaluation.code_name as evaluation_name',
                'ct.teach_name as created_name',
                'ut.teach_name as updated_name'
            )
            ->leftJoin('codes as subject', 'subject.id', '=', 'exams.subject_seq')
            ->leftJoin('codes as subject2', 'subject2.id', '=', 'exams.subject_seq2')
            ->leftJoin('codes as grade', 'grade.id', '=', 'exams.grade_seq')
            ->leftJoin('codes as semester', 'semester.id', '=', 'exams.semester_seq')
            ->leftJoin('codes as evaluation', 'evaluation.id', '=', 'exams.evaluation_seq')
            ->leftJoin('teachers as ct', 'ct.id', '=', 'exams.created_id')
            ->leftJoin('teachers as ut', 'ut.id', '=', 'exams.updated_id')
            ->where('exams.main_code', $main_code)
            ->orderByRaw('CAST(SUBSTRING_INDEX(exam_title, "강", 1) AS UNSIGNED), exam_title');

        // 과목이 있을 경우
        if(!empty($subject_seq)){
            $exams->where('subject_seq', $subject_seq);
        }
        // 학년이 있을 경우
        if(!empty($grade_seq)){
            $exams->where('grade_seq', $grade_seq);
        }
        // 학기가 있을 경우
        if(!empty($semester_seq)){
            $exams->where('semester_seq', $semester_seq);
        }
        // 제목이 있을 경우
        if(!empty($exam_title)){
            $exam_title = str_replace('%', '\%', $exam_title);
            $exam_title = str_replace(' ', '%', $exam_title);
            $exams->where('exam_title', 'like', '%'.$exam_title.'%');
        }
        // 평가분류가 있을 경우
        if(!empty($evaluation_seq)){
            $exams->where('evaluation_seq', $evaluation_seq);
        }

        if(empty($is_not_page)){
            // 페이징
            $exams = $exams->paginate($page_max, ['*'], 'page', $page);
        }else{
            $exams = $exams->get();
        }

        // 결과
        $result['resultCode'] = 'success';
        $result['exams'] = $exams;
        return response()->json($result);
    }

    // 문제 상세 등록
    public function examDetailInsert(Request $request){
        $exam_seq = $request->input('exam_seq');
        $exam_detail_seq = $request->input('exam_detail_seq');
        $exam_num = $request->input('exam_num');
        $exam_type = $request->input('exam_type');
        $answer_type = $request->input('answer_type');
        $answer = $request->input('answer');

        $teach_seq = session()->get('teach_seq');

        //
        $exam_detail = \App\ExamDetail::updateOrCreate(
            [
                'id' => $exam_detail_seq
            ],
            [
                'exam_seq' => $exam_seq,
                'exam_num' => $exam_num,
                'exam_type' => $exam_type,
                'answer_type' => $answer_type,
                'answer' => $answer,
                'created_id' => $teach_seq
            ]
        );

        // :업데이트시에만 변환 필드
        if (!$exam_detail->wasRecentlyCreated) {
            // 업데이트일 때만 updated_at 필드를 설정
            $exam_detail->updated_at = now();
            $exam_detail->updated_id = $teach_seq;
            $exam_detail->save();
        }
        // 결과
        $result['resultCode'] = 'success';
        $result['exam_detail_id'] = $exam_detail->id;
        return response()->json($result);
    }

    // 문제 상세 조회
    public function examDetailSelect(Request $request){
        $exam_seq = $request->input('exam_seq');
        $exam_type = $request->input('exam_type');

        $exam_details = \App\ExamDetail::
            select(
                'exam_details.*',
                'content_area.code_name as content_area_name',
                'cognitive_area.code_name as cognitive_area_name'
            )
            ->leftJoin('codes as content_area', 'content_area.id', '=', 'exam_details.content_area_seq')
            ->leftJoin('codes as cognitive_area', 'cognitive_area.id', '=', 'exam_details.cognitive_area_seq')
            ->where('exam_seq', $exam_seq)
            ->where('exam_type', $exam_type)
            ->get();

        // 결과
        $result['resultCode'] = 'success';
        $result['exam_details'] = $exam_details;

        return response()->json($result);

    }

    // 문제 상세 컨텐트(문제, 보기, 이미지) 저장
    public function examDetailContentInsert(Request $request){
        $main_code = session()->get('main_code') ?? $_COOKIE['main_code'];
        $teach_seq = session()->get('teach_seq');
        $exam_seq = $request->input('exam_seq');
        $exam_detail_seq = $request->input('exam_detail_seq');

        $ui_type = $request->input('ui_type');
        $questions = $request->input('questions');
        $questions2 = $request->input('questions2');
        $samples = $request->input('samples');
        $commentary = $request->input('commentary');
        $question_img_list = $request->file('question_img_list');
        $question_img = $request->file('question_img');
        $sample_img1 = $request->file('sample_img1');
        $sample_img2 = $request->file('sample_img2');
        $sample_img3 = $request->file('sample_img3');
        $sample_img4 = $request->file('sample_img4');
        $sample_img5 = $request->file('sample_img5');
        $commentary_video = $request->file('commentary_video');
        $commentary_img = $request->file('commentary_img');
        $content_area_seq = $request->input('content_area_seq');
        $cognitive_area_seq = $request->input('cognitive_area_seq');

        $is_transaction_suc = true;
        DB::beginTransaction();
        try {

            $exam_detail = \App\ExamDetail::find($exam_detail_seq);
            // 안전장치
            $exam_detail = $exam_detail->where('exam_seq', $exam_seq)->where('id', $exam_detail_seq)->first();

            $exam_detail->questions = $questions;
            $exam_detail->questions2 = $questions2;
            $exam_detail->samples = $samples;
            $exam_detail->commentary = $commentary;
            $exam_detail->updated_id = $teach_seq;
            $exam_detail->ui_type = $ui_type;

            $exam_detail->content_area_seq = $content_area_seq;
            $exam_detail->cognitive_area_seq = $cognitive_area_seq;
            $exam_detail->save();

            //$this->saveImgFile($question_img, 'question', $exam_seq, $exam_detail_seq, $main_code);
            $max_images = 10;
            $question_img_list = $request->file('question_img_list');

            if ($question_img_list && count($question_img_list) > 0) {
                // 삭제
                $result['save_result'] = $this->saveImgFile('delete', 'question', $exam_seq, $exam_detail_seq, $main_code, true);
                $remain_cnt = $max_images - count($question_img_list);
                $last_cnt = 0;
                foreach ($question_img_list as $index => $imgArray) {
                    if ($index >= $max_images) {
                        break;
                    }
                    if (isset($imgArray['file']) && $imgArray['file']->isValid()) {
                        $file = $imgArray['file'];
                        $seq = $request->input("question_img_list.{$index}.seq"); // seq 값 가져오기

                        // seq 값을 사용하여 파일 저장
                        $this->saveImgFile($file, 'question_img_list_'.$seq, $exam_seq, $exam_detail_seq, $main_code);
                        $last_cnt = $seq;
                    }
                }
                // if($remain_cnt > 0){
                //     for($i = ($last_cnt+1); $i < $max_images; $i++){
                //         $this->saveImgFile('delete', 'question_img_list_'.$i, $exam_seq, $exam_detail_seq, $main_code, true);
                //     }
                // }
            }else if($question_img){

                $this->saveImgFile($question_img, 'question', $exam_seq, $exam_detail_seq, $main_code);
                for($i = 1; $i < 10; $i++){
                    $this->saveImgFile('delete', 'question_img_list_'.$i, $exam_seq, $exam_detail_seq, $main_code, true);
                }
            }

            $this->saveImgFile($sample_img1, 'sample1', $exam_seq, $exam_detail_seq, $main_code);
            $this->saveImgFile($sample_img2, 'sample2', $exam_seq, $exam_detail_seq, $main_code);
            $this->saveImgFile($sample_img3, 'sample3', $exam_seq, $exam_detail_seq, $main_code);
            $this->saveImgFile($sample_img4, 'sample4', $exam_seq, $exam_detail_seq, $main_code);
            $this->saveImgFile($sample_img5, 'sample5', $exam_seq, $exam_detail_seq, $main_code);
            $this->saveImgFile($commentary_video, 'commentary', $exam_seq, $exam_detail_seq, $main_code);
            $this->saveImgFile($commentary_img, 'commentary_img', $exam_seq, $exam_detail_seq, $main_code);
            DB::commit();
        } catch (\Exception $e) {
            $is_transaction_suc = false;
            DB::rollback();
            throw $e;
        }

        // 결과
        if($is_transaction_suc)
            $result['resultCode'] = 'success';
        else
            $result['resultCode'] = 'fail';

        return response()->json($result);
    }

    // :파일 업로드
    private function saveImgFile($img_file, $file_type, $exam_seq, $exam_detail_seq, $main_code, $is_delete = false){

        if($img_file == null ) return 'null';
        $originalName = '';
        if(!$is_delete){
            $originalName = $img_file->getClientOriginalName();
            $fileName = pathinfo($originalName, PATHINFO_FILENAME);
            $extension = $img_file->getClientOriginalExtension();
            $originalName = $fileName . '_' . time() . '.' . $extension;
            $originalName = str_replace('.php', '', $originalName);
            $originalName = str_replace(' ', '_', $originalName);
            $img_file->storeAs('public/uploads/exam_files', $originalName);
        }

        // Lecture_uploadfile에 lecture_seq가 있으면 UPDATE 없으면 INSERT
        $exam_uploadfile = \App\ExamUploadfile::where('exam_detail_seq', $exam_detail_seq)->where('file_type', $file_type);
        // if($file_type == 'question' || $file_type == 'commentary'){
        //     $exam_uploadfile = $exam_uploadfile->orWhere(function($query) use($exam_seq, $file_type) {
        //         $query->where('file_type', $file_type)->where('exam_seq', $exam_seq);
        //     });
        // }

        $exam_uploadfile = $exam_uploadfile->first();
        if($exam_uploadfile == null){ $exam_uploadfile = new \App\ExamUploadfile; }
        else{
            //기존 파일 삭제
            $file_path = $exam_uploadfile->file_path;
            File::delete(storage_path('app/public/'.$file_path));
        }

        $exam_uploadfile->exam_seq = $exam_seq;
        $exam_uploadfile->exam_detail_seq = $exam_detail_seq;
        $exam_uploadfile->main_code = $main_code;
        $exam_uploadfile->file_path = "/storage/uploads/exam_files/".$originalName;
        $exam_uploadfile->file_type = $file_type;
        if($is_delete){
            $exam_uploadfile->delete();
            return 'del';
        }else{
            $exam_uploadfile->save();
            return 'save';
        }
    }

    // 문제 상세 컨텐트(문제, 보기, 이미지) 조회
    public function examDetailContentSelect(Request $request){
        $exam_seq = $request->input('exam_seq');
        $exam_detail_seq = $request->input('exam_detail_seq');
        $subject_seq = $request->input('subject_seq');
        $subject_seq = $subject_seq == 268 ? 'subject_hanja' : ($subject_seq ?? 0);
        $exam_detail = \App\ExamDetail::
            select(
                'exam_details.*',
                'qimg.file_path as question_file_path',
                'simg1.file_path as sample_file_path1',
                'simg2.file_path as sample_file_path2',
                'simg3.file_path as sample_file_path3',
                'simg4.file_path as sample_file_path4',
                'simg5.file_path as sample_file_path5',
                'cvideo.file_path as commentary_file_path',
                'cimg.file_path as commentary_img',
                'qimg_list.file_path as question_img_list'
            )
            ->leftJoin('exam_uploadfiles as qimg', function($join){
                $join->on('qimg.exam_detail_seq', '=', 'exam_details.id')
                    ->where('qimg.file_type', '=', 'question');
            })
            ->leftJoin('exam_uploadfiles as simg1', function($join){
                $join->on('simg1.exam_detail_seq', '=', 'exam_details.id')
                    ->where('simg1.file_type', '=', 'sample1');
            })
            ->leftJoin('exam_uploadfiles as simg2', function($join){
                $join->on('simg2.exam_detail_seq', '=', 'exam_details.id')
                    ->where('simg2.file_type', '=', 'sample2');
            })
            ->leftJoin('exam_uploadfiles as simg3', function($join){
                $join->on('simg3.exam_detail_seq', '=', 'exam_details.id')
                    ->where('simg3.file_type', '=', 'sample3');
            })
            ->leftJoin('exam_uploadfiles as simg4', function($join){
                $join->on('simg4.exam_detail_seq', '=', 'exam_details.id')
                    ->where('simg4.file_type', '=', 'sample4');
            })
            ->leftJoin('exam_uploadfiles as simg5', function($join){
                $join->on('simg5.exam_detail_seq', '=', 'exam_details.id')
                    ->where('simg5.file_type', '=', 'sample5');
            })
            ->leftJoin('exam_uploadfiles as cvideo', function($join){
                $join->on('cvideo.exam_detail_seq', '=', 'exam_details.id')
                    ->where('cvideo.file_type', '=', 'commentary');
            })
            ->leftJoin('exam_uploadfiles as cimg', function($join){
                $join->on('cimg.exam_detail_seq', '=', 'exam_details.id')
                    ->where('cimg.file_type', '=', 'commentary_img');
            })
            ->leftJoin('exam_uploadfiles as qimg_list', function($join){
                $join->on('qimg_list.exam_detail_seq', '=', 'exam_details.id')
                    ->where('qimg_list.file_type', 'like', '%question_img_list%');
            })
            ->where('exam_details.id', $exam_detail_seq)
            ->where('exam_details.exam_seq', $exam_seq)
            ->first();

        // question_img_list 값 추출
        $question_img_list = \App\ExamUploadfile::where('exam_detail_seq', $exam_detail_seq)
            ->where('file_type', 'like', 'question_img_list%')
            ->get(['id', 'file_path']);

        // :내용영역
        $content_areas_all = \App\Code::where('code_category', 'content_area')->get();
        $content_areas_pt_seq = $content_areas_all->where('code_step', 1)->where('function_code', $subject_seq)->first()->id;
        $content_areas = $content_areas_all->where('code_pt', $content_areas_pt_seq)->values();

        // :인지영역
        $cognitive_areas_all = \App\Code::where('code_category', 'cognitive_area')->get();
        $content_areas_pt_seq = $cognitive_areas_all->where('code_step', 1)->where('function_code', $subject_seq)->first()->id;
        $cognitive_areas = $cognitive_areas_all->where('code_pt', $content_areas_pt_seq)->values();

        // 결과
        $result['resultCode'] = 'success';
        $result['exam_detail'] = $exam_detail;
        $result['content_areas'] = $content_areas;
        $result['cognitive_areas'] = $cognitive_areas;
        $result['exam_detail']['question_img_list'] = $question_img_list;
        return response()->json($result);
    }

    // 문제 상세 이미지 삭제
    public function examDetailImgDelete(Request $request){
        $exam_uploadfile_seq = $request->input('exam_uploadfile_seq');
        $exam_uploadfile = \App\ExamUploadfile::find($exam_uploadfile_seq);
        $exam_uploadfile->delete();
        return response()->json(['resultCode' => 'success']);
    }

    // TODO: 삭제시에는 teach_seq 의 권한을 확인하는 것도 좋을듯.
    // 문제 삭제
    public function examDelete(Request $request){
        $teach_seq = session()->get('teach_seq');
        $exam_seq = $request->input('exam_seq');

        $exam = \App\Exam::find($exam_seq);
        $exam->delete();

        // 결과
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    public function videoMultipleUpload(Request $request)
    {
        try {
            $request->validate([
                'video' => 'required|max:92160', // 최대 90MB
            ]);
            $file = $request->file('video');
            $fileName = $file->getClientOriginalName();
            $fileName = str_replace('.php', '', $fileName);
            $fileName = str_replace(' ', '_', $fileName);
            // 첫 업로드 시에만 개별 폴더 생성
            if ($request->input('isFirstUpload')) {
                $folderName = pathinfo($fileName, PATHINFO_FILENAME);
                $folderPath = 'uploads/exam_files/' . $folderName;
            } else {
                $folderPath = 'uploads/exam_files/'. $request->lecture_detail_seq; // 공통 폴더
            }
            if($request->input('last_time') == "Y"){
                $test = '마지막파일 업로드완료테스트';
                \App\LectureUploadfile::updateOrInsert(
                    ['lecture_detail_seq' => $request->lecture_detail_seq],
                    [
                        'main_code' => 'elementary',
                        'file_path' => '/storage/'.$folderPath.'/'.$fileName,
                        'file_type' => 'detail_file:mp4',
                    ]
                );
                \App\LectureDetail::where('id', $request->lecture_detail_seq)->update([
                    'lecture_detail_link' => '/storage/'.$folderPath.'/'.$fileName,
                ]);
            }

            if (!Storage::disk('public')->exists($folderPath)) {
                Storage::disk('public')->makeDirectory($folderPath);
            }
            $path = $file->storeAs($folderPath, $fileName, 'public');
            return response()->json(['path' => $path, 'message' => 'Video uploaded successfully', 'folderPath' => $test ?? $folderPath]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => '유효성 검사 실패: ' . $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => '파일 업로드 중 오류 발생: ' . $e->getMessage()], 500);
        }
    }

    // 문제 상세 삭제
    public function examDetailDelete(Request $request){
        $teach_seq = session()->get('teach_seq');
        $exam_seq = $request->input('exam_seq');
        $exam_detail_seq = $request->input('exam_detail_seq');

        $exam_detail = \App\ExamDetail::where('id',$exam_detail_seq)->where('exam_seq', $exam_seq)->first();

        $exam_type = $exam_detail->exam_type;

        $exam_detail->delete();

        // 순서를 재정렬.(exam_num) $exam_type 이 같은 것만 정렬해야한다. 문제 유형이 다르기 때문에.
        $exam_details = \App\ExamDetail::where('exam_seq', $exam_seq)->orderBy('exam_num', 'asc')->where('exam_type', $exam_type)->get();
        $exam_num = 1;
        foreach($exam_details as $exam_detail){
            $exam_detail->exam_num = $exam_num;
            $exam_detail->save();
            $exam_num++;
        }
        // 결과
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    // 문제 답입력
    public function studentExamResultInsert(Request $request){
        $student_seq = session()->get('student_seq');
        $teach_seq = session()->get('teach_seq');
        $student_exam_seq = $request->input('student_exam_seq');
        $exam_seq = $request->input('exam_seq');
        $exam_num = $request->input('exam_num');
        $lecture_detail_seq = $request->input('lecture_detail_seq');
        $student_lecture_detail_seq = $request->input('student_lecture_detail_seq');
        $exam_type = $request->input('exam_type');
        $student_answer = $request->input('student_answer')??' ';
        $is_last = $request->input('is_last');
        $is_wrong = $request->input('is_wrong'); // 오답노트에서 넘어온 것.

        $exam_detail = \App\ExamDetail::select('*')
            ->where('exam_seq', $exam_seq)
            ->where('exam_num', $exam_num)
            ->where('exam_type', ($exam_type == 'easy' ? 'normal' : $exam_type))
            ->first();

        $answer = $exam_detail->answer;

        // lecture_detail_seq 로 lecture_detail_type 을 불러온다.
        $lecture_detail_type = \App\LectureDetail::select('lecture_detail_type')
            ->where('id', $lecture_detail_seq)->value('lecture_detail_type');


        if($lecture_detail_type == '') $lecture_detail_type = 'unitpage';
        //$student_exam_seq 가 안넘어 왔다면,
        //student_exams 를 만들어주고 넣는다.
        if(strlen($student_exam_seq) < 1){
            $fistData = [];
            //student_lecture_detail_seq 가 있으면
            if($student_lecture_detail_seq){
                $fistData = [
                    'student_seq' => $student_seq,
                    'teach_seq' => $teach_seq,
                    'exam_seq' => $exam_seq,
                    'lecture_detail_seq' => $lecture_detail_seq,
                    'student_lecture_detail_seq' => $student_lecture_detail_seq,
                    'lecture_detail_type' => $lecture_detail_type,
                ];
            }else{
                $fistData = [
                    'student_seq' => $student_seq,
                    'teach_seq' => $teach_seq,
                    'exam_seq' => $exam_seq,
                    'lecture_detail_seq' => $lecture_detail_seq,
                    'student_lecture_detail_seq' => $student_lecture_detail_seq,
                    'lecture_detail_type' => $lecture_detail_type,
                    'exam_status' => 'study',
                ];
            }
            $student_exam = \App\StudentExam::firstOrCreate(
                $fistData
            );
            $student_exam_seq = $student_exam->id;
            // exam_status 가 null이면 'study' 로 넣어준다.'
            if($student_exam->exam_status == null){
                $student_exam->exam_status = 'study';
                $student_exam->save();
            }
        }

        $updateData = [];
        // 오답노트에서 넘오온 경우 오답 쪽 컬럼으로 처리한다.
        if(strlen($is_wrong) > 0 && $is_wrong == 'Y'){
            $updateData = [
                'wrong_note_answer' => $student_answer,
                'wrong_status' => 'ready',
                'answer' => $answer,
            ];
        }else{
            // 오답노트가 아닌 경우.

            // 다음 추가.
            $updateData = [
                'exam_status' => 'ready',
                'student_answer' => $student_answer,
                'answer' => $answer,
            ];

        }
        $updateData['student_exam_seq'] = $student_exam_seq;

        //exam_status = ready 는 처음엔 준비상태.
        $search_data = [
            'student_seq' => $student_seq,
            'teach_seq' => $teach_seq,
            'exam_seq' => $exam_seq,
            'exam_num' => $exam_num,
            'exam_type' => $exam_type
        ];
        if($student_exam_seq){
            $search_data['student_exam_seq'] = $student_exam_seq;
        }else{
            $search_data['lecture_detail_seq'] = $lecture_detail_seq;
            $search_data['student_lecture_detail_seq'] = $student_lecture_detail_seq;
        }
        $student_exam_result = \App\StudentExamResult::updateOrCreate(
            $search_data,
            $updateData
        );

        //is_last = true 이면 답을 과 학생답을 비교해서 채점.
        if($is_last == 'Y'){
            //easy 때문에 앞전에 넣은 것을 불러와서 모두 채점해준다.
            //easy 는 한번에 채점.

            // 오답노트에서 넘오온 경우 오답 쪽 컬럼으로 처리한다.
            if(strlen($is_wrong) > 0 && $is_wrong == 'Y'){
                \App\StudentExamResult::where('student_seq', $student_seq)
                    ->where('teach_seq', $teach_seq)
                    ->where('exam_seq', $exam_seq)
                    ->where('wrong_status', 'ready')
                    ->whereColumn('wrong_note_answer', '!=', 'answer')
                    ->where('exam_num', '<=', $exam_num)
                    ->where('exam_type', $exam_type)
                    ->update([
                        'wrong_status' => 'wrong'
                    ]);

                // 같으면 맞음.
                \App\StudentExamResult::where('student_seq', $student_seq)
                    ->where('teach_seq', $teach_seq)
                    ->where('exam_seq', $exam_seq)
                    ->where('wrong_status', 'ready')
                    ->whereColumn('wrong_note_answer', 'answer')
                    ->where('exam_num', '<=', $exam_num)
                    ->where('exam_type', $exam_type)
                    ->update([
                        'wrong_status' => 'correct'
                    ]);
            }else{
                // 다르면 틀림.
                \App\StudentExamResult::where('student_seq', $student_seq)
                    ->where('teach_seq', $teach_seq)
                    ->where('exam_seq', $exam_seq)
                    ->where('exam_status', 'ready')
                    ->whereColumn('student_answer', '!=', 'answer')
                    ->where('exam_num', '<=', $exam_num)
                    ->where('exam_type', $exam_type)
                    ->update([
                        'exam_status' => 'wrong'
                    ]);

                // 같으면 맞음.
                \App\StudentExamResult::where('student_seq', $student_seq)
                    ->where('teach_seq', $teach_seq)
                    ->where('exam_seq', $exam_seq)
                    ->where('exam_status', 'ready')
                    ->whereColumn('student_answer', 'answer')
                    ->where('exam_num', '<=', $exam_num)
                    ->where('exam_type', $exam_type)
                    ->update([
                        'exam_status' => 'correct'
                    ]);
            }
        }

        // 결과
        if($student_exam_result->id > 0){
            $result['resultCode'] = 'success';
        }else
            $result['resultCode'] = 'fail';
        $result['student_exam_seq'] = $student_exam_seq;

        return response()->json($result);
    }

    //문제 목록 리스트 가져오기
    public function questionListSelect(Request $request){
        $student_seq = session()->get('student_seq');
        $exam_seq = $request->input('exam_seq');
        $lecture_detail_seq = $request->input('lecture_detail_seq');
        $student_lecture_detail_seq = $request->input('student_lecture_detail_seq');

        $student_exam_results = \App\StudentExamResult::select('*')
            ->where('student_seq', $student_seq)
            ->where('exam_seq', $exam_seq)
            ->where('lecture_detail_seq', $lecture_detail_seq)
            ->where('student_lecture_detail_seq', $student_lecture_detail_seq)
            ->orderBy('exam_type')
            ->orderBy('exam_num')
            ->get();

        $easys = $student_exam_results->where('exam_type', 'easy')->groupBy('exam_num');
        $normals = $student_exam_results->where('exam_type', 'normal')->groupBy('exam_num');
        $similars = $student_exam_results->where('exam_type', 'similar')->groupBy('exam_num');
        $challenges = $student_exam_results->where('exam_type', 'challenge')->groupBy('exam_num');
        $challenge_similars = $student_exam_results->where('exam_type', 'challenge_similar')->groupBy('exam_num');

        // 결과
        $result['resultCode'] = 'success';
        // $result['student_exam_results'] = $student_exam_results;
        $result['easys'] = $easys;
        $result['normals'] = $normals;
        $result['similars'] = $similars;
        $result['challenges'] = $challenges;
        $result['challenge_similars'] = $challenge_similars;
        return response()->json($result);
    }

    // 시험이 완료되면, 완료로 처리
    public function examComplete(Request $request){
        $st_lecture_detail_seq = $request->input('st_lecture_detail_seq');
        $lecture_detail_type = $request->input('lecture_detail_type');
        $student_exam_seq = $request->input('student_exam_seq');

        $lecture_detail_group = \App\StudentLectureDetail::where('id',$st_lecture_detail_seq)->value('lecture_detail_seq');
        $lecture_detail_seq = \App\LectureDetail::where('lecture_detail_group', $lecture_detail_group)
            ->where('lecture_detail_type', $lecture_detail_type)
            ->value('id');

        // 완료 날짜, 완료 상태 변경.
        if($student_exam_seq){
            $student_exam = \App\StudentExam::where('id', $student_exam_seq)->first();
        }else{
            $student_exam = \App\StudentExam::where('lecture_detail_seq', $lecture_detail_seq)->where('student_lecture_detail_seq', $st_lecture_detail_seq)->first();
        }
        $result['sql'] = $student_exam->toSql();
        $student_exam->exam_status = 'complete';
        $student_exam->is_complete = 'Y';
        if($student_exam->complete_datetime === null){
            $student_exam->complete_datetime = date('Y-m-d H:i:s');
        }
        $student_exam->save();
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    // 시험 미리보기 내용 가져오기.
    function examPreview(Request $request){

        $exam_seq = $request->input('exam_seq');
        // 오답노트의 정보를 가져온다.
        $exam_details = \App\ExamDetail::
            select(
                'exams.exam_title',
                'exam_details.*',
                'qimg.file_path as question_file_path',
                'simg1.file_path as sample_file_path1',
                'simg2.file_path as sample_file_path2',
                'simg3.file_path as sample_file_path3',
                'simg4.file_path as sample_file_path4',
                'simg5.file_path as sample_file_path5',
                'cvideo.file_path as commentary_file_path',
                'cimg.file_path as commentary_img',
                'qimg_list.file_path as question_img_list'

            )
            ->leftJoin('exams', 'exam_details.exam_seq', '=', 'exams.id')
            ->leftJoin('exam_uploadfiles as qimg', function($join) {
                $join->on('qimg.exam_detail_seq', '=', 'exam_details.id')
                    ->where('qimg.file_type', '=', 'question');
            })
            ->leftJoin('exam_uploadfiles as simg1', function($join) {
                $join->on('simg1.exam_detail_seq', '=', 'exam_details.id')
                    ->where('simg1.file_type', '=', 'sample1');
            })
            ->leftJoin('exam_uploadfiles as simg2', function($join) {
                $join->on('simg2.exam_detail_seq', '=', 'exam_details.id')
                    ->where('simg2.file_type', '=', 'sample2');
            })
            ->leftJoin('exam_uploadfiles as simg3', function($join) {
                $join->on('simg3.exam_detail_seq', '=', 'exam_details.id')
                    ->where('simg3.file_type', '=', 'sample3');
            })
            ->leftJoin('exam_uploadfiles as simg4', function($join) {
                $join->on('simg4.exam_detail_seq', '=', 'exam_details.id')
                    ->where('simg4.file_type', '=', 'sample4');
            })
            ->leftJoin('exam_uploadfiles as simg5', function($join) {
                $join->on('simg5.exam_detail_seq', '=', 'exam_details.id')
                    ->where('simg5.file_type', '=', 'sample5');
            })
            ->leftJoin('exam_uploadfiles as cvideo', function($join) {
                $join->on('cvideo.exam_detail_seq', '=', 'exam_details.id')
                    ->where('cvideo.file_type', '=', 'commentary');
            })
            ->leftJoin('exam_uploadfiles as cimg', function($join) {
                $join->on('cimg.exam_detail_seq', '=', 'exam_details.id')
                    ->where('cimg.file_type', '=', 'commentary_img');
            })
            ->leftJoin('exam_uploadfiles as qimg_list', function($join) {
                $join->on('qimg_list.exam_detail_seq', '=', 'exam_details.id')
                    ->where('qimg_list.file_type', 'like', '%question_img_list%');
            })
            ->where('exam_details.exam_seq', $exam_seq)
            ->orderBy('exam_details.exam_num')
            ->get();

        $sel_exams = (clone $exam_details)->groupBy('exam_type');

        // 결과
        $result['resultCode'] = 'success';
        $result['exam_details'] = $exam_details;
        $result['sel_exams'] = $sel_exams;

        return response()->json($result);
    }
}
