@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title', '공통 디자인 관리')

{{-- 네브바 체크 --}}
@section('design', 'active')


{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
    <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>

    <style>
        #design_tb_basic_info th {
            background: #f8f9fa;
            vertical-align: middle;
            padding-left: 15px;
        }

        #design_tb_basic_info td {
            vertical-align: middle;
        }
    </style>
    <div class="col-12 pe-3 ps-3 position-relative">
        <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
            <h4>공통 디자인 관리</h4>
        </div>

        {{-- 디자인 관련 관리 [추가 코드]--}}
        {{-- 기본정보 입력. --}}
        <div>
            <h6>기본정보입력</h6>
            <div>
                <table id="design_tb_basic_info" class="table table-bordered">
                    <tr>
                        <th class="col-2">회사명</th>
                        <td class="col">
                            <input type="text" class="col-3 company_name"
                            value="{{ $designs->where('design_code', 'company_name')->first()->design_value }}">
                        </td>
                    </tr>
                    <tr>
                        <th class="col-2">대표자명</th>
                        <td class="col">
                            <input type="text" class="col-3 ceo_name"
                            value="{{ $designs->where('design_code', 'ceo_name')->first()->design_value }}">
                        </td>
                    </tr>
                    <tr>
                        <th class="col-2">주소</th>
                        <td class="col">
                            <div>
                                <div class="d-flex gap-2">
                                    <input type="text" class="zip_code col-3"
                                    value="{{ $designs->where('design_code', 'zip_code')->first()->design_value }}">
                                    <a href="javascript:execDaumPostcode();" class="pt-1">우편번호 찾기</a>
                                </div>
                                <div id="address_wrap" style="display:none;border:1px solid;width:500px;height:300px;margin:5px 0;position:relative">
                                    <img src="//t1.daumcdn.net/postcode/resource/images/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" onclick="foldDaumPostcode()" alt="접기 버튼">
                                </div>
                                <div class="d-flex gap-2 mt-1">
                                    <input type="text" class="col-4 address"
                                    value="{{ $designs->where('design_code', 'address')->first()->design_value }}">
                                    <input type="text" class="col-3 address_detail"
                                    value="{{ $designs->where('design_code', 'address_detail')->first()->design_value }}">
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="col-2">학원설립<br>운영등록번호</th>
                        <td class="col">
                            <div class="d-flex gap-2">

                                <input type="text" class="col-3 academy_number"
                                value="{{ $designs->where('design_code', 'academy_number')->first()->design_value }}">
                                {{-- academy_number_file 가져오기 --}}
                                @php $academy_number_file = $designs->where('design_code', 'academy_number_file')->first()->design_value @endphp
                                <div class="div_academy_number_file input-group w-50 hpx-30" {{ strlen($academy_number_file) > 0 ? 'hidden':'' }}>
                                    <input type="file" class="academy_number_file form-control p-0 ps-2 pt-1"
                                        id="design_inp_findfile2" aria-describedby="design_btn_findfile2"
                                        aria-label="Upload">
                                    <button class="btn btn-primary btn-sm" type="button"
                                        onclick="document.querySelector('#design_inp_findfile2').click();">찾아보기</button>
                                </div>
                                
                                <span class="sp_div_academy_number_file badge p-2 align-items-center text-primary-emphasis bg-primary-subtle rounded-pill seq0 
                                {{ strlen($academy_number_file) > 0 ? 'd-flex':'' }}" 
                                hidden="">
                                    <span class="px-1">{{ $academy_number_file }}</span>
                                    <a href="javascript:designFileCancel('academy_number')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"></path>
                                          </svg>
                                    </a>
                                  </span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="col-2">신고기관명</th>
                        <td class="col">
                            <input type="text" class="col-3 academy_number_agency"
                            value="{{ $designs->where('design_code', 'academy_number_agency')->first()->design_value }}">
                        </td>
                    </tr>
                    <tr>
                        <th class="col-2">사업자등록번호</th>
                        <td class="col">
                            <input type="text" class="col-3 business_number"
                            value="{{ $designs->where('design_code', 'business_number')->first()->design_value }}">
                        </td>
                    </tr>
                    <tr>
                        <th class="col-2">통신판매업신고번호</th>
                        <td class="col">
                            <div class="d-flex gap-2">
                                {{-- 통신판매업신고번호 --}}
                                <input type="text" class="col-3 sales_number"
                                value="{{ $designs->where('design_code', 'sales_number')->first()->design_value }}">

                                @php $sales_number_file = $designs->where('design_code', 'sales_number_file')->first()->design_value @endphp
                                <div class="div_sales_number_file input-group w-50 hpx-30" {{ strlen($sales_number_file) > 0 ? 'hidden':'' }} >
                                    <input type="file" class="sales_number_file form-control p-0 ps-2 pt-1" id="design_inp_findfile1"
                                        aria-label="Upload">
                                    <button class="btn btn-primary btn-sm" type="button"
                                        onclick="document.querySelector('#design_inp_findfile1').click();">찾아보기</button>
                                </div>

                                <span class="sp_sales_number_file badge p-2 align-items-center text-primary-emphasis bg-primary-subtle rounded-pill seq0 
                                {{ strlen($sales_number_file) > 0 ? 'd-flex':'' }}" 
                                hidden="">
                                    <span class="px-1">{{ $sales_number_file }}</span>
                                    <a href="javascript:designFileCancel('sales_number')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"></path>
                                          </svg>
                                    </a>
                                  </span>

                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="col-2">신고기관명</th>
                        <td class="col">
                            <input type="text" class="col-3 sales_number_agency"
                            value="{{ $designs->where('design_code', 'sales_number_agency')->first()->design_value }}">
                        </td>
                    </tr>
                    <tr>
                        <th class="col-2">부가통신사업신고번호</th>
                        <td class="col">
                            <input type="text" class="col-3 additional_number"
                            value="{{ $designs->where('design_code', 'additional_number')->first()->design_value }}">
                        </td>
                    </tr>
                    <tr>
                        <th class="col-2">호스팅서비스제공자</th>
                        <td class="col">
                            <input type="text" class="col-3 hosting_service"
                            value="{{ $designs->where('design_code', 'hosting_service')->first()->design_value }}">
                        </td>
                    </tr>
                    <tr>
                        <th class="col-2">대표번호</th>
                        <td class="col">
                            <input type="text" class="col-3 representative_number"
                            value="{{ $designs->where('design_code', 'representative_number')->first()->design_value }}">
                        </td>
                    </tr>
                    <tr>
                        <th class="col-2">대표문의</th>
                        <td class="col">
                            <input type="text" class="col-3 representative_inquiry"
                            value="{{ $designs->where('design_code', 'representative_inquiry')->first()->design_value }}">
                        </td>
                    </tr>
                    <tr>
                        <th class="col-2">제휴문의</th>
                        <td class="col">
                            <input type="text" class="col-3 alliance_inquiry"
                            value="{{ $designs->where('design_code', 'alliance_inquiry')->first()->design_value }}">
                        </td>
                    </tr>
                </table>
                <div class="text-center">
                    <button class="btn btn-primary col-1" id="design_btn_normal_info_insert" onclick="designNormalSave();">
                        <span class="sp_loding spinner-border spinner-border-sm" aria-hidden="true" hidden=""></span>
                        저장
                    </button>
                    {{-- <button class="btn btn-secondary col-1">취소</button> ? --}}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // 기본정보 저장
        function designNormalSave(){
            const basic_info = document.querySelector('#design_tb_basic_info');
            const company_name = basic_info.querySelector('.company_name').value;
            const ceo_name = basic_info.querySelector('.ceo_name').value;
            const zip_code = basic_info.querySelector('.zip_code').value;
            const address = basic_info.querySelector('.address').value;
            const address_detail = basic_info.querySelector('.address_detail').value;
            const academy_number = basic_info.querySelector('.academy_number').value;
            const academy_number_file = basic_info.querySelector('.academy_number_file').files[0];
            const academy_number_agency = basic_info.querySelector('.academy_number_agency').value;
            const business_number = basic_info.querySelector('.business_number').value;
            const sales_number = basic_info.querySelector('.sales_number').value;
            const sales_number_file = basic_info.querySelector('.sales_number_file').files[0]; 
            const sales_number_agency = basic_info.querySelector('.sales_number_agency').value;
            const additional_number = basic_info.querySelector('.additional_number').value;
            const hosting_service = basic_info.querySelector('.hosting_service').value;
            const representative_number = basic_info.querySelector('.representative_number').value;
            const representative_inquiry = basic_info.querySelector('.representative_inquiry').value;
            const alliance_inquiry = basic_info.querySelector('.alliance_inquiry').value;

            const page = "/manage/design/basicinsert";
            let formData = new FormData();
            formData.append('company_name', company_name);
            formData.append('ceo_name', ceo_name);
            formData.append('zip_code', zip_code);
            formData.append('address', address);
            formData.append('address_detail', address_detail);
            formData.append('academy_number', academy_number);  
            formData.append('academy_number_file', academy_number_file);
            formData.append('academy_number_agency', academy_number_agency);
            formData.append('business_number', business_number);
            formData.append('sales_number', sales_number);
            formData.append('sales_number_file', sales_number_file);
            formData.append('sales_number_agency', sales_number_agency);
            formData.append('additional_number', additional_number);
            formData.append('hosting_service', hosting_service);
            formData.append('representative_number', representative_number);
            formData.append('representative_inquiry', representative_inquiry);
            formData.append('alliance_inquiry', alliance_inquiry);

            document.querySelector('#design_btn_normal_info_insert .sp_loding').hidden = false;

            queryFormFetch(page, formData, function(result){
                if(result == null || result.resultCode == null) return;

                if(result.resultCode == 'success')
                {
                    sAlert('','저장되었습니다.', 1, function(){
                        location.reload();
                    });
                }
                else
                {
                    sAlert('','저장에 실패하였습니다.');
                }
                
            })

        }

        // 첨부 파일취소
        function designFileCancel(file_type){
            const basic_info = document.querySelector('#design_tb_basic_info');
            sAlert('파일취소', '업로드한 파일을 취소하고 재업로드를 진행하시겠습니까? 저장과 상관없이 파일이 삭제됩니다.', 2, function(){
                if(file_type == 'sales_number')
                {
                    designFileDelete('sales_number_file');
                    basic_info.querySelector('.sales_number_file').value = '';
                    basic_info.querySelector('.div_sales_number_file').hidden = false;
                    basic_info.querySelector('.sp_sales_number_file').classList.remove('d-flex');
    
                }
                else if(file_type == 'academy_number')
                {
                    designFileDelete('academy_number_file');
                    basic_info.querySelector('.academy_number_file').value = '';
                    basic_info.querySelector('.div_academy_number_file').hidden = false;
                    basic_info.querySelector('.sp_div_academy_number_file').classList.remove('d-flex');
    
                }
            })
        }

        // 첨부 파일 삭제
        function designFileDelete(design_code){
            const page = "/manage/design/filedelete";
            const parameter = {
                design_code : design_code
            }
            queryFetch(page, parameter, function(result){
                if(result == null || result.resultCode == null) return;

                if(result.resultCode == 'success')
                {
                    toast('삭제되었습니다.');
                }
                else
                {
                    toast('삭제에 실패하였습니다.');
                }
            });
        }


        //우편번호찾기. 숨기기 / 가져오기
        var element_wrap = document.getElementById('address_wrap');
        function foldDaumPostcode() {
        // iframe을 넣은 element를 안보이게 한다.
            element_wrap.style.display = 'none';
        }
        function execDaumPostcode() {
        // 현재 scroll 위치를 저장해놓는다.
            const basic_info = document.querySelector('#design_tb_basic_info');
            var currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
            new daum.Postcode({
                oncomplete: function(data) {
                    var addr = ''; // 주소 변수
                    var extraAddr = ''; // 참고항목 변수
                    //사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
                    if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
                        addr = data.roadAddress;
                    } else { // 사용자가 지번 주소를 선택했을 경우(J)
                        addr = data.jibunAddress;
                    }

                    if(data.userSelectedType === 'R'){
                        // 법정동명이 있을 경우 추가한다. (법정리는 제외)
                        // 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
                        if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
                            extraAddr += data.bname;
                        }
                        // 건물명이 있고, 공동주택일 경우 추가한다.
                        if(data.buildingName !== '' && data.apartment === 'Y'){
                            extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                        }
                        // 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
                        if(extraAddr !== ''){
                            extraAddr = ' (' + extraAddr + ')';
                        }
                        // 조합된 참고항목을 해당 필드에 넣는다.
                        basic_info.querySelector(".address_detail").value = extraAddr;
                    } else {
                        basic_info.querySelector(".address_detail").value = '';
                    }

                    // 우편번호와 주소 정보를 해당 필드에 넣는다.
                    basic_info.querySelector(".zip_code").value = data.zonecode;
                    basic_info.querySelector(".address").value = addr;
                    // 커서를 상세주소 필드로 이동한다.
                    basic_info.querySelector(".address_detail").focus();

                    // iframe을 넣은 element를 안보이게 한다.
                    // (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
                    element_wrap.style.display = 'none';

                    // 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
                    document.body.scrollTop = currentScroll;
                },
                // 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분. iframe을 넣은 element의 높이값을 조정한다.
                onresize : function(size) {
                    let sheight = size.height;
                    if(size.height > 400)
                        sheight = 400;
                    element_wrap.style.height = sheight+'px';
                },
                width : '100%',
                height : '100%'
            }).embed(element_wrap);

            // iframe을 넣은 element를 보이게 한다.
            element_wrap.style.display = 'block';
        }

    </script>
@endsection
