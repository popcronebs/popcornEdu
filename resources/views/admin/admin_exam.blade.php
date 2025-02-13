
@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title')
강좌 목록
@endsection

<!-- : 상단바 만들기 : 조회, 문제등록, 필터 등등. -->
<!-- : 문제 리스트 만들기.  -->
<!-- : 문제 삭제 -->
<!-- : 시험지 저장 > 활성화 된 tr 이 있는지 체크. -->
<!-- : 시험지 저장 > 보기 인지, 주관식인지 확인후 답이 없으면 체크 및 저장해달라고 토스트.  -->
<!-- : 시험에 들어갈 내용 영역과 인지영역에 대해서 (논의) 후 진행. -->
<!-- TODO: 혹시라도 유닛(단원 요청시에 추가진행.) -->
<!-- TODO: 시험지 상세에서 이미지 삭제 기능 추가. -->

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
<style>

</style>
{{--  문제 리스트 및 문제 생성 --}}
<div class="col-12 pe-3 ps-3 position-relative">
    <section data-main-section="1">
        <div class="row mx-0">
            <input class="form-control col-auto w-25 me-3" placeholder="조회할 제목을 입력해주세요." data-search-title onkeyup="if(event.keyCode == 13) examSelect();">
            <div class="row mx-0 col-auto gap-1 me-3">
                <select class="col form-select" data-search-subject-code>
                    <option value="">과목전체</option>
                    @if(!empty($subject_codes))
                    @foreach($subject_codes as $subject_code)
                    <option value="{{$subject_code->id}}">{{$subject_code->code_name}}</option>
                    @endforeach
                    @endif
                </select>
                <select class="col form-select" data-search-subject-code2 style="width:auto">
                    <option value="">한자급수전체</option>
                    @if(!empty($subject_codes2))
                    @foreach($subject_codes2 as $subject_code)
                    <option value="{{$subject_code->id}}">{{$subject_code->code_name}}</option>
                    @endforeach
                    @endif
                </select>
                <select class="col form-select" data-search-grade-code >
                    <option value="">학년전체</option>
                    @if(!empty($grade_codes))
                    @foreach($grade_codes as $grade_code)
                    <option value="{{$grade_code->id}}">{{$grade_code->code_name}}</option>
                    @endforeach
                    @endif
                </select>
                <select class="col form-select" data-search-semester-code >
                    <option value="">학기전체</option>
                    @if(!empty($semester_codes))
                    @foreach($semester_codes as $semester_code)
                    <option value="{{$semester_code->id}}">{{$semester_code->code_name}}</option>
                    @endforeach
                    @endif
                </select>
                {{--  평가분류 --}}
                <select  class="col form-select" data-search-evaluation-code style="width:auto;">
                    <option value="">평가분류</option>
                    @if(!empty($evaluation_codes))
                    @foreach($evaluation_codes as $evaluation_code)
                    <option value="{{$evaluation_code->id}}">{{$evaluation_code->code_name}}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <button class="btn btn-primary col-auto me-1" onclick="examSelect();">조회</button>
            <button class="btn btn-primary col-auto me-1" onclick="examAddModalShow('insert');">문제등록</button>
            <button class="btn btn-primary col-auto" onclick="examBatchAddModalShow();">일괄등록</button>
        </div>
        <div class="mt-4">
            <table class="table">
                <thead>
                    <tr>
                        <th>과목</th>
                        <th>학년</th>
                        <th>학기</th>
                        <td>평가</td>
                        <th>제목</th>
                        <th>등록날짜</th>
                        <th>수정날짜</th>
                        <th>작성자</th>
                        <th>수정자</th>
                        <th>기능</th>
                    </tr>
                </thead>
                <tbody data-bundle="exam_list">
                    <tr data-row="copy" hidden>
                        <input type="hidden" data-exam-seq>
                        <input type="hidden" data-subject-seq>
                        <input type="hidden" data-grade-seq>
                        <input type="hidden" data-semester-seq>
                        <input type="hidden" data-evaluation-seq>

                        <td data-subject-name></td>
                        <td data-grade-name></td>
                        <td data-semester-name></td>
                        <td data-evaluation-name></td>
                        <td data-exam-title></td>
                        <td data-created-at></td>
                        <td data-updated-at></td>
                        <td data-created-name></td>
                        <td data-updated-name></td>
                        <td>
                            <button class="btn btn-primary" onclick="examAddModalShow('update', this)">문제수정</button>
                            <button class="btn btn-primary" onclick="examMainSectionShow('detail', this);">문제상세</button>
                            <button class="btn btn-danger" onclick="examDelete(this);">문제삭제</button>
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>
        <div class="all-center mt-52">
            <div class=""></div>
            <div class="col-auto">
                {{-- 페이징 --}}
                <div class="col d-flex justify-content-center align-items-center">
                    <ul class="pagination col-auto gap-3 align-items-center" data-page="1" hidden>
                        <button href="javascript:void(0)" class="btn p-0 prev" data-page-prev="1"
                            onclick="userPaymentPageFunc('1', 'prev')">
                            <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                        </button>
                        <li class="page-item" hidden>
                            <a class="page-link" onclick="">0</a>
                        </li>
                        <span class="page" data-page-first="1" hidden onclick="userPaymentPageFunc('1', this.innerText);"
                            disabled>0</span>
                        <button href="javascript:void(0)" class="btn p-0 next" data-page-next="1"
                            onclick="userPaymentPageFunc('1', 'next')" data-is-next="0">
                            <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                        </button>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- 문제 상세 만들기 --}}
    <section data-main-section="2" hidden>
        <input type="hidden" data-main-exam-seq>
        <input type="hidden" data-main-subject-seq>
        <div class="row mx-0 align-items-center">
            <button class="btn col-auto px-0" onclick="examMainSectionShow('main')"><img src="{{asset('images/black_arrow_left_tail.svg')}}" width="32"></button>
            <span class="fs-4 col-auto"> 뒤로가기</span>
        </div>
        <div class="row mx-0 mt-3">
            {{--  문제 보기 --}}
            <div class="col-lg-7">
                <div class="rounded-3 bg-primary-subtle text-white fs-5 py-1 px-2" style="height:40px">
                    시험지 보기
                </div>
                <div class="row justify-content-between mx-0">
                    <div class="col">
                        <button class="btn btn-outline-primary active rounded-top-4">문제 상세 관리</button>
                        <button class="btn btn-outline-primary rounded-top-4">학생 화면 보기</button>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-success" onclick="examDetailContentInsert(this)">
                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true" hidden></span>
                            시험지 저장
                        </button>
                    </div>
                </div>
                <div class="overflow-auto border" style="height:70vh">
                    <table class="table">
                        <tbody data-bundle="exam_detail_manage" hidden>
                            <tr>
                                <th>내용영역</th>
                                <td>
                                    <select class="col form-select" id="" data-detail-content-area-seq="">

                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>인지영역</th>
                                <td>
                                    <select class="col form-select" id="" data-detail-cognitive-area-seq="">

                                    </select>
                                </td>
                            </tr>
                            <tr data-row="ui_type">
                                <th class="text-info">UI 타입</th>
                                <td>
                                    <select data-ui-type class="form-select">
                                        <option value="1">UI TYPE 1</option>
                                        <option value="2">UI TYPE 2</option>
                                        <option value="3">UI TYPE 3</option>
                                        <option value="4">UI TYPE 4</option>
                                    </select>
                                </td>
                            </tr>
                            <tr data-row="questions">
                                <th class="text-success">
                                   문제(글)
                                </th>
                                <td>
                                    <textarea class=" form-control" id="text_questions"></textarea>
                                </td>
                            </tr>
                            <tr data-row="questions2">
                                <th class="text-success">
                                   문제(글2)
                                </th>
                                <td>
                                    <textarea class=" form-control" id="text_questions2"></textarea>
                                    <div class="mt-2">문제(글2 프리뷰)</div>
                                    <div class="mt-2" id="text_questions2_preview"></div>
                                </td>
                            </tr>
                            <tr data-row="questions_img">
                                <th class="text-success">문제(이미지)</th>
                                <td>
                                    <div class="row mx-0 gap-2">
                                        <div class="col-12 mb-2 questions-img-wrap">
                                            <div class="row mx-0 gap-2 questions-img-wrap-box">
                                                <div class="input-group">
                                                    <span class="input-group-text">1</span>
                                                    <input type="file" class="form-control" id="inp_questions" data-questions-img-seq="1" aria-describedby="design_btn_findfile2" aria-label="Upload"
                                                        onchange="examAddImgSetting(this);"
                                                        accept="image/*"

                                                        >
                                                    <button class="btn btn-primary btn-sm" type="button" onclick="document.querySelector('#inp_questions').click();">찾아보기</button>
                                                    <button class="btn btn-danger btn-sm" type="button" onclick="examDeleteImg(this);" hidden>삭제</button>
                                                </div>
                                                <img src="" data-questions="img" style="max-width:300px">
                                            </div>

                                        </div>
                                        <div class="col-12">
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-success btn-sm" type="button" onclick="addQuestionImageInput(this);">이미지 업로드 추가</button>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>

                            <tr data-row="remark" >
                                <td class="text-primary">참고</td>
                                <td>
                                    <div class="row mx-0 justify-content-between">
                                        <span class="col-auto"> 보기가 필요 없으면 내용을 비워주세요. </span>
                                        <button class="col-auto btn btn-outline-success" onclick="examHideSampleToggle()">보기 숨기기</button>
                                    </div>
                                </td>
                            </tr>

                            <tr data-row="sample" data-sample="1">
                                <th class="text-warning">
                                   보기-1(글)
                                </th>
                                <td>
                                    <textarea class=" form-control" data-sample-str="1"></textarea>
                                </td>
                            </tr>
                            <tr data-row="sample_img" data-sample="1">
                                <th class="text-warning">보기-1(이미지)</th>
                                <td>
                                    <div class="row mx-0">
                                        <input type="file" class="col academy_number_file form-control ps-2" id="inp_sample1" aria-describedby="design_btn_findfile2" aria-label="Upload"
                                            onchange="examAddImgSetting(this);" accept="image/*" >
                                        <button class="col-auto btn btn-primary btn-sm" type="button" onclick="document.querySelector('#inp_sample1').click();">찾아보기</button>
                                    </div>
                                    <img src="" data-sample="img">
                                </td>
                            </tr>

                            <tr data-row="sample" data-sample="2">
                                <th class="text-warning">
                                   보기-2(글)
                                </th>
                                <td>
                                    <textarea class=" form-control" data-sample-str="2"></textarea>
                                </td>
                            </tr>
                            <tr data-row="sample_img" data-sample="2">
                                <th class="text-warning">보기-2(이미지)</th>
                                <td>
                                    <div class="row mx-0">
                                        <input type="file" class="col academy_number_file form-control ps-2" id="inp_sample2" aria-describedby="design_btn_findfile2" aria-label="Upload"
                                            onchange="examAddImgSetting(this);" accept="image/*" >
                                        <button class="col-auto btn btn-primary btn-sm" type="button" onclick="document.querySelector('#inp_sample2').click();">찾아보기</button>
                                    </div>
                                    <img src="" style="max-height: 400px;max-width:100%" data-sample="img">
                                </td>
                            </tr>
                            <tr data-row="sample" data-sample="3">
                                <th class="text-warning">
                                   보기-3(글)
                                </th>
                                <td>
                                    <textarea class=" form-control" data-sample-str="3"></textarea>
                                </td>
                            </tr>
                            <tr data-row="sample_img" data-sample="3">
                                <th class="text-warning">보기-3(이미지)</th>
                                <td>
                                    <div class="row mx-0">
                                        <input type="file" class="col academy_number_file form-control ps-2" id="inp_sample3" aria-describedby="design_btn_findfile2" aria-label="Upload"
                                            onchange="examAddImgSetting(this);" accept="image/*" >
                                        <button class="col-auto btn btn-primary btn-sm" type="button" onclick="document.querySelector('#inp_sample3').click();">찾아보기</button>
                                    </div>
                                    <img src="" data-sample="img">
                                </td>
                            </tr>
                            <tr data-row="sample" data-sample="4">
                                <th class="text-warning">
                                   보기-4(글)
                                </th>
                                <td>
                                    <textarea class=" form-control" data-sample-str="4"></textarea>
                                </td>
                            </tr>
                            <tr data-row="sample_img" data-sample="4">
                                <th class="text-warning">보기-4(이미지)</th>
                                <td>
                                    <div class="row mx-0">
                                        <input type="file" class="col academy_number_file form-control ps-2" id="inp_sample4" aria-describedby="design_btn_findfile2" aria-label="Upload"
                                            onchange="examAddImgSetting(this);" accept="image/*" >
                                        <button class="col-auto btn btn-primary btn-sm" type="button" onclick="document.querySelector('#inp_sample4').click();">찾아보기</button>
                                    </div>
                                    <img src="" style="max-height: 400px;max-width: 100%;" data-sample="img">
                                </td>
                            </tr>
                            <tr data-row="sample" data-sample="5">
                                <th class="text-warning">
                                   보기-5(글)
                                </th>
                                <td>
                                    <textarea class=" form-control" data-sample-str="5"></textarea>
                                </td>
                            </tr>
                            <tr data-row="sample_img" data-sample="5">
                                <th class="text-warning">보기-5(이미지)</th>
                                <td>
                                    <div class="row mx-0">
                                        <input type="file" class="col academy_number_file form-control ps-2" id="inp_sample5" aria-describedby="design_btn_findfile2" aria-label="Upload"
                                            onchange="examAddImgSetting(this);" accept="image/*" >
                                        <button class="col-auto btn btn-primary btn-sm" type="button" onclick="document.querySelector('#inp_sample5').click();">찾아보기</button>
                                    </div>
                                    <img src="" style="max-height: 400px;max-width:100%" data-sample="img">
                                </td>
                            </tr>

                            <tr data-row="commentary">
                                <th class="text-danger">해설(글)</th>
                                <td>
                                    <textarea class=" form-control" id="text_commentary"></textarea>
                                </td>
                            </tr>
                            <tr data-row="commentary_img">
                                <th class="text-danger">해설(이미지)</th>
                                <td>
                                    <div class="row mx-0">
                                        <input type="file" class="col academy_number_file form-control ps-2" id="inp_commentary_img" aria-describedby="design_btn_findfile2" aria-label="Upload"
                                            onchange="examAddImgSetting(this);" accept="image/*" >
                                        <button class="col-auto btn btn-primary btn-sm" type="button" onclick="document.querySelector('#inp_commentary_img').click();">찾아보기</button>
                                    </div>
                                    <img src="" style="max-height: 400px;max-width:100%" data-commentary="img">
                                </td>
                            </tr>
                            <tr data-row="commentary_video">
                                <th class="text-danger">해설(동영상)</th>
                                <td>
                                    <div class="row mx-0">
                                        <input type="file" class="col academy_number_file form-control ps-2" id="inp_commentary_video" aria-describedby="design_btn_findfile2" aria-label="Upload"
                                            onchange="examAddImgSetting(this, true);" accept="video/*" >
                                        <button class="col-auto btn btn-primary btn-sm" type="button" onclick="document.querySelector('#inp_commentary_video').click();">찾아보기</button>
                                    </div>
                                    <video src="" style="max-height: 400px;max-width:100%" data-commentary="video" controls></video>
                                </td>
                            </tr>
                        </tbody>

                    </table>

                </div>
            </div>
            {{--  문제 생성 / 답 --}}
            <div class="col-lg-5">
                <div class="rounded-3 bg-primary-subtle text-white fs-5 py-1 px-2" style="height:40px">
                    답안지 작성
                </div>
                <div class="rounded-2 border overflow-auto" style="height:calc(70vh + 38px) ">
                    <div class="rounded-3 p-2">
                        <div id="div_number_inner_div_simple_omr_setting" style="text-align: right;">
                            <!-- <span style="color: blue;">*</span>보기수: -->

                            <button data-btn-bogi-cnt="4" class="btn btn-sm btn-outline-primary" hidden onclick="examActivateButton(this)">4</button>
                            <button data-btn-bogi-cnt="5" class="btn btn-sm btn-outline-primary active" hidden onclick="examActivateButton(this)">5</button>

                            <span>
                                <span style="color: red;">*</span>추가 수
                            </span>
                            <input type="number" class="form-control" style="width: 50px;display: inline;padding: 5px;" maxlength="3" value="1" id="inp_number_omr" onkeyup="if(event.keyCode == 13)appendSimpleOmrView();">
                            <button class="btn btn-sm btn-primary ms-2" onclick="examAppendSimpleOmrView();">+</button>
                        </div>
                    </div>
                    <!-- tab 기본문제 / 도전문제 / 도전유사 / 유사문제 -->
                    <div class="px-3">
                        <button data-btn-tab-exam-type="normal" onclick="examTypeTab(this);" class="rounded-top-4 btn btn-outline-primary active">기본문제</button>
                        <button data-btn-tab-exam-type="challenge" onclick="examTypeTab(this);" class="rounded-top-4 btn btn-outline-primary">도전문제</button>
                        <button data-btn-tab-exam-type="challenge_similar" onclick="examTypeTab(this);" class="rounded-top-4 btn btn-outline-primary">도전유사</button>
                        <button data-btn-tab-exam-type="similar" onclick="examTypeTab(this);" class="rounded-top-4 btn btn-outline-primary">유사문제</button>
                    </div>
                    <div class="">
                        <table class="table table-bordered align-middle">
                            <tbody data-bundle="exam_detail_list">
                                <tr data-row="copy" onclick="examShowExamDetailContent(this);" class="cursor-pointer" hidden>
                                    <input type="hidden" data-exam-detail-seq>
                                    <input type="hidden" data-answer-type value="sample">
                                    <td class="tr_index" data-num >1</td>
                                    <td class=" active text-center" style="cursor:pointer; width:20%;padding:5px;" onclick="" >
                                        <button data-btn-sample class="btn btn-sm btn-outline-primary active" onclick="examViewCheckSetting(this, 'sample')">보기</button>
                                        <button data-btn-subjective class="btn btn-sm btn-outline-primary" onclick="examViewCheckSetting(this, 'subjective')" hidden>주관식</button>
                                        <input class="exam_one_score" type="hidden" value="0">
                                    </td>
                                    <td class="text-center" style="width: 60%;">
                                        <div data-class="tr_sample_child" >
                                            <button style="font-size: 1erm; height: 25px; width: 25px;vertical-align:middle;" onclick="examSaveExamEach(this, 'sample');" class="ms-2 rounded-pill  btn p-0 btn-outline-info">1</button>
                                            <button style="font-size: 1erm; height: 25px; width: 25px;vertical-align:middle;" onclick="examSaveExamEach(this, 'sample');" class="ms-2 rounded-pill  btn p-0 btn-outline-info">2</button>
                                            <button style="font-size: 1erm; height: 25px; width: 25px;vertical-align:middle;" onclick="examSaveExamEach(this, 'sample');" class="ms-2 rounded-pill  btn p-0 btn-outline-info">3</button>
                                            <button style="font-size: 1erm; height: 25px; width: 25px;vertical-align:middle;" onclick="examSaveExamEach(this, 'sample');" class="ms-2 rounded-pill  btn p-0 btn-outline-info">4</button>
                                            <button style="font-size: 1erm; height: 25px; width: 25px;vertical-align:middle;" onclick="examSaveExamEach(this, 'sample');" class="ms-2 rounded-pill  btn p-0 btn-outline-info">5</button>
                                        </div>
                                        <div data-class="tr_subjective_child" hidden >
                                            <div class="row ml-0 mr-0">
                                                <div class="col-9" style="padding-right: 0px;">
                                                    <input type="text" class="inp_tr_subjective_child" onblur="examSaveExamEach(this, 'subjective');" style="margin-top:0px;height:31px;width:150px;" placeholder="답이 여러개일때 ; 를 이용하세요.">
                                                </div>
                                                <div class="col-3" style="padding-left: 0px;">
                                                    <button onclick="examSaveExamEach(this, 'subjective');" class="btn btn-sm btn-primary">저장</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mx-0 border-top mt-3 pt-2 gap-2">
                                            <span class="col " data-content-area-name> - </span>
                                            <span class="col " data-cognitive-area-name> - </span>
                                            <input type="hidden" data-content-area-seq>
                                            <input type="hidden" data-cognitive-area-seq>
                                        </div>
                                    </td>
                                    <td rowspan="1" style="width: 20%;">
                                        <div style="vertical-align: middle; text-align:center;">
                                            <button class="btn_delete btn btn-danger btn-sm" onclick="examDetailDelete(this);">삭제</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>


</div>

{{--  모달 / 문제 등록 --}}
<div class="modal fade" id="modal_exam_add" tabindex="-1" aria-hidden="true" style="display: none;">
    <input type="hidden" data-exam-seq >
    <div class="modal-dialog  modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" data-modal-title>문제 타이틀 등록</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick=""></button>
            </div>
            <div class="modal-body">
                <div class="row mx-0 gap-1">
                    <select class="col form-select" data-subject-code onchange="subjectCodeChange(this)">
                        <option value="">과목전체</option>
                        @if(!empty($subject_codes))
                        @foreach($subject_codes as $subject_code)
                        <option value="{{$subject_code->id}}">{{$subject_code->code_name}}</option>
                        @endforeach
                        @endif

                    </select>
                    <select class="col form-select" data-subject-code2 disabled>
                        <option value="">한자급수</option>
                        @if(!empty($subject_codes2))
                        @foreach($subject_codes2 as $subject_code)
                        <option value="{{$subject_code->id}}" data-code-pt="{{$subject_code->code_pt}}">{{$subject_code->code_name}}</option>
                        @endforeach
                        @endif
                    </select>
                    <select class="col form-select" data-grade-code >
                        <option value="">학년전체</option>
                        @if(!empty($grade_codes))
                        @foreach($grade_codes as $grade_code)
                        <option value="{{$grade_code->id}}">{{$grade_code->code_name}}</option>
                        @endforeach
                        @endif
                    </select>
                    <select class="col form-select" data-semester-code >
                        <option value="">학기전체</option>
                        @if(!empty($semester_codes))
                        @foreach($semester_codes as $semester_code)
                        <option value="{{$semester_code->id}}">{{$semester_code->code_name}}</option>
                        @endforeach
                        @endif
                    </select>
                    {{--  평가분류 --}}
                    <select class="col form-select" data-evaluation-code >
                        <option value="">평가분류</option>
                        @if(!empty($evaluation_codes))
                        @foreach($evaluation_codes as $evaluation_code)
                        <option value="{{$evaluation_code->id}}">{{$evaluation_code->code_name}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="row mx-0 mt-2">
                    <input class="form-control" placeholder="문제를 쉽게 알아볼수 있게, 제목을 입력해주세요." data-exam-title >
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="examInsert();" data-btn-exam-add>문제등록</button>
                <button type="button" class="modal_close btn btn-secondary" data-bs-dismiss="modal"
                    onclick="">닫기</button>
            </div>
        </div>
    </div>
</div>

{{--  모달 / 문제 일괄등록 --}}
<div class="modal fade" id="modal_exam_batch_add" tabindex="-1" aria-hidden="true" style="display: none;">
    <input type="hidden" data-exam-seq >
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" data-modal-title>문제 타이틀 등록</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick=""></button>
            </div>
            <div class="modal-body">
                <input type="file" class="form-control" id="excel_file_input">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="examBatchAdd();" data-btn-exam-add>문제등록</button>
                <button type="button" class="modal_close btn btn-secondary" data-bs-dismiss="modal"
                    onclick="">닫기</button>
            </div>
        </div>
    </div>
</div>

<script>

document.addEventListener('DOMContentLoaded', function(){
    // const myModal = new bootstrap.Modal(document.getElementById('modal_exam_add'), {
    //     keyboard: false
    // });
    // myModal.show();
    examSelect();
// TEST:
});


// 문제등록 모달 show
function examAddModalShow(type, vthis){
    const myModal = new bootstrap.Modal(document.getElementById('modal_exam_add'), {
        keyboard: false
    });
    myModal.show();

    // 수정일때는 seq를 넣어준다.
    const modal = document.querySelector('#modal_exam_add');
    if(type == 'insert'){
        modal.querySelector('[data-exam-seq]').value = '';
        modal.querySelector('[data-subject-code]').value = '';
        modal.querySelector('[data-subject-code2]').value = '';
        modal.querySelector('[data-subject-code2]').disabled = true;
        modal.querySelector('[data-grade-code]').value = '';
        modal.querySelector('[data-semester-code]').value = '';
        modal.querySelector('[data-exam-title]').value = '';
        modal.querySelector('[data-evaluation-code]').value = '';
        modal.querySelector('[data-modal-title]').innerText = '문제 등록';
        modal.querySelector('[data-btn-exam-add]').innerText = '문제등록';
    }else if(type == 'update'){
        const tr = vthis.closest('tr');

        // 시험 수정시 모달 세팅
        examAddModalSetting(tr);
        modal.querySelector('[data-subject-code]').onchange();
        modal.querySelector('[data-modal-title]').innerText = '문제 수정';
        modal.querySelector('[data-btn-exam-add]').innerText = '문제수정';
    }
}

// 시험 수정시 모달 세팅
function examAddModalSetting(tr){
    const modal = document.querySelector('#modal_exam_add');
    const exam_seq = tr.querySelector('[data-exam-seq]').value;
    modal.querySelector('[data-exam-seq]').value = exam_seq;
    // select 의 text 를 가지고 선택
    modal.querySelector('[data-subject-code]').value = tr.querySelector('[data-subject-seq]').value;
    modal.querySelector('[data-subject-code2]').value = tr.querySelector('[data-subject-seq]').dataset.subjectSeq2;
    modal.querySelector('[data-grade-code]').value = tr.querySelector('[data-grade-seq]').value;
    modal.querySelector('[data-semester-code]').value = tr.querySelector('[data-semester-seq]').value;
    modal.querySelector('[data-exam-title]').value = tr.querySelector('[data-exam-title]').innerText;
    modal.querySelector('[data-evaluation-code]').value = tr.querySelector('[data-evaluation-seq]').value;

}

// 문제 조회
function examSelect(page_num){
    const title = document.querySelector('[data-search-title]').value;
    const subject_code = document.querySelector('[data-search-subject-code]').value;
    const grade_code = document.querySelector('[data-search-grade-code]').value;
    const semester_code = document.querySelector('[data-search-semester-code]').value;
    const evaluation_code = document.querySelector('[data-search-evaluation-code]').value;

    const page = "/manage/exam/select";
    const parameter = {
        title: title,
        subject_code: subject_code,
        grade_code: grade_code,
        semester_code: semester_code,
        page:page_num,
        evaluation_code: evaluation_code
    };

    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            // 초기화
            const bundle = document.querySelector('[data-bundle="exam_list"]');
            const row_copy = bundle.querySelector('[data-row="copy"]');
            bundle.innerHTML = '';
            bundle.appendChild(row_copy);

            const exams = result.exams;
            // 페이징
            userPaymentTablePaging(result.exams, '1');

            // foreach
            exams.data.forEach(function(result){
                const row = row_copy.cloneNode(true);
                row.hidden = false;
                row.dataset.row='clone';
                row.querySelector('[data-subject-name]').innerText = ( result.subject_name||'' )+(result.subject_name2?' ('+result.subject_name2+')':'');
                row.querySelector('[data-grade-name]').innerText = result.grade_name||'';
                row.querySelector('[data-semester-name]').innerText = result.semester_name||'';
                row.querySelector('[data-evaluation-name]').innerText = result.evaluation_name||'';
                row.querySelector('[data-exam-title]').innerText = result.exam_title;
                row.querySelector('[data-created-at]').innerText = new Date(result.created_at).format('yyyy.MM.dd');
                row.querySelector('[data-updated-at]').innerText = new Date(result.updated_at).format('yyyy.MM.dd');
                row.querySelector('[data-created-name]').innerText = result.created_name;
                row.querySelector('[data-updated-name]').innerText = result.updated_name;
                row.querySelector('[data-subject-seq]').value = result.subject_seq;
                row.querySelector('[data-subject-seq]').dataset.subjectSeq2 = result.subject_seq2||'';
                row.querySelector('[data-grade-seq]').value = result.grade_seq;
                row.querySelector('[data-semester-seq]').value = result.semester_seq;
                row.querySelector('[data-evaluation-seq]').value = result.evaluation_seq;
                row.querySelector('[data-exam-seq]').value = result.id;
                bundle.appendChild(row);
            });
        }
    });
}

// 문제 등록
function examInsert(){
    const msg = "<div class='text-sb-24px'>문제를 등록 하시겠습니까?</div>"
    const modal = document.querySelector('#modal_exam_add');

    const exam_seq = modal.querySelector('[data-exam-seq]').value;
    const subject_seq = modal.querySelector('[data-subject-code]').value;
    const subject_seq2 = modal.querySelector('[data-subject-code2]').value;
    const grade_seq = modal.querySelector('[data-grade-code]').value;
    const semester_seq = modal.querySelector('[data-semester-code]').value;
    const evaluation_seq = modal.querySelector('[data-evaluation-code]').value;
    const title = modal.querySelector('[data-exam-title]').value;

    const page = "/manage/exam/insert";
    const parameter = {
        exam_seq:exam_seq,
        subject_seq: subject_seq,
        subject_seq2:subject_seq2,
        grade_seq: grade_seq,
        semester_seq: semester_seq,
        evaluation_seq: evaluation_seq,
        title: title
    };
    sAlert('', msg, 3, function(){
        queryFetch(page, parameter, function(result){
            if((result.resultCode||'') == 'success'){
                if(exam_seq == '')
                    toast('문제가 등록 되었습니다.');
                else
                    toast('문제가 수정 되었습니다.');

                // 모달 닫기.
                modal.querySelector('.modal_close').click();

                // 문제 리스트 가져오기.
                examSelect();

            }else{}
        });
    });
}

// 문제 일괄등록 모달 show
function examBatchAddModalShow(){
    const modalElement = document.getElementById('modal_exam_batch_add');
    const myModal = new bootstrap.Modal(modalElement, {
        keyboard: false
    });
    myModal.show();
}

// 문제 일괄등록
function examBatchAdd(){
    const modal = document.querySelector('#modal_exam_batch_add');
    const fileInput = modal.querySelector('#excel_file_input');
    const file = fileInput.files[0];

    if (!file) {
        toast('파일을 선택해주세요.');
        return;
    }

    if (file.type !== 'text/csv') {
        toast('CSV 파일만 업로드 가능합니다.');
        return;
    }

    const formData = new FormData();
    formData.append('excel_file', file);

    const page = '/manage/exam/batch/upload';

    // FormData를 사용할 때는 fetch 옵션을 추가해야 합니다
    const fetchOptions = {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{csrf_token()}}'
        }
    };

    // queryFetch 함수를 수정하거나, 직접 fetch를 사용
    fetch(page, fetchOptions)
        .then(response => response.json())
        .then(result => {
            if(result.resultCode === 'success'){
                toast('문제가 등록 되었습니다.');
                // 필요한 경우 모달 닫기
                // modal.hide();
                // 필요한 경우 리스트 새로고침
                // refreshList();
            } else {
                toast(result.message || '업로드 중 오류가 발생했습니다.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toast('업로드 중 오류가 발생했습니다.');
        });
}

// 페이징 함수
function userPaymentTablePaging(rData, target){
    if(!rData) return;
    const from = rData.from;
    const last_page = rData.last_page;
    const per_page = rData.per_page;
    const total = rData.total;
    const to = rData.to;
    const current_page = rData.current_page;
    const data = rData.data;
    //페이징 처리
    const notice_ul_page = document.querySelector(`[data-page='${target}']`);
    //prev button, next_button
    const page_prev = notice_ul_page.querySelector(`[data-page-prev='${target}']`);
    const page_next = notice_ul_page.querySelector(`[data-page-next='${target}']`);
    //페이징 처리를 위해 기존 페이지 삭제
    notice_ul_page.querySelectorAll(".page_num").forEach(element => {
        element.remove();
    });
    //#page_first 클론
    const page_first = document.querySelector(`[data-page-first='${target}']`);
    //페이지는 1~10개 까지만 보여준다.
    let page_start = 1;
    let page_end = 10;
    if(current_page > 5){
        page_start = current_page - 4;
        page_end = current_page + 5;
    }
    if(page_end > last_page){
        page_end = last_page;
        if(page_end <= 10)
            page_start = 1;
    }


    let is_next = false;
    for(let i = page_start; i <= page_end; i++){
        const copy_page_first = page_first.cloneNode(true);
        copy_page_first.innerText = i;
        copy_page_first.removeAttribute("data-page-first");
        copy_page_first.classList.add("page_num", 'px-3');
        copy_page_first.hidden = false;
        //현재 페이지면 active
        if(i == current_page){
            copy_page_first.classList.add("active");
        }
        //#page_first 뒤에 붙인다.
        notice_ul_page.insertBefore(copy_page_first, page_next);
        //시작 페이지 보다 11보다 큰 i 이면 page_prev disabled 을 제거
        if(i > 11){
            page_next.setAttribute("data-is-next", "1");
            page_prev.classList.remove("disabled");
        }else{
            page_next.setAttribute("data-is-next", "0");
        }
        if(i == 1){
            // page_prev.classList.add("disabled");
        }
        if(last_page == i){
            // page_next.classList.add("disabled");
            is_next = true;
        }
    }
    if(!is_next){
        page_next.classList.remove("disabled");
    }

    if(data.length != 0)
        notice_ul_page.hidden = false;
        else
        notice_ul_page.hidden = true;
}

// 페이지 번호 클릭
function userPaymentPageFunc(target, type){
    if(type == 'next'){
        const page_next = document.querySelector(`[data-page-next="${target}"]`);
        if(page_next.getAttribute("data-is-next") == '0') return;
        // data-page 의 마지막 page_num 의 innerText를 가져온다
        const last_page = document.querySelector(`[data-page="${target}"] .page_num:last-of-type`).innerText;
        const page = parseInt(last_page) + 1;
        if(target == "1")
            examSelect(page);
    }
    else if(type == 'prev'){
        // [data-page-first]  next tag 의 innerText를 가져온다
        const page_first = document.querySelector(`[data-page-first="${target}"]`);
        const page = page_first.innerText;
        if(page == 1) return;
        const page_num = page*1 -1;
        if(target == "1")
            examSelect(page);
    }
    else{
        if(target == "1")
            examSelect(type);
    }
}

// 문제 상세 섹션 보기
function examMainSectionShow(type, vthis){
    // main section 을 모두 숨김처리.
    document.querySelectorAll('[data-main-section]').forEach(function(result){
         result.hidden = true;
    });
    if(type == 'main'){
        document.querySelector('[data-main-section="1"]').hidden = false;
        document.querySelector('[data-main-exam-seq]').value = '';
        document.querySelector('[data-main-subject-seq]').value = '';
    }
    else if (type == 'detail'){
        const tr = vthis.closest('tr');
        const exam_seq = tr.querySelector('[data-exam-seq]').value;
        const subject_seq = tr.querySelector('[data-subject-seq]').value;
        document.querySelector('[data-main-section="2"]').hidden = false;
        document.querySelector('[data-main-exam-seq]').value = exam_seq;
        document.querySelector('[data-main-subject-seq]').value = subject_seq;
        examDetailSelect();
    }

}

// 보기숫자 지정
function examActivateButton(vthis){
    // data-btn-bogi-cnt 를 모두 비활성화한후에
    document.querySelectorAll('[data-btn-bogi-cnt]').forEach(function(result){
        result.classList.remove('active');
    });
    vthis.classList.add('active');
}

// 보기, 주관식 버튼 클릭
function examViewCheckSetting(vthis, type){
    event.stopPropagation();
    const tr = vthis.closest('tr');
    tr.querySelector('[data-btn-subjective]').classList.remove('active');
    tr.querySelector('[data-btn-sample]').classList.remove('active');
    vthis.classList.add('active');

    if(type == 'sample'){
        tr.querySelector('[data-class="tr_sample_child"]').hidden = false;
        tr.querySelector('[data-class="tr_subjective_child"]').hidden = true;
        tr.querySelector('[data-answer-type]').value = 'sample';
    }else{
        tr.querySelector('[data-class="tr_sample_child"]').hidden = true;
        tr.querySelector('[data-class="tr_subjective_child"]').hidden = false;
        tr.querySelector('[data-answer-type]').value = 'subjective';
    }
}

// 답안치 추가.
function examAppendSimpleOmrView(){
    // #inp_number_omr 의 숫자 값만큼 보기를 추가한다.
    const cnt = document.querySelector('#inp_number_omr').value;
    const bundle = document.querySelector('[data-bundle="exam_detail_list"]');
    const row_copy = bundle.querySelector('[data-row="copy"]');
    for(let i = 0; i < cnt; i++){
        const row = row_copy.cloneNode(true);
        row.hidden = false;
        row.querySelector('.tr_index').innerText = bundle.querySelectorAll('[data-row="copy"]').length;
        row.dataset.row='clone';
        bundle.appendChild(row);
    }

    // 순서 정렬
    examReorderIndex();
}

// 답안지 작성 란에서 idx를 재정렬해준다.
function examReorderIndex(){
    const trs = document.querySelectorAll('[data-bundle="exam_detail_list"] tr');
    trs.forEach(function(result, idx){
        result.querySelector('.tr_index').innerText = idx;
    });
}

// 기본문제 도전문제 도전유사 유사문제 탭 클릭
function examTypeTab(vthis){
    // data-btn-tab-exam-type= 모두 비활성화.
    document.querySelectorAll('[data-btn-tab-exam-type]').forEach(function(result){
        result.classList.remove('active');
    });
    vthis.classList.add('active');
    examDetailSelect();
}

// 문제 상세 리스트 조회 (불러오기)
function examDetailSelect(){
    const exam_type = document.querySelector('[data-btn-tab-exam-type].active').getAttribute('data-btn-tab-exam-type');
    const exam_seq = document.querySelector('[data-main-exam-seq]').value;

    const page = "/manage/exam/detail/select";
    const parameter = {
        exam_seq: exam_seq,
        exam_type: exam_type
    };
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            // 초기화
            const bundle = document.querySelector('[data-bundle="exam_detail_list"]');
            const row_copy = bundle.querySelector('[data-row="copy"]');
            bundle.innerHTML = '';
            bundle.appendChild(row_copy);

            const exam_details = result.exam_details;
            exam_details.forEach(function(detail){
                const row = row_copy.cloneNode(true);
                row.hidden = false;
                row.dataset.row='clone';
                row.querySelector('[data-exam-detail-seq]').value = detail.id;
                row.querySelector('[data-answer-type]').value = detail.answer_type;
                row.querySelector('[data-num]').innerText = detail.exam_num;
                if(detail.content_area_name)
                    row.querySelector('[data-content-area-name]').innerText = detail.content_area_name;
                if(detail.cognitive_area_name)
                    row.querySelector('[data-cognitive-area-name]').innerText = detail.cognitive_area_name;
                // 보기 타입 답체크.
                if(detail.answer_type == 'sample'){
                    row.querySelector('[data-class="tr_sample_child"]').hidden = false;
                    row.querySelector('[data-class="tr_subjective_child"]').hidden = true;
                    const answer = (detail.answer||'').split(';');
                    const buttons = row.querySelectorAll('[data-class="tr_sample_child"] button');
                    buttons.forEach(function(result){
                        if(answer.includes(result.innerText)){
                            result.classList.add('active');
                        }
                    });
                }
                // 주관식일때 답 체크
                else if(detail.answer_type == 'subjective'){
                    row.querySelector('[data-btn-subjective]').click();
                    row.querySelector('[data-class="tr_sample_child"]').hidden = true;
                    row.querySelector('[data-class="tr_subjective_child"]').hidden = false;
                    row.querySelector('.inp_tr_subjective_child').value = detail.answer;
                }
                bundle.appendChild(row);
            });
            if(exam_details.length > 0){
                bundle.querySelector('[data-row="clone"]').click();
            }

            // 순서 정렬
            examReorderIndex();
        }else{}
    });
}

//문제 상세 리스트 추가.
function examDetailInsert(tr){
    const exam_seq = document.querySelector('[data-main-exam-seq]').value;
    const exam_detail_seq = tr.querySelector('[data-exam-detail-seq]').value;
    const exam_num = tr.querySelector('[data-num]').innerText;
    const exam_type = document.querySelector('[data-btn-tab-exam-type].active').getAttribute('data-btn-tab-exam-type');
    const answer_type = tr.querySelector('[data-answer-type]').value;
    const answer = getExamAnswer(tr);

    const page = "/manage/exam/detail/insert";
    const parameter = {
        exam_seq: exam_seq,
        exam_detail_seq: exam_detail_seq,
        exam_num: exam_num,
        exam_type: exam_type,
        answer_type: answer_type,
        answer: answer
    };

    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            toast('문제가 등록 되었습니다.');
            tr.querySelector('[data-exam-detail-seq]').value = result.exam_detail_id;
        }
    });
}

// 답안지에서 답가져오기.
function getExamAnswer(tr){
    const answer_type = tr.querySelector('[data-answer-type]').value;
    let answer = '';
    if(answer_type == 'sample'){
        // 복수 답(활성화) 되어있을때는 구분자 ; 로 넣어준다.
         answers = tr.querySelectorAll('[data-class="tr_sample_child"] button');
        answer = '';
        answers.forEach(function(result){
            if(result.classList.contains('active')){
                if(answer.length > 0)
                    answer += ';';
                answer += result.innerText;
            }
        });

    }else if(answer_type == 'subjective'){
        answer = tr.querySelector('.inp_tr_subjective_child').value;
    }
    return answer;
}


// 정답 작성.
function examSaveExamEach(vthis, type){
    event.stopPropagation();
    const tr = vthis.closest('tr');
    const answer_type = tr.querySelector('[data-answer-type]').value;

    if(type == 'sample'){
        vthis.classList.toggle('active');
    }else if(type == 'subjective'){
        const answer = tr.querySelector('.inp_tr_subjective_child').value;
        if(answer.length == 0){
            toast('답을 입력해주세요.');
            return;
        }
    }
    examDetailInsert(tr);
}


// 시험지 보기.
function examShowExamDetailContent(vthis){
    event.stopPropagation();
    const tr = vthis.closest('tr');
    const bundle = document.querySelector('[data-bundle="exam_detail_list"]');
    bundle.querySelectorAll('[data-row="clone"]').forEach(function(result){
        result.classList.remove('active');
        result.classList.remove('border-primary');
        result.classList.remove('border-3');
    });
    tr.classList.add('active');
    tr.classList.add('border-primary');
    tr.classList.add('border-3');
    examDetailContentSelect();
}


//시험지 저장.
function examDetailContentInsert(vthis){
    const tr = document.querySelector('[data-bundle="exam_detail_list"] tr.active');
    const exam_seq = document.querySelector('[data-main-exam-seq]').value;
    const exam_detail_seq = tr.querySelector('[data-exam-detail-seq]').value;

    const ui_type = document.querySelector('[data-ui-type]').value;
    const questions = document.querySelector('#text_questions').value;
    const questions2 = document.querySelector('#text_questions2').value;
    let samples = [];
    document.querySelectorAll('[data-sample-str]').forEach(function(result){
        samples.push(result.value);
    });
    const commentary = document.querySelector('#text_commentary').value;
    const commentary_img = document.querySelector('#inp_commentary_img').files[0] || null;
    const question_img_input = document.querySelector('#inp_questions');
    const question_img = question_img_input ? question_img_input.files[0] : null;
    const question_img_list = document.querySelectorAll('.questions-img-wrap-box');
    let question_img_list_data = [];
    question_img_list.forEach(function(result) {
        const inputFile = result.querySelector('input[type="file"]');
        if (inputFile) {
            const file = inputFile.files[0] || null;
            const seq = inputFile.getAttribute('data-questions-img-seq') || "";
            if (file) {
                question_img_list_data.push({ "file": file, "seq": seq });
            }
        }
    });

    if (question_img_list_data.length === 0 && question_img) {
        question_img_list_data.push({ "file": question_img, "seq": "" });
    }
    const sample_img1 = document.querySelector('#inp_sample1').files[0] || null;
    const sample_img2 = document.querySelector('#inp_sample2').files[0] || null;
    const sample_img3 = document.querySelector('#inp_sample3').files[0] || null;
    const sample_img4 = document.querySelector('#inp_sample4').files[0] || null;
    const sample_img5 = document.querySelector('#inp_sample5').files[0] || null;
    const commentary_video = document.querySelector('#inp_commentary_video').files[0] || null;

    const content_area_seq = document.querySelector('[data-detail-content-area-seq]').value;
    const cognitive_area_seq = document.querySelector('[data-detail-cognitive-area-seq]').value;
    const acontent_area_name = document.querySelector('[data-detail-content-area-seq]').options[document.querySelector('[data-detail-content-area-seq]').selectedIndex].innerText;
    const cognitive_area_name = document.querySelector('[data-detail-cognitive-area-seq]').options[document.querySelector('[data-detail-cognitive-area-seq]').selectedIndex].innerText;

    if(!tr){
        toast('답안지 작성 리스트에서 번호를 눌러주세요.');
        return;
    }
    if(exam_seq == '' || exam_detail_seq == ''){
        toast('먼저 답을 체크하거나, 주관식일경우 입력해주세요.');
        return;
    }

    const page = "/manage/exam/detail/content/insert";
    let formData = new FormData();
    formData.append("exam_seq", exam_seq);
    formData.append("exam_detail_seq", exam_detail_seq);
    formData.append('ui_type', ui_type);
    formData.append("questions", questions);
    formData.append("questions2", questions2);
    formData.append("question_img", question_img);
    // 다중 이미지 추가
    if (question_img_list_data.length === 1) {
        formData.append("question_img", question_img_list_data[0].file);
    } else if (question_img_list_data.length > 1) {
        question_img_list_data.forEach((imgData, index) => {
            formData.append(`question_img_list[${index}][file]`, imgData.file);
            formData.append(`question_img_list[${index}][seq]`, imgData.seq);
        });
    } else if (question_img) {
        formData.append("question_img", question_img);
    }
    formData.append("samples", samples.join(';'));
    formData.append("sample_img1", sample_img1);
    formData.append("sample_img2", sample_img2);
    formData.append("sample_img3", sample_img3);
    formData.append("sample_img4", sample_img4);
    formData.append("sample_img5", sample_img5);
    formData.append("commentary", commentary);
    formData.append("commentary_img", commentary_img);
    formData.append("commentary_video", commentary_video);
    formData.append("content_area_seq", content_area_seq);
    formData.append("cognitive_area_seq", cognitive_area_seq);

    vthis.querySelector('span').hidden = false;
    queryFormFetch(page, formData, function(result){
        vthis.querySelector('span').hidden = true;
        if((result.resultCode||'') == 'success'){
            toast('문제(시험지)가 등록 되었습니다.');
            tr.querySelector('[data-content-area-name]').innerText = acontent_area_name;
            tr.querySelector('[data-cognitive-area-name]').innerText = cognitive_area_name;
        }
    });
}

// 시험지 가져오기.
function examDetailContentSelect(){
    document.querySelector('[data-bundle="exam_detail_manage"]').hidden = false;
    const tr = document.querySelector('[data-bundle="exam_detail_list"] tr.active');
    const exam_seq = document.querySelector('[data-main-exam-seq]').value;
    const exam_detail_seq = tr.querySelector('[data-exam-detail-seq]').value;
    const subject_seq = document.querySelector('[data-main-subject-seq]').value;

    const page = "/manage/exam/detail/content/select";
    const parameter = {
        exam_seq: exam_seq,
        exam_detail_seq: exam_detail_seq,
        subject_seq: subject_seq,
    };

    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            console.log(result);
            // 초기화
            setClearExamDetailContent();

            // 시험지 가져오기.
            const content = result.exam_detail;
            const content_areas = result.content_areas;
            const cognitive_areas = result.cognitive_areas;

            const content_area_el = document.querySelector('[data-detail-content-area-seq]');
            const cognitive_area_el = document.querySelector('[data-detail-cognitive-area-seq]');
            content_area_el.innerHTML = '<option value="">내용영역</option>';
            cognitive_area_el.innerHTML = '<option value="">인지영역</option>';
            content_areas.forEach(function(content_area){
                const option = document.createElement('option');
                option.value = content_area.id;
                option.innerText = content_area.code_name;
                content_area_el.appendChild(option);
            });
            cognitive_areas.forEach(function(cognitive_area){
                const option = document.createElement('option');
                option.value = cognitive_area.id;
                option.innerText = cognitive_area.code_name;
                cognitive_area_el.appendChild(option);
            });
            if(content.content_area_seq)
                content_area_el.value = content.content_area_seq;
            if(content.cognitive_area_seq)
                cognitive_area_el.value = content.cognitive_area_seq;
                document.querySelector('[data-ui-type]').value = content.ui_type||'1';
                document.querySelector('#text_questions').value = content.questions||'';
                let questions2Text = content.questions2 || '';
                const questionImgList = content.question_img_list;
                questionImgList.forEach((img, index) => {
                    const placeholder = `\${${index + 1}}`;
                    if (questions2Text.includes(placeholder)) {
                        const imgTag = `<img src="${img.file_path}" alt="문제이미지 ${index + 1}">`;
                        questions2Text = questions2Text.replace(placeholder, imgTag);
                    }
                });
                document.querySelector('#text_questions2').value = content.questions2||'';
                document.querySelector('#text_questions2_preview').innerHTML = questions2Text;
                document.querySelector('#text_commentary').value = content.commentary||'';
                document.querySelector('[data-sample-str="1"]').value = (content.samples||'').split(';')[0] || '';
                document.querySelector('[data-sample-str="2"]').value = (content.samples||'').split(';')[1] || '';
                document.querySelector('[data-sample-str="3"]').value = (content.samples||'').split(';')[2] || '';
                document.querySelector('[data-sample-str="4"]').value = (content.samples||'').split(';')[3] || '';
                document.querySelector('[data-sample-str="5"]').value = (content.samples||'').split(';')[4] || '';
                document.querySelector('[data-questions="img"]').src = content.question_file_path||'' ? content.question_file_path : '';
                document.querySelector('[data-sample="1"] [data-sample="img"]').src = content.sample_file_path1||'' ? content.sample_file_path1 : '';
                document.querySelector('[data-sample="2"] [data-sample="img"]').src = content.sample_file_path2||'' ? content.sample_file_path2 : '';
                document.querySelector('[data-sample="3"] [data-sample="img"]').src = content.sample_file_path3||'' ? content.sample_file_path3 : '';
                document.querySelector('[data-sample="4"] [data-sample="img"]').src = content.sample_file_path4||'' ? content.sample_file_path4 : '';
                document.querySelector('[data-sample="5"] [data-sample="img"]').src = content.sample_file_path5||'' ? content.sample_file_path5 : '';
                document.querySelector('[data-commentary="video"]').src = content.commentary_file_path || '';
                document.querySelector('[data-commentary="img"]').src = content.commentary_img || '';

        }
        const questions_img_wrap = document.querySelector('.questions-img-wrap');
        const question_img_list = result.exam_detail.question_img_list?.length ? result.exam_detail.question_img_list : (result.exam_detail.question_file_path ? [result.exam_detail.question_file_path] : []);
        const question_img_container = document.querySelector('.questions-img-wrap-box');
        if(question_img_list.length == 0){
            questions_img_wrap.innerHTML = '';
            const div = document.createElement('div');
            div.className = 'row mx-0 gap-2 questions-img-wrap-box';
            div.innerHTML = `
                <div class="input-group">
                    <span class="input-group-text">1</span>
                    <input type="file" class="form-control" id="inp_questions_0" data-questions-img-seq="1" aria-describedby="design_btn_findfile2" aria-label="Upload" onchange="examAddImgSetting(this);" accept="image/*">
                    <button class="btn btn-primary btn-sm" type="button" onclick="document.querySelector('#inp_questions_0').click();">찾아보기</button>
                    <button class="btn btn-danger btn-sm" type="button" onclick="examDeleteImg(this);">삭제</button>
                </div>
                <img style="width:auto;" data-questions="img" src="">
            `;
            questions_img_wrap.append(div);
        }else{
            questions_img_wrap.innerHTML = '';
            question_img_list.forEach(function(img_src, index) {
                const div = document.createElement('div');
                div.className = 'row mx-0 gap-2 questions-img-wrap-box';
                div.innerHTML = `
                    <div class="input-group">
                        <span class="input-group-text">${index + 1}</span>
                        <input type="file" class="form-control" id="inp_questions_${index}" data-questions-img-seq="1" aria-describedby="design_btn_findfile2" aria-label="Upload" onchange="examAddImgSetting(this);" accept="image/*">
                        <button class="btn btn-primary btn-sm" type="button" onclick="document.querySelector('#inp_questions_${index}').click();">찾아보기</button>
                        <button class="btn btn-danger btn-sm" type="button" data-exam-uploadfile-seq="${img_src.id}" onclick="examDeleteImg(this);">삭제</button>
                    </div>
                    <img style="width:auto;" data-questions="img" src="${img_src.file_path||img_src}">
                `;
                questions_img_wrap.append(div);
            });
        }

    });


}
// 시험지에 이미지 넣기.
function examAddImgSetting(vthis, is_video){
    const imgWrapBox = vthis.closest('.questions-img-wrap-box');
    const file = vthis.files[0];
    let tag_name = 'img';
    if(is_video)
        tag_name = 'video';
    if(file){
        imgWrapBox.querySelector(tag_name).src = URL.createObjectURL(file);
    }else{
        imgWrapBox.querySelector(tag_name).src = '';
    }
}


// 시험지 초기화
function setClearExamDetailContent(){
    const bundle = document.querySelector('[data-bundle="exam_detail_manage"]');
    bundle.querySelector('[data-ui-type]').value = "1";
    bundle.querySelectorAll('img, video').forEach(function(img){
        img.removeAttribute('src');
    });
    bundle.querySelectorAll('textarea').forEach(function(text){
        text.value = '';
    });
    bundle.querySelectorAll('input').forEach(function(input){
        input.value = '';
    });
    // 첫번재 빼고 모두 삭제.
    document.querySelectorAll('.copy_div_img').forEach(function(item){
        item.remove();
    });
}

// 문제 삭제
function examDelete(vthis){
    const exam_seq = vthis.closest('tr').querySelector('[data-exam-seq]').value;
    const page = "/manage/exam/delete";
    const parameter = {
        exam_seq:exam_seq
    }
    const msg = `<div class='fs-4'>문제를 삭제 하시겠습니까? 삭제후에는 되돌릴수 없습니다.</div>`;
    sAlert('', msg, 3, function(){
        queryFetch(page, parameter, function(result){
            if((result.resultCode||'') == 'success'){
                toast('문제가 삭제 되었습니다.');
                examSelect();
            }
        });
    });
}

// 문제 상세 삭제
// TODO: 문제 삭제시에 이미지도 같이 삭제하는 기능 추가.
function examDetailDelete(vthis){
    event.stopPropagation();
    const exam_seq = document.querySelector('[data-main-exam-seq]').value;
    const exam_detail_seq = vthis.closest('tr').querySelector('[data-exam-detail-seq]').value;
    if(exam_detail_seq == ''){
        vthis.closest('tr').remove();
        return;
    }
    const page = "/manage/exam/detail/delete";
    const parameter = {
        exam_seq: exam_seq,
        exam_detail_seq: exam_detail_seq
    };

    const msg = `<div class='fs-4'>문제상세를 삭제 하시겠습니까? 삭제후에는 되돌릴수 없습니다.</div>`;
    sAlert('', msg, 3, function(){
        queryFetch(page, parameter, function(result){
            if((result.resultCode||'') == 'success'){
                toast('문제가 삭제 되었습니다.');
                examDetailSelect();
            }
        });
    });
}

// 보기 숨기기 토글
function examHideSampleToggle(){
    // 모든 보기 tr을 토글한다.
    document.querySelectorAll('[data-row="sample"]').forEach(function(result){
        result.hidden = !result.hidden;
    });
    document.querySelectorAll('[data-row="sample_img"]').forEach(function(result){
        result.hidden = !result.hidden;
    });
}

// 내용영역 인지영역 가져오기.
function examGetContentArea(vthis){

    const page = "/manage/exam/detail/content/area/select";
    const parameter = {
    };
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){

        }
    });
}

function addQuestionImageInput() {
    const container = document.querySelector('.questions-img-wrap-box');
    const existingInputs = document.querySelectorAll('.questions-img-wrap-box');
    let newIndex = existingInputs.length+1;
    let newInputGroup = ''; // Changed from const to let
    if (existingInputs.length >= 10) {
        alert('이미지는 최대 10개까지만 업로드할 수 있습니다.');
        return;
    }
    newInputGroup = `
        <div class="row mx-0 gap-2 questions-img-wrap-box copy_div_img">
            <div class="input-group">
                <span class="input-group-text">${newIndex}</span>
                <input type="file" class="form-control" id="inp_questions_${newIndex}" data-questions-img-seq="${newIndex}" aria-describedby="design_btn_findfile2" aria-label="Upload" onchange="examAddImgSetting(this);" accept="image/*">
                <button class="btn btn-primary btn-sm" type="button" onclick="document.querySelector('#inp_questions_${newIndex}').click();">찾아보기</button>
                <button class="btn btn-danger btn-sm" type="button" data-exam-uploadfile-seq="" onclick="examDeleteImg(this);">삭제</button>
            </div>
            <img data-questions="img" src="" style="max-width:300px">
        </div>
    `;
    container.parentNode.insertAdjacentHTML('beforeend', newInputGroup); // Changed appendChild to insertAdjacentHTML
}

function examDeleteImg(vthis){
    const img = vthis.closest('.questions-img-wrap-box');
    img.remove();

    // Update the data-questions-img-seq attribute and input-group-text for all input elements
    document.querySelectorAll('.questions-img-wrap-box').forEach((element, index) => {
        const fileInput = element.querySelector('input[type="file"]');
        const inputGroupText = element.querySelector('.input-group-text');
        if (fileInput) {
            fileInput.setAttribute('data-questions-img-seq', index + 1);
        }
        if (inputGroupText) {
            inputGroupText.innerText = index + 1;
        }
    });

    const examUploadfileSeq = vthis.getAttribute('data-exam-uploadfile-seq');
    if (examUploadfileSeq) {
        if (confirm('이미지를 삭제하시겠습니까?')) {
            const page = "/manage/exam/detail/img/delete";
            const parameter = {
                exam_uploadfile_seq: examUploadfileSeq
            };
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    toast('이미지가 삭제 되었습니다.');
                }
            });
        }
    }

}

// 문제 삽입 / 수정 시 과목에 따라 ,한자 급수 disabled
function subjectCodeChange(vthis){
    const modal = document.getElementById('modal_exam_add');
    const select_el = vthis;
    const select_el2 = modal.querySelector('[data-subject-code2]');
    if(select_el2.querySelectorAll('option').length == 1){
        return;
    }
    const select_el2_option2 = select_el2.querySelectorAll('option')[1];
    const subject_seq = select_el.value;
    if(select_el2_option2.dataset.codePt == subject_seq){
        select_el2.disabled = false;
    }else{
        select_el2.value = '';
        select_el2.disabled = true;
    }
}
</script>
@endsection
