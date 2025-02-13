@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title')
    학습영상관리
@endsection

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
<div class="col-12 pe-3 ps-3 position-relative">
    <div class="d-flex justify-content-end align-items-center mb-3 mt-3">
        <div>
            <span>전체 학습영상수:</span>
            <span id="lecturelist_sp_all_cnt">0</span>
        </div>
    </div>
    {{-- 통합검색 --}}
    <div class="row gap-2 px-3 mb-3">
        <span class="col-auto" style="line-height:38px">통합검색</span>
        <select id="lecture_sel_subject" class="form-select col-auto" style="width:auto;">
            <option value="">과목 선택</option>
            @if(!empty($subject_codes))
                @foreach($subject_codes as $subject_code)
                    <option value="{{$subject_code->id}}">{{$subject_code->code_name}}</option>
                @endforeach
            @endif
        </select>
        <select id="lecture_sel_series" class="form-select col-auto" style="width:auto;">
            <option value="">시리즈 선택</option>
            @if(!empty($series_codes))
                @foreach($series_codes as $series_code)
                    <option value="{{$series_code->id}}">{{$series_code->code_name}}</option>
                @endforeach
            @endif
        </select>
        <input id="lecture_inp_search" onkeyup="event.keyCode == 13? lectureSelect() : ''"
         type="text" class="col border" placeholder="(강좌명)검색어를 입력해주세요.">
        <button class="btn btn-outline-primary rounded col-auto" onclick="lectureSelect();">
            <span class="spinner-border spinner-border-sm" role="status" hidden> </span>
            검색
        </button>
    </div>
    {{-- 강좌관리/학습영상관리? 리스트 테이블 --}}
    <div>
        <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th> 분류 </th>
                    <th> 과목 </th>
                    <th> 시리즈 </th>
                    <th> 학년 </th>
                    <th> 강좌명 </th>
                    <th> 선생님 </th>
                    <th> 총강의수 </th>
                    <th> 강좌수준 </th>
                    <th> 수강기간 </th>
                    <th> 교재 </th>
                    <th> 썸네일 </th>
                    <th> 사용 </th>
                    <th> 기능 </th>
                </tr>
            </thead>
            <tbody id="lecturelist_tby_list">
                <tr class="tr_list">
                    <td data="#분류" class="course_names">
                        <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                    </td>
                    <td data="#과목" class="subject_names">
                        <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                    </td>
                    <td data="#시리즈" class="series_names">
                        <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                    </td>
                    <td data="#학년" class="grade_names">
                        <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                    </td>
                    <td data="#강좌명" class="lecture_name">
                        <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                    </td>
                    <td data="#선생님" class="teacher_name">
                        <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                    </td>
                    <td data="#총강의수" class="lecture_detail_count">
                        <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                    </td>
                    <td data="#강좌수준" class="level_names">
                        <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                    </td>
                    <td data="#수강기간" class="course_date_count">
                        <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                    </td>
                    <td data="#교재" class="book_name">
                        <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                    </td>
                    <td data="#썸네일" class="thumbnail_data">
                        <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                        <button class="btn btn-sm btn-outline-secondary rounded btn_preview " onclick="lectureThumbnailPreview(this);" hidden>미리보기</button>
                    </td>
                    <td data="#사용">
                        <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                        <input class="form-check-input is_use" type="checkbox" onchange="lectureUseChange(this);" hidden>
                    </td>
                    {{-- 상세 --}}
                    <td class="text-center align-middle">
                        <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                        <button class="btn btn-sm btn-outline-primary rounded btn_detail " onclick="lectureDetail(this);" hidden>상세/수정</button>
                    </td>
                    <input type="hidden" class="lecture_seq" value="">
                </tr>
            </tbody>
        </table>
    </div>

    {{-- 모달 / 섬네일 미리보기 --}}
    <div class="modal fade" id="lecutre_modal_thumbnail_preview" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">이미지 미리보기</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick=""></button>
                </div>
                <div class="modal-body">
                    <img src="" alt="" id="lecture_modal_thumbnail_preview_img" style="width: 100%;" hidden>
                </div>
                <div class="modal-footer">
                    <button type="button" class="modal_close btn btn-secondary" data-bs-dismiss="modal"
                        onclick="">닫기</button>
                    {{-- <button type="button" class="btn btn-primary" onclick=";">저장</button> --}}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    lectureSelect();

    // 강좌(학습영상) 리스트 SELECT
    function lectureSelect(lecture_seq){
        const search_str = document.querySelector('#lecture_inp_search').value;
        const subject_seq = document.querySelector('#lecture_sel_subject').value;
        const serise_seq = document.querySelector('#lecture_sel_series').value;

        const page = "/manage/lecture/list/select";
        const parameter = {
            search_str : search_str,
            lecture_seq : lecture_seq,
            subject_seq : subject_seq,
            serise_seq : serise_seq
        };
        queryFetch(page, parameter, function(result){
            if((lecture_seq||'') == '') 
                lectureListTrSet(result);
            else
                lectureTrSet(result); 
        });
    }
    // 업데이트시 리스트중 해당 강좌만 업데이트
    function lectureTrSet(result){

        if((result.resultCode||'') == 'success'){
            const tby_list = document.querySelector('#lecturelist_tby_list');
            const lecture = result.lectures[0];
            const trs = tby_list.querySelectorAll('.tr_'+lecture.id);
            if(trs.length == 0) return;
            const tr = trs[0];

            tr.querySelector('.lecture_seq').value = lecture.id;
            tr.querySelector('.course_names').innerHTML = lecture.course_names;
            tr.querySelector('.subject_names').innerHTML = lecture.subject_names;
            tr.querySelector('.series_names').innerHTML = lecture.series_names;
            tr.querySelector('.grade_names').innerHTML = lecture.grade_names;
            tr.querySelector('.lecture_name').innerHTML = lecture.lecture_name;
            tr.querySelector('.teacher_name').innerHTML = lecture.teacher_name;
            tr.querySelector('.lecture_detail_count').innerHTML = lecture.lecture_detail_count;
            tr.querySelector('.level_names').innerHTML = lecture.level_names;
            tr.querySelector('.course_date_count').innerHTML = lecture.course_date_count;
            tr.querySelector('.book_name').innerHTML = lecture.book_name;

            if((lecture.thumbnail_file_path||'') != ''){
                // 미리보기 버튼 활성화
                tr.querySelector('.btn_preview').hidden = false;
                // 썸네일 이미지
                tr.querySelector('.btn_preview').setAttribute('file_path', '/storage/'+lecture.thumbnail_file_path);
            }else{
                tr.querySelector('.btn_preview').hidden = true;
            }
            
            tr.querySelector('.is_use').checked = (lecture.is_use||'') == 'Y'? true : false;
            tr.querySelector('.is_use').hidden = false;
            tr.querySelector('.btn_detail').hidden = false;
            tr.querySelector('.lecture_seq').value = lecture.id;

        }
    }
    function lectureListTrSet(result){
        if((result.resultCode||'') == 'success'){
            //초기화
            const tby_list = document.querySelector('#lecturelist_tby_list');
            const tr_list = tby_list.querySelector('.tr_list').cloneNode(true);
            tby_list.innerHTML = '';
            tby_list.appendChild(tr_list);
            tr_list.hidden = true;
            document.querySelector('#lecturelist_sp_all_cnt').innerHTML = result.lectures.length;
            for(let i = 0; i < result.lectures.length; i++){
                const tr = tr_list.cloneNode(true);
                const lecture = result.lectures[i];
                tr.hidden = false;
                //로딩 삭제
                tr.querySelectorAll('.placeholder-glow').forEach(function(item){
                    item.remove();
                });
                tr.classList.add('tr_'+lecture.id);
                tr.querySelector('.lecture_seq').value = lecture.id;
                tr.querySelector('.course_names').innerHTML = lecture.course_names;
                tr.querySelector('.subject_names').innerHTML = lecture.subject_names;
                tr.querySelector('.series_names').innerHTML = lecture.series_names;
                tr.querySelector('.grade_names').innerHTML = lecture.grade_names;
                tr.querySelector('.lecture_name').innerHTML = lecture.lecture_name;
                tr.querySelector('.teacher_name').innerHTML = lecture.teacher_name;
                tr.querySelector('.lecture_detail_count').innerHTML = lecture.lecture_detail_count;
                tr.querySelector('.level_names').innerHTML = lecture.level_names;
                tr.querySelector('.course_date_count').innerHTML = lecture.course_date_count;
                tr.querySelector('.book_name').innerHTML = lecture.book_name;

                if((lecture.thumbnail_file_path||'') != ''){
                    // 미리보기 버튼 활성화
                    tr.querySelector('.btn_preview').hidden = false;
                    // 썸네일 이미지
                    tr.querySelector('.btn_preview').setAttribute('file_path', '/storage/'+lecture.thumbnail_file_path);
                }
                
                tr.querySelector('.is_use').checked = (lecture.is_use||'') == 'Y'? true : false;
                tr.querySelector('.is_use').hidden = false;
                tr.querySelector('.btn_detail').hidden = false;
                tr.querySelector('.lecture_seq').value = lecture.id;
                tby_list.appendChild(tr);
            }
        }
    }
    // 강좌(학습영상) 리스트 태그 삭제
    function lectureListTrRemove(lecture_seq){
        const tby_list = document.querySelector('#lecturelist_tby_list');
        const tr = tby_list.querySelector('.tr_'+lecture_seq);
        tr.remove();
    }

    // 강좌(학습영상) 사용여부 변경
    function lectureUseChange(vthis){
        const lecture_seq = vthis.closest('tr').querySelector('.lecture_seq').value;
        const is_use = vthis.checked? 'Y' : 'N';
        const page = "/manage/lecture/list/use/update";
        const parameter = {
            lecture_seq : lecture_seq,
            is_use : is_use
        };
        queryFetch(page, parameter, function(result){
            if((result.resultCode||'') == 'success'){
                toast('사용여부가 변경되었습니다.');
            }
        });
    }

    // 강좌(학습영상) 상세/수정
    function lectureDetail(vthis){
        //페이지 오픈 window opne
        const lecture_seq = vthis.closest('tr').querySelector('.lecture_seq').value;
        const page = "/manage/lecture/add?lecture_seq="+lecture_seq;
        window.open(page, '_blank');
    }
    
    // 강좌(학습영상) 썸네일 미리보기
    function lectureThumbnailPreview(vthis){
        //이미지 미리보기
        const file_path = vthis.getAttribute('file_path');
        const img = document.querySelector('#lecture_modal_thumbnail_preview_img');
        img.src = file_path;
        img.hidden = false;

        //모달 열기.
        const myModal = new bootstrap.Modal(document.getElementById('lecutre_modal_thumbnail_preview'), {
                keyboard: false
        });
        myModal.show();
    }

</script>

@endsection