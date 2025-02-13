@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title')
    Sample 시간표 목록
@endsection

@section('layout_coutent')
@php
    if(!empty($codes_all)){
        $grade_codes = $codes_all->where('code_category', 'grade')->where('code_step', '=', 1);
    }
@endphp

{{-- 관리자 컨텐트 --}}
    <div class="col-12 pe-3 ps-3 position-relative">
        {{-- 상단 학년 메뉴 --}}
        <div class="row">
        @if (!empty($grade_codes))
            <ul id="sample_ul_top_grade_code" class="nav nav-tabs mt-2 text-center mb-3 col">
                @php $i = 0; @endphp
                @foreach ($grade_codes as $grade_code)
                    <li class="nav-item col-auto cursor-pointer">
                        <a class="code_tab nav-link {{ $i == 0 ? 'active':'' }}" onclick="sampleCodeTab(this)" code_seq="{{ $grade_code->id }}">
                            <span class="sp_loding spinner-border spinner-border-sm" aria-hidden="true" hidden> </span>
                            {{ $grade_code->code_name }}
                        </a>
                    </li>
                    @php $i++; @endphp
                @endforeach
            </ul>
        @endif
            <div class="col-auto text-end">
                <button class="btn btn-outline-secondary " onclick="timetableShowAdd();"> 시간표 등록 </button>
            </div>
        </div>
        {{-- 시간표 목록 --}}
        <div class="mt-3">
            <table class="table table-bordered text-center mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>제목</th>
                        <th>과목</th>
                        <th>학습요일</th>
                        <th>기능</th>
                    </tr>
                </thead>
                <tbody id="timetable_tby_timetable_group">
                    <tr class="copy_tr_timetable_group">
                        <td data="#제목" class="col timetable_group_title"> 
                            <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                        </td>
                        <td data="#과목" class="col timetable_group_subject"> 
                            <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                        </td>
                        <td data="#학습요일" class="col-auto timetable_group_days"> 
                            <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                            <div class="row p-0 m-0 div_timetable_days gap-1 justify-content-center" hidden>
                                <span class="col-auto" value="월">월</span>
                                <span class="col-auto" value="화">화</span>
                                <span class="col-auto" value="수">수</span>
                                <span class="col-auto" value="목">목</span>
                                <span class="col-auto" value="금">금</span>
                                <span class="col-auto" value="토">토</span>
                                <span class="col-auto" value="일">일</span>
                            </div>
                        </td>
                        <td data="#기능" class="col-auto w-auto">
                            <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                            <div class="row gap-2 div_timetable_group_btn justify-content-center" hidden>
                                <button class="col-auto btn btn-outline-primary btn-sm" onclick="timetableShowAdd(this)"> 편집 </button>
                                <button class="col-auto btn btn-outline-danger btn-sm"> 삭제 </button>
                                <select class="col-auto form-select form-select-sm w-auto ">
                                    <option value=""> 전체 </option>
                                </select>
                            </div>
                        </td>
                        <input type="hidden" class="timetable_group_seq">
                    </tr>
                </tbody>
            </table>
            {{-- Sample 시간표 목록이 없습니다. --}}
            <div class="text-center mt-4 mb-5" id="timetable_div_group_empty" hidden >
                <p class="fs-6 text-muted"> Sample 시간표 목록이 없습니다. </p>
            </div>
        </div>
        
        <div id="timetable_div_add" class="position-absolute w-100 h-100 bg-white" style="top: 0; left: 0; z-index:3" hidden>
            @include('admin.admin_sample_timetable_add')
        </div>
    </div>

    <script>
        timetableGroupSelect();

        // 상단 텝 클릭시
        function sampleCodeTab(vthis){
            //각 탭 활성화
            var codeTabs = document.querySelectorAll('#sample_ul_top_grade_code .code_tab');
            codeTabs.forEach(function(tab) {
                tab.classList.remove('active');
            });
            vthis.classList.add('active');

            //목록 없음 숨기기
            document.querySelector('#timetable_div_group_empty').hidden = true;

            // 시간표 그룹 목록 가져오기
            timetableGroupSelect();

        }

        // 시간표 그룹 목록 가져오기
        function timetableGroupSelect(){
            const grade_seq = document.querySelector('#sample_ul_top_grade_code .code_tab.active').getAttribute('code_seq');
            const page = '/manage/sample/timetable/group/select';
            const parameter = {
                grade_seq : grade_seq,
            };

            //로딩 보이기.
            const timetable_tby_timetable_group = document.querySelector('#timetable_tby_timetable_group');
            timetable_tby_timetable_group.querySelectorAll('.tr_timetable_group').forEach(function(vthis){
                vthis.remove();
            });
            timetable_tby_timetable_group.querySelector('.copy_tr_timetable_group').hidden = false;
                        
            queryFetch(page, parameter, function(result){
                //초기화 / 로딩 숨김
                const copy_tr_timetable_group = document.querySelector('.copy_tr_timetable_group').cloneNode(true);
                timetable_tby_timetable_group.innerHTML = '';
                timetable_tby_timetable_group.appendChild(copy_tr_timetable_group);
                copy_tr_timetable_group.hidden = true;

                if((result.resultCode||'') == 'success'){
                    for (let i = 0; i < result.timetable_groups.length; i++) {
                        const timetable_group = result.timetable_groups[i];
                        const tr = copy_tr_timetable_group.cloneNode(true);
                        tr.hidden = false;
                        tr.classList.remove('copy_tr_timetable_group');
                        tr.classList.add('tr_timetable_group');

                        //로딩바 제거
                        tr.querySelectorAll('.loding_place').forEach(function(vthis){
                            vthis.remove();
                        });
                        tr.querySelector('.timetable_group_seq').value = timetable_group.id;
                        tr.querySelector('.timetable_group_title').innerHTML = timetable_group.timetable_group_title;
                        tr.querySelector('.timetable_group_subject').innerHTML = timetable_group.subject_name || '';
                        tr.querySelector('.div_timetable_days').hidden = false;
                        const timetable_group_days = (timetable_group.timetable_group_days||'').split(',');
                        for (let j = 0; j < timetable_group_days.length; j++) {
                            const timetable_group_day = timetable_group_days[j];
                            if(timetable_group_day.length > 0)
                                tr.querySelector('.div_timetable_days').querySelector('span[value='+timetable_group_day+']').classList.add('bg-primary');
                        }
                        tr.querySelector('.div_timetable_group_btn').hidden = false;
                        timetable_tby_timetable_group.appendChild(tr);
                    }
                }
                //tr이 없으면 empty 보이기
                if(document.querySelectorAll('.tr_timetable_group').length < 1){
                    document.querySelector('#timetable_div_group_empty').hidden = false;
                }else{
                    document.querySelector('#timetable_div_group_empty').hidden = true;
                }
            });
        }
        // 시간표 등록 화면 보이기
        function timetableShowAdd(vthis){
            //vthis 있을 경우.
            if(vthis != undefined) 
            {
                const tr = vthis.closest('tr');
                const timetable_group_seq = tr.querySelector('.timetable_group_seq').value;
                const timetable_group_title = tr.querySelector('.timetable_group_title').innerText;
                timetableAddEditSetting(timetable_group_seq, timetable_group_title);
                
            }

            // 활성화 되어있는 탭의 code_seq 가져오기
            const code_seq = document.querySelector('#sample_ul_top_grade_code .code_tab.active').getAttribute('code_seq');
            document.querySelector('#timetable_add_grade_code').value = code_seq;;

            document.querySelector('#timetable_div_add').hidden = false;
        }
    </script>
@endsection
