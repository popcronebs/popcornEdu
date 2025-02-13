@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title', '창의체험')

@section('add_css_js')
    <link href="{{ asset('css/reset.css?5') }}" rel="stylesheet">
@endsection

{{-- 학생 컨텐츠 --}}
@section('layout_coutent')
    <div class="col mx-0 mb-3 pt-5 row position-relative">
        {{-- 상단 --}}
        <article class="pt-5 px-0">
            <div class="row">
                <div class="col-auto pb-4">
                    <div class="h-center">
                        <img src="{{ asset('images/creative_experience_icon.svg?1') }}" width="72">
                        <span class="cfs-1 fw-semibold align-middle">창의체험</span>
                    </div>
                    <div class="pt-2 pb-3">
                        <span class="cfs-3 fw-medium">여러가지 학습을 한 눈에 쉽게 알아볼 수 있어요.</span>
                    </div>
                </div>
                <div class="col text-end position-relative">
                    <img class="position-absolute bottom-0 end-0" src="{{ asset('images/creative_experience_chick_character.svg') }}"
                        width="255">
                </div>
            </div>
        </article>

        {{-- padding 80px --}}
        <div>
            <div class="py-lg-4"></div>
            <div class="py-lg-3"></div>
        </div>

        <article>
            {{-- Content --}}
            <section>
                <div class="row">
                    <aside class="col-lg-3 px-0">
                        <div class="rounded modal-shadow-style">
                            <ul class="tab py-4 px-3">
                                <li class="mb-2">
                                    <button
                                        class="btn h-center w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                                        <img src="{{ asset('images/creative_experience_icon.svg') }}" width="32" class="me-2">
                                        창의체험 탐구생활
                                    </button>
                                </li>
                                <li class="mb-2">
                                    <button
                                        class="btn h-center w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                                        <img src="{{ asset('images/creative_experience_icon.svg') }}" width="32" class="me-2">
                                        과학 창의체험
                                    </button>
                                </li>
                                <li class="">
                                    <button
                                        class="btn h-center w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                                        <img src="{{ asset('images/creative_experience_icon.svg') }}" width="32" class="me-2">
                                        즐거운 창의체험
                                    </button>
                                </li>
                                <li class="">
                                    <button
                                        class="btn h-center w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                                        <img src="{{ asset('images/creative_experience_icon.svg') }}" width="32" class="me-2">
                                        스쿨랜드
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
                                    <span class="text-sb-24px">창의적인 탐구생활</span>
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
                        {{-- bundle list --}}
                        <div class="mt-2 d-flex flex-column gap-3" data-div-ability-bundle>
                            <div data-div-ability-row class="row rounded-3 scale-bg-gray_01 mx-0">
                                <div class="col-auto text-b-20px scale-text-gray_06 p-4">
                                    <div class="p-2">
                                        <span class="text-b-20px scale-text-gray_06">탐구1</span>
                                    </div>
                                </div>
                                <div class="col-auto h-center">
                                    <img src="{{ asset('images/bar_icon.svg') }}" width="2" height="12">
                                </div>
                                <div class="col h-center ps-5">
                                    <div class="scale-text-black text-b-20px ps-1">한 해를 잘 보내는 방법, 세시풍속과 24절기</div>
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
                        {{-- bundle video --}}
                        <div class="row row-cols-lg-3 mx-0 mt-2 gx-4">
                            <div class="col ps-0">
                                <div>
                                    {{-- video --}}
                                    <div style="width:100%;height:240px" class="bg-gc5 rounded-3"></div>
                                </div>
                                <div class="mt-3 pt-1">
                                    <div class="pb-2">
                                        <span class="text-b-20px scale-text-black">전현무 (MC. 아나운서)</span>
                                    </div>
                                    <div>
                                        <span class="text-b-20px scale-text-gray_05">전현무 아나운서의 영상을 보며 아나운서로써의...</span>
                                    </div>
                                </div>
                            </div>
                            

                            <div class="col">
                                <div>
                                    {{-- video --}}
                                    <div style="width:100%;height:240px" class="bg-gc5 rounded-3"></div>
                                </div>
                                <div class="mt-3 pt-1">
                                    <div class="pb-2">
                                        <span class="text-b-20px scale-text-black">전현무 (MC. 아나운서)</span>
                                    </div>
                                    <div>
                                        <span class="text-b-20px scale-text-gray_05">전현무 아나운서의 영상을 보며 아나운서로써의...</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col pe-0">
                                <div>
                                    {{-- video --}}
                                    <div style="width:100%;height:240px" class="bg-gc5 rounded-3"></div>
                                </div>
                                <div class="mt-3 pt-1">
                                    <div class="pb-2">
                                        <span class="text-b-20px scale-text-black">전현무 (MC. 아나운서)</span>
                                    </div>
                                    <div>
                                        <span class="text-b-20px scale-text-gray_05">전현무 아나운서의 영상을 보며 아나운서로써의...</span>
                                    </div>
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
