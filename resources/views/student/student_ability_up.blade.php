@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title', '실력키우기')

@section('add_css_js')
    <link href="{{ asset('css/reset.css?5') }}" rel="stylesheet">
@endsection

{{-- 학생 컨텐츠 --}}
@section('layout_coutent')
    <div class="col mx-0 mb-3 pt-5 row position-relative">
        {{-- 상단 --}}
        <article class="pt-5 px-0">
            <div class="row">
                <div class="col-auto pb-3">
                    <div class="h-center">
                        <img src="{{ asset('images/ability_up_icon.svg?1') }}" width="72">
                        <span class="cfs-1 fw-semibold align-middle">실력키우기</span>
                    </div>
                    <div class="pt-2 pb-1">
                        <span class="cfs-3 fw-medium">여러가지 학습을 한 눈에 쉽게 알아볼 수 있어요.</span>
                    </div>
                </div>
                <div class="col text-end position-relative">
                    <img class="position-absolute bottom-0 end-0" src="{{ asset('images/graphic_character_skillup.svg') }}"
                        width="165">
                </div>
            </div>
        </article>

        {{-- padding 120px --}}
        <div>
            <div class="py-lg-5"></div>
            <div class="pt-lg-4"></div>
        </div>

        <article>
            <section>
                {{-- Tab --}}
                {{-- 방학을 알차게, 학교 예/복습, 한자, 한국사, 심화, 영상, 웹툰, 한국사능력 검정시험 --}}
                <div class="row mx-0 mb-5">
                    <ul class="col d-inline-flex gap-2 mb-3 px-0">
                        <li>
                            <button type="button"
                                class="btn-ms-primary text-sb-24px rounded-pill scale-text-white px-32">방학을 알차게</button>
                        </li>
                        <li>
                            <button type="button"
                                class="btn-ms-primary text-sb-24px rounded-pill scale-bg-gray_01 px-32 scale-text-gray_05 scale-text-white-hover">학교
                                예/복습</button>
                        </li>
                        <li>
                            <button type="button"
                                class="btn-ms-primary text-sb-24px rounded-pill scale-bg-gray_01 px-32 scale-text-gray_05 scale-text-white-hover">한자</button>
                        </li>
                        <li>
                            <button type="button"
                                class="btn-ms-primary text-sb-24px rounded-pill scale-bg-gray_01 px-32 scale-text-gray_05 scale-text-white-hover">한국사</button>
                        </li>
                        <li>
                            <button type="button"
                                class="btn-ms-primary text-sb-24px rounded-pill scale-bg-gray_01 px-32 scale-text-gray_05 scale-text-white-hover">심화</button>
                        </li>
                        <li>
                            <button type="button"
                                class="btn-ms-primary text-sb-24px rounded-pill scale-bg-gray_01 px-32 scale-text-gray_05 scale-text-white-hover">영상</button>
                        </li>
                        <li>
                            <button type="button"
                                class="btn-ms-primary text-sb-24px rounded-pill scale-bg-gray_01 px-32 scale-text-gray_05 scale-text-white-hover">웹툰</button>
                        </li>
                        <li>
                            <button type="button"
                                class="btn-ms-primary text-sb-24px rounded-pill scale-bg-gray_01 px-32 scale-text-gray_05 scale-text-white-hover">한국사능력
                                검정시험</button>
                        </li>
                    </ul>
                </div>
            </section>

            {{-- Content --}}
            <section>
                <div class="row">
                    <aside class="col-lg-3 px-0">
                        <div class="rounded modal-shadow-style">
                            <ul class="tab py-4 px-3">
                                <li class="mb-2">
                                    <button
                                        class="btn h-center w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                                        <img src="{{ asset('images/pencil_icon.svg') }}" width="32" class="me-2">
                                        여름방학 생활
                                    </button>
                                </li>
                                <li class="mb-2">
                                    <button
                                        class="btn h-center w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                                        <img src="{{ asset('images/pencil_icon.svg') }}" width="32" class="me-2">
                                        학습포인트 순위
                                    </button>
                                </li>
                                <li class="">
                                    <button
                                        class="btn h-center w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                                        <img src="{{ asset('images/pencil_icon.svg') }}" width="32" class="me-2">
                                        학습포인트 순위
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </aside>
                    <div class="col modal-shadow-style p-4 ms-3">
                        {{-- 32px, 24px --}}
                        <div class="row mt-2 mb-4">
                            <div class="col text-start d-flex gap-2">
                                <span class="text-sb-24px">
                                    <span class="text-sb-24px">3</span>학년
                                </span>
                                <button class="btn p-0 h-center">
                                    <img src="https://sdang.acaunion.com/images/yellow_round_arrow.svg" width="24">
                                </button>
                            </div>
                            <div class="col text-end">
                                <span class="text-danger text-sb-20px">
                                    총 <span class="text-danger text-sb-20px">3</span> 개
                                </span><span class="text-sb-20px">의 수업이 있습니다.</span>
                            </div>
                        </div>
                        {{-- bundle --}}
                        <div class="mt-2 d-flex flex-column gap-3" data-div-ability-bundle>
                            <div data-div-ability-row class="row rounded-3 scale-bg-gray_01">
                                <div class="col-auto text-b-20px scale-text-gray_06 p-4">
                                    <div class="p-2">
                                        <span class="text-b-20px scale-text-gray_06">1</span>장
                                    </div>
                                </div>
                                <div class="col-auto h-center">
                                    <img src="{{ asset('images/bar_icon.svg') }}" width="2" height="12">
                                </div>
                                <div class="col h-center ps-5">
                                    <div class="scale-text-black text-b-20px ps-1">어서와, 거미의 세계로</div>
                                </div>
                                <div class="col-auto h-center pe-5">
                                    <button
                                        class="btn rounded-pill text-sb-20px btn-light scale-bg-white border scale-bg-gray_01-hover py-2 px-3 all-center me-3">
                                        <img src="{{ asset('images/black_arrow_right_notail.svg') }}">
                                        학습하기
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </article>
        {{-- padding 160px --}}
        <div>
            <div class="py-lg-5"></div>
            <div class="py-lg-4"></div>
            <div class="pt-lg-3"></div>
        </div>


    </div>
@endsection
