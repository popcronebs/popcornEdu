@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title', '단원별 요점정리')

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
                        <img src="{{ asset('images/unit_summary_icon.svg?1') }}" width="72">
                        <span class="cfs-1 fw-semibold align-middle">단원별 요점정리</span>
                    </div>
                    <div class="pt-2">
                        <span class="cfs-3 fw-medium">여러가지 학습을 한 눈에 쉽게 알아볼 수 있어요.</span>
                    </div>
                </div>
                <div class="col text-end position-relative">
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
        <div>
            <div class="py-lg-5"></div>
            <div class="pt-lg-4"></div>
        </div>

        <section class="modal-shadow-style p-4">
            {{-- 40px,40px --}}
            <div class="p-3">
                <div class="d-flex">
                    {{-- 학기선택, 과목선택 --}}
                    <div class="col">
                        <div class="row">
                            <div class="col-auto h-center">
                                <img src="{{ asset('images/pencil_icon.svg') }}" width="32">
                                <span class="text-sb-24px ps-2">학기 선택</span>
                            </div>
                            {{-- 0,0,0,72px --}}
                            <div class="col h-center ps-5 ms-4">
                                {{-- bundle --}}
                                <div class="row">
                                    {{-- row --}}
                                    <div class="col-auto h-center">
                                        <label class="checkbox">
                                            <input type="checkbox" class="">
                                            <span class=""></span>
                                        </label>
                                        <span class="text-r-24px ps-2">
                                            전체
                                        </span>
                                    </div>
                                    @if (!empty($semester_codes))
                                        @foreach ($semester_codes as $semester_code)
                                            <div class="col-auto h-center">
                                                <label class="checkbox">
                                                    <input type="checkbox" class="">
                                                    <span class=""></span>
                                                </label>
                                                <span class="text-r-24px ps-2">
                                                    {{ $semester_code->code_name }}
                                                </span>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                        </div>
                        {{-- 20px --}}
                        <div class="row pt-3 mt-1">
                            <div class="col-auto h-center">
                                <img src="{{ asset('images/book_icon.svg') }}" width="32">
                                <span class="text-sb-24px ps-2">과목 선택</span>
                            </div>
                            {{-- 0,0,0,72px --}}
                            <div class="col h-center ps-5 ms-4">
                                {{-- bundle --}}
                                <div class="row">
                                    {{-- row --}}
                                    <div class="col-auto h-center">
                                        <label class="checkbox">
                                            <input type="checkbox" class="">
                                            <span class=""></span>
                                        </label>
                                        <span class="text-r-24px ps-2">
                                            전체
                                        </span>
                                    </div>
                                    @if (!empty($subject_codes))
                                        @foreach ($subject_codes as $subject_code)
                                            <div class="col-auto h-center">
                                                <label class="checkbox">
                                                    <input type="checkbox" class="">
                                                    <span class=""></span>
                                                </label>
                                                <span class="text-r-24px ps-2">
                                                    {{ $subject_code->code_name }}
                                                </span>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto h-center">
                        <button type="button" class="btn-ms-primary text-b-24px rounded-pill scale-text-white">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2">
                                <path d="M18.9218 13.05C18.4151 16.4179 15.5091 19 12 19C8.134 19 5 15.866 5 12C5 8.134 8.134 5 12 5C14.8704 5 17.3374 6.72773 18.4175 9.2" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M15.5 9.20312H18.58C18.812 9.20312 19 9.01508 19 8.78313V5.70312" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>    
                            초기화</button>
                    </div>
                </div>
            </div>
        </section>

        {{-- padding 80px --}}
        <div>
            <div class="pt-lg-5"></div>
            <div class="py-lg-3"></div>
        </div>
        <section>
            <div class="text-end">
                <span class="text-danger text-sb-24px">
                    총 <span class="text-danger text-sb-24px">3</span> 개
                </span><span class="text-sb-24px">의 강의가 있습니다.</span>
            </div>
            {{-- 24px --}}
            <div class="mt-4">
                <table class="table">
                    {{-- 길이 조정 --}}
                    <colgroup>
                        <col style="width: 6%">
                        <col style="width: 15%">
                        <col style="width: 15%">
                        <col>
                        <col style="width: 15%">
                    </colgroup>
                    <thead class="modal-shadow-style text-b-20px scale-text-gray_05">
                        <tr class="">
                            <td class="col-auto p-4 text-center">
                                <label class="checkbox">
                                    <input type="checkbox" class="">
                                    <span class=""></span>
                                </label>
                            </td>
                            <td class="text-center ctext-gc1-imp p-4">학기</td>
                            <td class="text-center ctext-gc1-imp p-4">과목</td>
                            <td class="ctext-gc1-imp p-4">단원/장명</td>
                            <td class="text-center ctext-gc1-imp p-4">파일 다운로드</td>
                        </tr>
                    </thead>
                    <tbody class="text-b-20px">
                        <tr class="border-bottom">
                            <td class="col-auto text-center bg-transparent p-4">
                                <label class="checkbox">
                                    <input type="checkbox" class="">
                                    <span class=""></span>
                                </label>
                            </td>
                            <td class="text-center ctext-gc1-imp bg-transparent p-4">1학기</td>
                            <td class="text-center ctext-gc1-imp bg-transparent p-4">국어</td>
                            <td class="bg-transparent p-4">[1단원] 재미가 톡톡톡</td>
                            <td class="text-center ctext-gc1-imp bg-transparent p-4">
                                <div class="w-center">
                                    <button
                                        class="btn rounded-pill text-sb-20px btn-light scale-bg-white border scale-bg-gray_01-hover py-2 px-3 all-center">
                                        <img src="{{ asset('images/download_icon.svg') }}" class="me-2">
                                        다운로드
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            {{-- padding 52px --}}
            <div>
                <div class="py-lg-4"></div>
                <div class="pt-lg-1"></div>
            </div>
            <div class="text-center">
                <button type="button" class="btn-ms-primary text-b-24px rounded-pill scale-text-white">
                    <svg height="17" viewBox="0 0 14 17" fill="none" xmlns="http://www.w3.org/2000/svg"
                        class="me-2">
                        <path d="M0 17H14V15H0M14 6H10V0H4V6H0L7 13L14 6Z" fill="white" />
                    </svg>
                    파일 다운로드</button>
            </div>
        </section>
        {{-- padding 160px --}}
        <div>
            <div class="py-lg-5"></div>
            <div class="py-lg-4"></div>
            <div class="pt-lg-3"></div>
        </div>

    </div>
@endsection
