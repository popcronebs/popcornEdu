@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title', '이벤트')
{{-- 학생 컨텐츠 --}}
@section('layout_coutent')
<link rel="stylesheet" href="{{ asset('css/reset.css') }}">
<div class="col mx-0 mb-3 pt-5 row position-relative">
    {{-- 상단 --}}
   <article class="d-flex pt-5 px-0">
    <div class="col-auto pb-3 mb-1 pt-4">
     <span class=" cfs-1">
        팝콘에듀의 생생한 이벤트<br>
        소식을 전해드립니다.
     </span>
    </div>
     <div class="col position-relative">
        <img src="{{ asset('images/event_character.svg') }}" class="bottom-0 end-0 position-absolute">
     </div>
    </article> 

    {{-- padding --}}
    <div>
        <div class="py-5"></div>
        <div class="pt-1"></div>
    </div>

    {{-- 이벤트 리스트 --}}
    <article>
        {{-- tab, search --}}
        <section class="pb-5 mb-1">
            <div class="row justify-content-between">
                <ul class="col-auto d-inline-flex gap-2 mb-3">
                    <li>
                        <button class="btn_event_tab btn-ms-primary text-sb-24px rounded-pill scale-text-white px-32 active" 
                        data="all" onclick="eventTab('all',this)">전체</button>
                    </li>
                    <li>
                        <button class="btn_event_tab btn-ms-primary text-sb-24px rounded-pill scale-bg-gray_01 scale-text-gray_05 scale-text-white-hover px-32"
                        data="process" onclick="eventTab('progress',this)">진행중</button>
                    </li>
                    <li>
                        <button class="btn_event_tab btn-ms-primary text-sb-24px rounded-pill scale-bg-gray_01 scale-text-gray_05 scale-text-white-hover px-32"
                        data="end" onclick="eventTab('end', this)">종료</button>
                    </li>
                </ul>
                <div class="col-auto">
                    <label class="label-search-wrap">
                        <input id="event_inp_search" type="text" class="lg-search border-gray rounded-pill text-m-20px" 
                        placeholder="공지사항을 검색해보세요." onkeyup="if(event.keyCode == 13) eventSearchList()">
                    </label>
                </div>
            </div>
        </section>

        {{-- 리스트 --}}
        <section>
            <div class="row row-cols-lg-3">
                @if(!empty($boards))
                    @foreach($boards as $board)
                <figure class="col fig_event_row">
                    <div>
                        <div class="rounded-4 scale-bg-gray_01 d-flex align-items-center justify-content-center overflow-hidden" style="height: 280px;">
                            <img src="{{ asset('storage')."/".$board->bdupfile_path }}" width="100%">
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-4">
                            <span class="col-auto fw-semibold cfs-5 title">{{ $board->title }}</span>
                            {{-- 날짜(start_date <= now <= end_date 이면 "진행중" 아니면 "마감" --}}
                            @if(substr($board->start_date, 0, 10) <= date('Y-m-d') && 
                            substr($board->end_date, 0, 10) >= date('Y-m-d'))
                                <span class="col-auto text-r-18px bg-danger text-white rounded-pill event_status" style="padding:6px 12px" data="progress">
                                    진행중
                                </span>
                            @else
                                <span class="col-auto text-r-18px bg-danger text-white rounded-pill event_status" style="padding:6px 12px" data="end">
                                    종료
                                </span>
                            @endif
                        </div>
                        <div class="pt-4">
                            <span class="scale-text-gray_05 text-r-20px start_date">{{ substr($board->start_date, 0, 10) }}</span>
                            ~
                            <span class="scale-text-gray_05 text-r-20px end_date">{{ substr($board->end_date, 0, 10) }}</span>
                        </div>
                    </div>
                </figure>
                    @endforeach
                @endif
            </div>
        </section>
    </article>
    {{-- pdding 160px --}}
    <div>
        <div class="py-5"></div>
        <div class="py-4"></div>
        <div class="py-2"></div>
    </div>
</div>
<script>
    // 이벤트 탭 (전체, 진행중, 종료)
    function eventTab(type, vthis){
        const btn_event_tab = document.querySelectorAll('.btn_event_tab');
        btn_event_tab.forEach(function(el, idx){
            el.classList.add('scale-bg-gray_01');
            el.classList.add('scale-text-gray_05');
            el.classList.add('scale-text-white-hover');
            el.classList.remove('scale-text-white');
            el.classList.remove('active');
        });
        vthis.classList.remove('scale-bg-gray_01');
        vthis.classList.remove('scale-text-gray_05');
        vthis.classList.remove('scale-text-white-hover');
        vthis.classList.add('scale-text-white');
        vthis.classList.add('active');

        const event_row = document.querySelectorAll('.fig_event_row');
        event_row.forEach(function(el, idx){
            if(type == 'all'){
                el.hidden = false;
            }else if(type == 'progress'){
                if(el.querySelector('.event_status').getAttribute('data') == 'progress'){
                    el.hidden = false;
                }else{
                    el.hidden = true;
                }
            }else if(type == 'end'){
                if(el.querySelector('.event_status').getAttribute('data') == 'end'){
                    el.hidden = false;
                }else{
                    el.hidden = true;
                }
            } 
        });
    }

    // 검색 하기.
    function eventSearchList(){
            // 검색어, 이베트 상태
            const search_word = document.getElementById('event_inp_search').value;
            const event_status = document.querySelector('.btn_event_tab.active').getAttribute('data');
            const event_row = document.querySelectorAll('.fig_event_row');
            event_row.forEach(function(el, idx){
                if(event_status == 'all'){
                    if(el.querySelector('.title').textContent.indexOf(search_word) > -1){
                        el.hidden = false;
                    }else{
                        el.hidden = true;
                    }
                }else if(event_status == 'progress'){
                    if(el.querySelector('.event_status').getAttribute('data') == 'progress'){
                        if(el.querySelector('.title').textContent.indexOf(search_word) > -1){
                            el.hidden = false;
                        }else{
                            el.hidden = true;
                        }
                    }else{
                        el.hidden = true;
                    }
                }else if(event_status == 'end'){
                    if(el.querySelector('.event_status').getAttribute('data') == 'end'){
                        if(el.querySelector('.title').textContent.indexOf(search_word) > -1){
                            el.hidden = false;
                        }else{
                            el.hidden = true;
                        }
                    }else{
                        el.hidden = true;
                    }
                }
            });
        }
</script>
@endsection