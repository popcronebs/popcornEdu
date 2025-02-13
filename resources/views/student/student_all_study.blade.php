@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title', '전체학습보기')

@section('add_css_js')
    <link href="{{ asset('css/reset.css?5') }}" rel="stylesheet">
@endsection

{{-- 학생 컨텐츠 --}}
@section('layout_coutent')
    <div class="col mx-0 mb-3 pt-5 row position-relative">
        {{-- 상단 --}}
        <article class="pt-5 px-0">
            <div class="row">
                <div class="col-auto">
                    <div class="h-center">
                        <img src="{{ asset('images/graphic_all_study_icon.svg?1') }}" width="72">
                        <span class="cfs-1 fw-semibold align-middle">전체학습내용</span>
                    </div>
                    <div class="pt-2">
                        <span class="cfs-3 fw-medium">여러가지 학습을 한 눈에 쉽게 알아볼 수 있어요.</span>
                    </div>
                </div>
                <div class="col text-end">
                    <div class="pt-5">
                        <div class="d-inline-block select-wrap select-icon" style="min-width:200px">
                            <select class="rounded-pill border-gray lg-select text-sb-24px ps-4"
                                style="min-width:260px;padding-top:18px;padding-bottom:18px;">
                                <option value="">학년선택</option>
                                @if (!empty($grade_codes))
                                    @foreach ($grade_codes as $grade_code)
                                        <option value="{{ $grade_code->id }}">{{ $grade_code->code_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </article>

        {{-- padding 120px --}}
        <div style="height:68px"></div>

        {{-- 전체학습내용 목록 --}}
        <article class="px-0 mx-0">
            <div class="row row-cols-lg-6" style="    
            background-image: url(/images/all_study_bg.svg);
            background-repeat: no-repeat;
            background-position: right;
            background-position-y: bottom;
            ">
                {{-- pc기준 --}}
                {{-- 상단 6 --}}
                <div class="col pt-5 mt-1 ps-0">
                    <div class="modal-shadow-style py-4 px-3 h-100 bg-white d-flex flex-column">
                        <div class="col-auto row mx-1 ">
                            <div class="col-auto px-0 h-center"> <img src="{{ asset('images/special_lecture_icon.svg') }}" alt="32"> </div>
                            <div class="col text-sb-24px h-center px-0">특강수업</div>
                            <div class="col-auto h-center px-0">
                                <button class="btn p-0 h-center">
                                    <img src="{{ asset('images/calendar_arrow_right.svg') }}" width="24">
                                </button>
                            </div>
                        </div>

                        {{-- padding 20px --}}
                        <div style="height:20px"></div>

                        <div class="col rounded-3 scale-bg-gray_01 mx-1">
                            <ul class="p-4">
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">국어</li>
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">수학</li>
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">영어</li>
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">과학</li>
                            </ul>
                        </div>
                    </div>
                </div>
                {{-- 단원평가 --}}
                <div class="col pt-5 mt-1">
                    <div class="modal-shadow-style py-4 px-3 h-100 bg-white d-flex flex-column">
                        <div class="col-auto row mx-1">
                            <div class="col-auto px-0 h-center"> <img src="{{ asset('images/unit_test_icon.svg') }}" alt="32"> </div>
                            <div class="col text-sb-24px h-center px-0">단원평가</div>
                            <div class="col-auto h-center px-0">
                                <button class="btn p-0 h-center">
                                    <img src="{{ asset('images/calendar_arrow_right.svg') }}" width="24">
                                </button>
                            </div>
                        </div>

                        {{-- padding 20px --}}
                        <div style="height:20px"></div>

                        <div class="col rounded-3 scale-bg-gray_01 mx-1">
                            <ul class="p-4">
                                {{-- 단원기출평가 --}}
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">단원기출평가</li>
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">단원평가</li>
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">실력평가</li>
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">실력진단평가</li>
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">서술형평가</li>
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">전국평가</li>
                            </ul>
                        </div>
                    </div>
                </div>
                {{-- 수학 단계별 수업 --}}
                <div class="col pt-5 mt-1">
                    <div class="modal-shadow-style py-4 px-3 h-100 bg-white d-flex flex-column">
                        <div class="col-auto row mx-1">
                            <div class="col-auto px-0 h-center"> <img src="{{ asset('images/math_step_lesson_icon.svg') }}" alt="32"> </div>
                            <div class="col text-sb-24px h-center px-0">수학 단계별 수업</div>
                            <div class="col-auto h-center px-0">
                                <button class="btn p-0 h-center">
                                    <img src="{{ asset('images/calendar_arrow_right.svg') }}" width="24">
                                </button>
                            </div>
                        </div>

                        {{-- padding 20px --}}
                        <div style="height:20px"></div>

                        <div class="col rounded-3 scale-bg-gray_01 mx-1">
                            <ul class="p-4">
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">기본</li>
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">응용</li>
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">심화</li>
                            </ul>
                        </div>
                    </div>
                </div>
                {{-- AI 실력 오답노트 --}}
                <div class="col pt-5 mt-1">
                    <div class="modal-shadow-style py-4 px-3 h-100 bg-white d-flex flex-column">
                        <div class="col-auto row mx-1">
                            <div class="col-auto px-0 h-center"> <img src="{{ asset('images/ai_wrong_note_icon.svg') }}" alt="32"> </div>
                            <div class="col text-sb-24px h-center px-0">AI 실력 오답노트</div>
                            <div class="col-auto h-center px-0">
                                <button class="btn p-0 h-center">
                                    <img src="{{ asset('images/calendar_arrow_right.svg') }}" width="24">
                                </button>
                            </div>
                        </div>

                        {{-- padding 20px --}}
                        <div style="height:20px"></div>

                        <div class="col rounded-3 scale-bg-gray_01 mx-1">
                            <ul class="p-4">
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">나의 오답노트</li>
                            </ul>
                        </div>
                    </div>
                </div>
                {{-- 단원별 요점정리 --}}
                <div class="col pt-5 mt-1">
                    <div class="modal-shadow-style py-4 px-3 h-100 bg-white d-flex flex-column">
                        <div class="col-auto row mx-1">
                            <div class="col-auto px-0 h-center"> <img src="{{ asset('images/unit_summary_icon.svg') }}" alt="32"> </div>
                            <div class="col text-sb-24px h-center px-0">단원별 요점정리</div>
                            <div class="col-auto h-center px-0">
                                <button class="btn p-0 h-center">
                                    <img src="{{ asset('images/calendar_arrow_right.svg') }}" width="24">
                                </button>
                            </div>
                        </div>

                        {{-- padding 20px --}}
                        <div style="height:20px"></div>

                        <div class="col rounded-3 scale-bg-gray_01 mx-1">
                            <ul class="p-4">
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">국어</li>
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">수학</li>
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">영어</li>
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">과학</li>
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">사회</li>
                            </ul>
                        </div>
                    </div>
                </div>
                {{-- 학교공부 --}}
                <div class="col pt-5 mt-1 pe-0">
                    <div class="modal-shadow-style py-4 px-3 h-100 bg-white d-flex flex-column">
                        <div class="col-auto row mx-1">
                            <div class="col-auto px-0 h-center"> <img src="{{ asset('images/school_study_icon.svg') }}" alt="32"> </div>
                            <div class="col text-sb-24px h-center px-0">학교공부</div>
                            <div class="col-auto h-center px-0">
                                <button class="btn p-0 h-center">
                                    <img src="{{ asset('images/calendar_arrow_right.svg') }}" width="24">
                                </button>
                            </div>
                        </div>

                        {{-- padding 20px --}}
                        <div style="height:20px"></div>

                        <div class="col rounded-3 scale-bg-gray_01 mx-1">
                            <ul class="p-4">
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">국어</li>
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">수학</li>
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">영어</li>
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">과학</li>
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">사회</li>
                            </ul>
                        </div>
                    </div>
                </div>
                {{-- 예체능 수업 --}}
                <div class="col pt-5 mt-1 ps-0">
                    <div class="modal-shadow-style py-4 px-3 h-100 bg-white d-flex flex-column">
                        <div class="col-auto row mx-1">
                            <div class="col-auto px-0 h-center"> <img src="{{ asset('images/yecheung_icon.svg') }}" alt="32"> </div>
                            <div class="col text-sb-24px h-center px-0">예체능 수업</div>
                            <div class="col-auto h-center px-0">
                                <button class="btn p-0 h-center">
                                    <img src="{{ asset('images/calendar_arrow_right.svg') }}" width="24">
                                </button>
                            </div>
                        </div>

                        {{-- padding 20px --}}
                        <div style="height:20px"></div>

                        <div class="col rounded-3 scale-bg-gray_01 mx-1">
                            <ul class="p-4">
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">학교 예체능</li>
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">고전 문학 클립</li>
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">미래사회 클립</li>
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">도덕 클립</li>
                            </ul>
                        </div>
                    </div>
                </div>
                {{-- 실력 키우기 --}}
                <div class="col pt-5 mt-1">
                    <div class="modal-shadow-style py-4 px-3 h-100 bg-white d-flex flex-column">
                        <div class="col-auto row mx-1">
                            <div class="col-auto px-0 h-center"> <img src="{{ asset('images/ability_up_icon.svg') }}" alt="32"> </div>
                            <div class="col text-sb-24px h-center px-0">실력키우기</div>
                            <div class="col-auto h-center px-0">
                                <button class="btn p-0 h-center">
                                    <img src="{{ asset('images/calendar_arrow_right.svg') }}" width="24">
                                </button>
                            </div>
                        </div>

                        {{-- padding 20px --}}
                        <div style="height:20px"></div>

                        <div class="col rounded-3 scale-bg-gray_01 mx-1">
                            <ul class="p-4">
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">방학특강</li>
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">학교공부 예/복습</li>
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">한자 7급, 8급</li>
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">한국사</li>
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">한국사능력검정시험</li>
                            </ul>
                        </div>
                    </div>
                </div>
                {{-- 창의체험 --}}
                <div class="col pt-5 mt-1">
                    <div class="modal-shadow-style py-4 px-3 h-100 bg-white d-flex flex-column">
                        <div class="col-auto row mx-1">
                            <div class="col-auto px-0 h-center"> <img src="{{ asset('images/creative_experience_icon.svg') }}" alt="32"> </div>
                            <div class="col text-sb-24px h-center px-0">창의체험</div>
                            <div class="col-auto h-center px-0">
                                <button class="btn p-0 h-center">
                                    <img src="{{ asset('images/calendar_arrow_right.svg') }}" width="24">
                                </button>
                            </div>
                        </div>

                        {{-- padding 20px --}}
                        <div style="height:20px"></div>

                        <div class="col rounded-3 scale-bg-gray_01 mx-1">
                            <ul class="p-4">
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">창의체험 탐구생활</li>
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">과학 창의체험</li>
                                <li class="text-r-20px scale-text-gray_06 mb-1 pb-3">창의체험</li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </article>

        {{-- 160px --}}
        <div style="height:160px"></div>

    </div>
@endsection
