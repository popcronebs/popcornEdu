@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title', '교재')
{{-- 학생 컨텐츠 --}}
@section('layout_coutent')
<div class="col mx-0 mb-3 pt-5 row position-relative">
    {{-- 상단 --}}
   <article class="pt-5 px-0">
        <div class="row">
            <div class="col-auto">
                <div>
                    <img src="{{ asset('images/book_icon.svg')}}" width="72" class="pt-2">
                    <span class="cfs-1 fw-semibold align-middle">추천 교재</span>
                </div>
                <div class="pt-2">
                    <span class="cfs-3 fw-medium">팝콘에듀에서 교재를 추천드려요.</span>
                </div>
            </div>
            <div class="col text-end">
                <div class="pt-5">
                    <div class="d-inline-block select-wrap select-icon" style="min-width:200px">
                        <select class="rounded-pill border-gray lg-select text-sb-24px ps-4" style="min-width:200px">
                          <option value="">학년선택</option>
                          @if(!empty($grade_codes))
                            @foreach($grade_codes as $grade_code)
                                <option value="{{ $grade_code->id }}">{{ $grade_code->code_name }}</option>
                            @endforeach
                          @endif
                        </select>
                      </div>
                </div>
            </div>
       </div>
   </article>

   {{-- padding 120 --}}
   <div>
        <div class="py-5"></div>
        <div class="pt-4"></div>
   </div>

   {{-- 책 리스트 --}}
    <article>
        <div class="row row-cols-4">
            <div class="col pe-lg-4 me-lg-2">
                <div class="pe-lg-5 me-lg-5">
                    <div class="scale-bg-gray_01 d-flex align-items-center justify-content-center overflow-hidden">
                        <img src="{{ asset('images/book_test_img.png') }}" width="100%">
                    </div>
                    <div class="pt-4">
                        <span class="text-m-24px">4주 완성 독해력</span>
                    </div>
                    <div class="pt-4">
                        <button class="btn btn-primary-y text-r-18px rounded-pill px-3">
                            구매하러가기
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </article>
    {{-- pdding 160px --}}
    <div>
        <div class="py-5"></div>
        <div class="py-4"></div>
        <div class="py-2"></div>
    </div>
</div>
@endsection