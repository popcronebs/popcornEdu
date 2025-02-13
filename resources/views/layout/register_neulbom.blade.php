{{-- 로그인 페이지 --}}
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="{{ asset('css/bootstrap.css?8') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.15.5/xlsx.full.min.js"></script> --}}
    <link href="{{ asset('css/admin_style.css?28') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('font/font.css?1') }}">
    <link rel="stylesheet" href="{{ asset('css/neulbom.css?1') }}">

    <link rel="stylesheet" as="style" crossorigin href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/static/pretendard.min.css" />
    <link rel="stylesheet" href="{{ asset('css/reset.css?5') }}">

    <script src="{{ asset('js/admin_script.js?19') }}"></script>
    <title>팝콘 로그인</title>
</head>
<body>
    <input type="hidden" id="csrf_token" value="{{ csrf_token() }}" />
    <div id="toast" style=" font-size: 1.5rem; padding: 1.1rem; "></div>
    <main>
        <div class="register_neulbom_layout">
            <div class="register_neulbom_layout_left">
                <div class="register_neulbom_layout_left_top_wrap">
                    <div class="register_neulbom_layout_left_top">
                        <img src="{{ asset('images/svg/neubom_logo.svg') }}" alt="책방 로고">
                    </div>
                    <div class="cross_wrap">
                        <div class="cross_line">
                            <span class="cross_line_1"></span>
                            <span class="cross_line_2"></span>
                        </div>
                    </div>
                    <div class="register_neulbom_layout_right_top">
                        <img src="{{ asset('images/svg/popcorn_neubom_logo.svg') }}" alt="팝콘 로고">
                    </div>
                </div>
                <form action="/register/neulbom" method="post" id="registerForm" onsubmit="return joinChk()">
                    @csrf
                    <div class="register_neulbom_layout_left_form">
                        <h2>회원가입</h2>
                        <input type="hidden" name="is_register" value="Y">
                        <div class="form_group ">
                            <div class="input_wrap_wrap col-wrap-2">
                                <div class="input_wrap">
                                    <input type="text" id="id" name="id" required placeholder="" onkeyup="idStatusClear();">
                                    <label for="id"><span class="required">*</span>아이디</label>
                                </div>
                                <button type="button" class="check_duplicate_btn" onclick="useraddUserIdCheck(this)">중복확인</button>
                            </div>
                            <div class="info_check id_check_wrap">
                                <span class="check_fail check_status id_duplicate" hidden>아이디가 중복되었습니다.</span>
                                <span class="check_fail check_status id_duplicate_chk" hidden>아이디 중복체크를 확인해주세요</span>
                                <span class="check_success check_status id_ok" hidden>사용 가능한 아이디입니다.</span>
                                <input type="hidden" id="inp_status_id">
                            </div>
                        </div>
                        <div class="form_group">
                            <div class="input_wrap">
                                <input type="password" id="password" name="password" required placeholder=" " onkeyup="pwChk();">
                                <label for="password"><span class="required">*</span>비밀번호</label>
                                <input type="hidden" id="inp_status_pw">
                            </div>
                            <div class="info_check password_check_wrap">
                                <span class="check_fail check_status pw_8" hidden>비밀번호는 8자 이상으로 입력해주세요.</span>
                            </div>
                        </div>
                        <div class="form_group">
                            <div class="input_wrap">
                                <input type="password" id="password_confirm" name="password_confirm" required placeholder=" " onkeyup="pwChk();">
                                <label for="password_confirm"><span class="required">*</span>비밀번호 확인</label>
                            </div>
                            <div class="info_check password_check_wrap">
                                <span class="check_fail check_status pw_no_match" hidden>비밀번호가 일치하지 않습니다.</span>
                            </div>
                        </div>
                        <div class="form_group">
                            <div class="input_wrap">
                                <input type="text" id="name" name="name" required placeholder=" ">
                                <label for="name"><span class="required">*</span>선생님 성함</label>
                            </div>
                            <div class="info_check name_check_wrap">
                                <span class="check_fail check_status" hidden>선생님 성함을 입력해주세요.</span>
                            </div>
                        </div>
                        <div class="form_group">
                            <div class="input_wrap_wrap col-wrap-2">
                                <div class="input_wrap">
                                    <input type="text" id="phone" name="phone" required placeholder=" " onkeyup="phoneStatusClear();">
                                    <label for="phone"><span class="required">*</span>휴대폰번호</label>
                                </div>
                                <button type="button" class="check_duplicate_btn p-2" onclick="sendPhoneAuth();">인증번호 발송</button>
                            </div>
                            <div class="info_check phone_check_wrap1">
                                <span class="check_success check_status suc" hidden>전송되었습니다.</span>
                                <span class="check_fail check_status fail" hidden>전송되지 않았습니다. 다시 시도해주세요.</span>
                                <input type="hidden" class="inp_status_phone1" value="">
                            </div>
                        </div>
                        {{-- 인증번호 발송 버튼을 클릭하고나면 나오는 인증번호 입력 폼 --}}
                        <div class="form_group div_phone_auth" hidden>
                            <div class="input_wrap_wrap col-wrap-2">
                                <div class="input_wrap">
                                    <input type="text" id="phone_auth" name="phone_auth" required placeholder=" ">
                                    <label for="phone_auth"><span class="required">*</span>인증번호</label>
                                </div>
                                <button type="button" class="check_duplicate_btn p-2" onclick="phoneAuth();">인증번호 확인</button>
                            </div>
                            <div class="info_check phone_check_wrap2">
                                <span class="check_success check_status suc" hidden>인증되었습니다.</span>
                                <span class="check_fail check_status fail" hidden>인증되지 않았습니다.</span>
                                <input type="hidden" class="inp_status_phone2" value="">
                            </div>
                        </div>
                        <div class="form_group">
                            <div class="input_wrap_wrap col-wrap-2">
                                <div class="input_wrap">
                                    <input type="email" id="email" name="email" required placeholder=" " onkeyup="emailStatusClear();">
                                    <label for="email"><span class="required">*</span>이메일</label>
                                </div>
                                <button type="button" class="check_duplicate_btn p-2" onclick="sendMailAuth();">인증번호 발송</button>
                            </div>
                            <div class="info_check email_check_wrap1">
                                <span class="check_success check_status suc" hidden>전송되었습니다.</span>
                                <span class="check_fail check_status fail" hidden>전송되지 않았습니다. 다시 시도해주세요.</span>
                                <input type="hidden" class="inp_status_email1" value="">
                            </div>
                        </div>
                        {{-- 인증번호 발송 버튼을 클릭하고나면 나오는 인증번호 입력 폼 --}}
                        <div class="form_group div_email_auth" hidden>
                            <div class="input_wrap_wrap col-wrap-2">
                                <div class="input_wrap">
                                    <input type="text" id="email_auth" name="email_auth" required placeholder=" ">
                                    <label for="email_auth"><span class="required">*</span>이메일 인증번호</label>
                                </div>
                                <button type="button" class="check_duplicate_btn p-2" onclick="mailAuth();">인증번호 확인</button>
                            </div>
                            <div class="info_check email_check_wrap2">
                                <span class="check_success check_status suc" hidden>인증되었습니다.</span>
                                <span class="check_fail check_status fail" hidden>인증되지 않았습니다.</span>
                                <input type="hidden" class="inp_status_email2" value="">
                            </div>
                        </div>
                        <div class="suggestions">
                        <div class="suggestions_wrap d-flex flex-column gap-3">
                            <div class="suggestions_wrap_all">
                                <div class="all_agree_wrap mb-3">
                                    <input type="checkbox" id="all_agree" name="all_agree">
                                    <label for="all_agree">전체 동의</label>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="suggestions_wrap_1">
                                        <input type="checkbox" id="agree_terms" name="agree_terms" required>
                                        <label for="agree_terms"><span class="required">(필수)</span>서비스 이용 약관</label>
                                    </div>
                                    <div class="suggestions_wrap_2">
                                        <span data-bs-toggle="modal" data-bs-target="#termsModal" style="cursor: pointer;">보기</span>
                                    </div>
                                </div>
                            </div>
                            <div class="suggestions_wrap_2">
                                <div class="d-flex justify-content-between">
                                    <div class="suggestions_wrap_2">
                                        <input type="checkbox" id="agree_privacy" name="agree_privacy" required>
                                        <label for="agree_privacy"><span class="required">(필수)</span>개인정보 수집 및 이용 동의</label>
                                    </div>
                                    <div class="suggestions_wrap_2">
                                        <span data-bs-toggle="modal" data-bs-target="#privacyModal" style="cursor: pointer;">보기</span>
                                    </div>
                                </div>
                            </div>
                            <div class="suggestions_wrap_3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="suggestions_wrap_3">
                                        <input type="checkbox" id="marketing_agree" name="marketing_agree">
                                        <label for="marketing_agree">(선택)광고성 정보 수신</label>
                                    </div>
                                    <div class="suggestions_wrap_3">
                                        <span data-bs-toggle="modal" data-bs-target="#marketingModal" style="cursor: pointer;">보기</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        <button type="submit" class="submit_btn">회원 가입</button>
                    </div>
                    {{-- 이용약관동의 --}}

                </form>
            </div>
            <div class="register_neulbom_layout_right d-none">

            </div>
        </div>

        <!-- 서비스 이용약관 모달 -->
        <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="termsModalLabel">서비스 이용약관</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                        <p>제1조 (목적)<br>
                        이 약관은 늘봄책방(이하 "회사")이 제공하는 서비스의 이용조건 및 절차, 회사와 회원 간의 권리, 의무 및 책임사항 등을 규정함을 목적으로 합니다.</p>
                        <p>제2조 (약관의 효력 및 변경)<br>
                        1. 이 약관은 서비스를 이용하고자 하는 모든 회원에 대하여 그 효력을 발생합니다.<br>
                        2. 회사는 약관의 규제에 관한 법률 등 관련법을 위배하지 않는 범위에서 이 약관을 개정할 수 있습니다.</p>
                        <p>제3조 (용어의 정의)<br>
                        이 약관에서 사용하는 용어의 정의는 다음과 같습니다.<br>
                        1. "서비스"라 함은 회사가 제공하는 모든 서비스를 의미합니다.<br>
                        2. "회원"이라 함은 회사와 서비스 이용계약을 체결하고 회원 아이디를 부여받은 자를 의미합니다.</p>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="button" class="btn btn-primary btn-yellow w-100" data-bs-dismiss="modal">확인</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 개인정보 수집 및 이용 동의 모달 -->
        <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="privacyModalLabel">개인정보 수집 및 이용 동의</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                        <p>1. 수집하는 개인정보 항목<br>
                        - 필수항목: 이름, 휴대폰번호, 이메일<br>
                        - 선택항목: 마케팅 수신 동의</p>
                        <p>2. 개인정보의 수집 및 이용목적<br>
                        - 서비스 제공에 관한 계약 이행 및 서비스 제공에 따른 요금정산<br>
                        - 회원 관리: 회원제 서비스 이용에 따른 본인확인, 개인 식별, 불량회원의 부정 이용 방지와 비인가 사용 방지, 가입 의사 확인, 연령확인, 불만처리 등 민원처리, 고지사항 전달</p>
                        <p>3. 개인정보의 보유 및 이용기간<br>
                        - 회원탈퇴 시까지<br>
                        - 단, 관계법령의 규정에 의하여 보존할 필요가 있는 경우 회사는 아래와 같이 관계법령에서 정한 일정한 기간 동안 회원정보를 보관합니다.</p>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="button" class="btn btn-primary btn-yellow w-100" data-bs-dismiss="modal">확인</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 광고성 정보 수신 동의 모달 -->
        <div class="modal fade" id="marketingModal" tabindex="-1" aria-labelledby="marketingModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="marketingModalLabel">광고성 정보 수신 동의</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                        <p>늘봄책방의 새로운 소식과 혜택을 받아보세요.</p>
                        <p>1. 수신 정보<br>
                        - 이벤트 및 프로모션 정보<br>
                        - 신규 서비스 안내<br>
                        - 할인 혜택 및 쿠폰<br>
                        - 교육 컨텐츠 추천</p>
                        <p>2. 수신 방법<br>
                        - 이메일<br>
                        - SMS/MMS<br>
                        - 푸시알림</p>
                        <p>3. 수신 동의 철회<br>
                        - 회원은 언제든지 마케팅 수신 동의를 철회할 수 있습니다.<br>
                        - 철회 방법: 마이페이지 > 설정 > 마케팅 수신 동의 설정</p>
                                                <p>늘봄책방의 새로운 소식과 혜택을 받아보세요.</p>
                        <p>1. 수신 정보<br>
                        - 이벤트 및 프로모션 정보<br>
                        - 신규 서비스 안내<br>
                        - 할인 혜택 및 쿠폰<br>
                        - 교육 컨텐츠 추천</p>
                        <p>2. 수신 방법<br>
                        - 이메일<br>
                        - SMS/MMS<br>
                        - 푸시알림</p>
                        <p>3. 수신 동의 철회<br>
                        - 회원은 언제든지 마케팅 수신 동의를 철회할 수 있습니다.<br>
                        - 철회 방법: 마이페이지 > 설정 > 마케팅 수신 동의 설정</p>
                                                <p>늘봄책방의 새로운 소식과 혜택을 받아보세요.</p>
                        <p>1. 수신 정보<br>
                        - 이벤트 및 프로모션 정보<br>
                        - 신규 서비스 안내<br>
                        - 할인 혜택 및 쿠폰<br>
                        - 교육 컨텐츠 추천</p>
                        <p>2. 수신 방법<br>
                        - 이메일<br>
                        - SMS/MMS<br>
                        - 푸시알림</p>
                        <p>3. 수신 동의 철회<br>
                        - 회원은 언제든지 마케팅 수신 동의를 철회할 수 있습니다.<br>
                        - 철회 방법: 마이페이지 > 설정 > 마케팅 수신 동의 설정</p>
                                                <p>늘봄책방의 새로운 소식과 혜택을 받아보세요.</p>
                        <p>1. 수신 정보<br>
                        - 이벤트 및 프로모션 정보<br>
                        - 신규 서비스 안내<br>
                        - 할인 혜택 및 쿠폰<br>
                        - 교육 컨텐츠 추천</p>
                        <p>2. 수신 방법<br>
                        - 이메일<br>
                        - SMS/MMS<br>
                        - 푸시알림</p>
                        <p>3. 수신 동의 철회<br>
                        - 회원은 언제든지 마케팅 수신 동의를 철회할 수 있습니다.<br>
                        - 철회 방법: 마이페이지 > 설정 > 마케팅 수신 동의 설정</p>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="button" class="btn btn-primary btn-yellow w-100" data-bs-dismiss="modal">확인</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="first_displey">
            <div class="loading-cross-logo-1">
                <img src="{{ asset('images/svg/neubom_logo.svg') }}" alt="책방 로고">
            </div>
            <div class="loading-cross">

                <div class="loading-cross-line">
                    <span class="loading-cross-line-1"></span>
                    <span class="loading-cross-line-2"></span>
                </div>

            </div>
            <div class="loading-cross-logo-2">
                <img src="{{ asset('images/svg/popcorn_neubom_logo.svg') }}" alt="팝콘 로고">
            </div>
        </div>
        <div id="toast" style=" font-size: 1.5rem; padding: 1.1rem; "></div>

    </main>
<div id="system_alert" hidden>
    <div class="modal modal-sheet position-fixed d-block top-50 start-50 translate-middle" tabindex="-1" role="dialog"
        style="width:27%;height:auto;z-index: 9999;">
        <div class="modal-dialog m-0 modal-lg" role="document">
            <div class="modal-content rounded-4 shadow">
                <div class="modal-header border-bottom-0">
                    <h1 class="modal-title cfs-5 msg_title"></h1>
                    <button type="button" class="btn p-0" data-bs-dismiss="modal" aria-label="Close" onclick="this.closest('#system_alert').hidden = true;">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22 10L10.0003 21.9997" stroke="#222222" stroke-width="3.33333" stroke-linecap="round"/>
                            <path d="M10 10L21.9997 21.9997" stroke="#222222" stroke-width="3.33333" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
                <div class="modal-body py-0">
                    <p class="msg_content cfs-6"></p>
                </div>
                <div class="modal-footer flex-column align-items-stretch w-100 gap-2 pb-3 border-top-0">
                    <button type="button" class="msg_btn1 btn btn-lg btn-primary-y"></button>
                    <button type="button" class="msg_btn2 btn btn-lg btn-light ctext-gc1" hidden></button>
                </div>
            </div>
        </div>
    </div>
</div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 전체 동의 체크박스
            const allAgreeCheckbox = document.getElementById('all_agree');
            // 개별 동의 체크박스들
            const agreeTermsCheckbox = document.getElementById('agree_terms');
            const agreePrivacyCheckbox = document.getElementById('agree_privacy');
            const marketingAgreeCheckbox = document.getElementById('marketing_agree');

            // 전체 동의 체크박스 이벤트
            allAgreeCheckbox.addEventListener('change', function() {
                const isChecked = this.checked;
                agreeTermsCheckbox.checked = isChecked;
                agreePrivacyCheckbox.checked = isChecked;
                marketingAgreeCheckbox.checked = isChecked;
            });

            // 개별 체크박스들의 변경을 감지하여 전체 동의 체크박스 상태 업데이트
            const checkboxes = [agreeTermsCheckbox, agreePrivacyCheckbox, marketingAgreeCheckbox];
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const allChecked = checkboxes.every(cb => cb.checked);
                    allAgreeCheckbox.checked = allChecked;
                });
            });
        });



        //사용자 아이디 중복 체크
        function useraddUserIdCheck(nThis){
            const div_user_group = nThis.closest('div');
            const user_id = div_user_group.querySelector('#id').value;
            //영어 및 '_' 와 숫자만 입력가능.
            if(!/^[a-zA-Z0-9_]+$/.test(user_id)){
                toast('아이디는 영어, 숫자, 밑줄 이외의 글자만 입력해주세요.');
                return;
            }
            if(user_id.length < 4){
                toast('아이디는 4자리 이상으로 입력해주세요.');
                return;
            }
            // const sel_group = div_user_group.querySelector('#useradd_sel_group_name');
            // const sel_group_idx = sel_group.selectedIndex;
            // const grouptype = sel_group.options[sel_group.selectedIndex].getAttribute('grouptype');

            if(user_id.length < 1){
                sAlert('','사용자 이이디 입력 후 중복 확인이 가능합니다.', 4);
                return;
            }

            // if(sel_group_idx == 0){
            //     alert('사용자 그룹을 선택 후 아이디 중복 체크 해주세요');
            //     return;
            // }

            const parameter = {
                user_id:user_id,
            };

            const page = '/manage/useradd/user/id/check';
            queryFetch(page,parameter,function(result){
                if(result == null || result.resultCode == null){
                    return '';
                }

                // id_check_wrap 안에 span모두 hidden 되도록 수정해야함.
                document.querySelectorAll('.id_check_wrap span').forEach(function(item){
                    item.hidden = true;
                });
                if(result.resultCode == 'success'){
                    toast('사용 가능한 아이디입니다.');
                    document.querySelector('#inp_status_id').value = 'id_ok';
                    document.querySelector('.id_ok').hidden = false;
                    return;
                }
                else{
                    toast('이미 사용중인 아이디입니다.');
                    document.querySelector('#inp_status_id').value = 'id_duplicate';
                    document.querySelector('.id_duplicate').hidden = false;
                    return;
                }
            });
        }
        function idStatusClear(){
            document.querySelector('#inp_status_id').value = '';
            document.querySelector('.id_ok').hidden = true;
        }

        // 비밀번호 8글자 이상인지.
        function pwChk(){
            const pw = document.querySelector('#password').value;
            const pw2 = document.querySelector('#password_confirm').value;
            if(pw.length < 8){
                document.querySelector('#inp_status_pw').value = 'pw_8';
                document.querySelector('.pw_8').hidden = false;
            }else{
                document.querySelector('#inp_status_pw').value = '';
                document.querySelector('.pw_8').hidden = true;
            }
            if(pw != pw2){
                document.querySelector('#inp_status_pw').value = 'pw_no_match';
                document.querySelector('.pw_no_match').hidden = false;
            }else{
                document.querySelector('#inp_status_pw').value = '';
                document.querySelector('.pw_no_match').hidden = true;
            }
        }

        // 휴대폰 인증번호 전송.
        function sendPhoneAuth(){
            // 데이터
            const user_phone = document.querySelector("#phone").value;
            const user_type = 'teacher';
            const user_seq = '';
            const user_name = document.querySelector('#name').value;
            const phone_status1_el = document.querySelector('.inp_status_phone1');

            if(user_phone.length < 10){
                toast('휴대폰 번호를 10자리 이상으로 입력해주세요.');
                return;
            }
            if(user_name.length < 1){
                toast('이름을 입력해주세요.');
                return;
            }

            // 전송
            const page = '/phone/auth/send/number';
            const parameter = {
                user_phone: user_phone,
                user_seq: user_seq,
                user_type: user_type,
                user_name: user_name
            };
            queryFetch(page, parameter, function(result){
                document.querySelectorAll('.phone_check_wrap1 span').forEach(function(item){
                    item.hidden = true;
                });
                if((result.resultCode||'') == 'success'){
                    toast('인증번호가 전송되었습니다. 인증번호는 3분동안 유효합니다.');
                    document.querySelector('.phone_check_wrap1 .suc').hidden = false;
                    phone_status1_el.value = 'phone_suc';
                    document.querySelector('.div_phone_auth').hidden = false;
                }
                //already
                else if((result.resultCode||'') == 'already'){
                    toast('이미 전송을 진행했습니다. 3분이 지난후 다시 전송해주세요.');
                    document.querySelector('.phone_check_wrap1 .suc').hidden = false;
                    phone_status1_el.value = 'phone_suc';
                    document.querySelector('.div_phone_auth').hidden = false;
                }else{
                    toast('인증번호 전송에 실패하였습니다. 다시 시도해주세요. 유효한 휴대폰 번호인지 확인해주세요.');
                    document.querySelector('.phone_check_wrap1 .fail').hidden = false;
                    phone_status1_el.value = 'phone_fail';
                    document.querySelector('.div_phone_auth').hidden = false;
                }
            });
        }

        // 인증하기 버튼 클릭.
        function phoneAuth(){
            //전역
            const user_seq = '';
            const user_type = 'teacher';
            const user_phone = document.querySelector("#phone").value;
            //모달
            const user_auth = document.querySelector('#phone_auth').value;
            const status_phone1 = document.querySelector('.inp_status_phone1').value;
            const phone_status2_el = document.querySelector('.inp_status_phone2');

            if(status_phone1 != 'phone_suc'){
                toast('먼전 인증번호 발송을 해주세요.');
                return;
            }

            const page = '/phone/auth/check/number';
            const parameter = {
                user_seq: user_seq,
                user_type: user_type,
                user_phone: user_phone,
                user_auth: user_auth,
                is_join:'Y'
            };
            queryFetch(page, parameter, function(result){
                document.querySelectorAll('.phone_check_wrap2 span').forEach(function(item){
                    item.hidden = true;
                });
                phone_status2_el.value = 'phone_fail';
                if((result.resultCode||'') == 'success'){
                    toast('휴대폰이 인증되었습니다.');
                    document.querySelector('.phone_check_wrap2 .suc').hidden = false;
                    phone_status2_el.value = 'phone_suc';
                }else if((result.resultCode||'') == 'timeover'){
                    toast('인증번호가 시간이 만료되었습니다. 다시 인증번호를 받아주세요.');
                    document.querySelector('.phone_check_wrap2 .fail').hidden = false;
                }
                //already
                else if((result.resultCode||'') == 'already'){
                    toast('이미 전송을 진행했습니다. 3분이 지난후 다시 전송해주세요.');
                    document.querySelector('.phone_check_wrap2 .fail').hidden = false;
                }
                else if((result.resultCode||'') == 'already_phone'){
                    toast('이미 등록된 휴대폰 번호입니다. 다른 번호를 입력해주세요.');
                    document.querySelector('.phone_check_wrap2 .fail').hidden = false;
                }
                else{
                    toast('인증번호가 일치하지 않습니다. 다시 확인해주세요.');
                    document.querySelector('.phone_check_wrap2 .fail').hidden = false;
                }
            });
        }

        // 휴대폰 번호 변경시 초기화.
        function phoneStatusClear(){
            document.querySelector('.inp_status_phone1').value = '';
            document.querySelector('.phone_check_wrap1 .suc').hidden = true;
            document.querySelector('.phone_check_wrap1 .fail').hidden = true;
        }


        // 이메일 인증번호 전송.
        function sendMailAuth(){
            // 데이터
            const user_email = document.querySelector('#email').value;
            const user_type = 'teacher';
            const user_seq = '';
            const user_name = document.querySelector('#name').value;
            const email_status1_el = document.querySelector('.inp_status_email1');
            if(user_email.length < 1){
                toast('이메일을 입력해주세요.');
                return;
            }
            if(user_name.length < 1){
                toast('이름을 입력해주세요.');
                return;
            }

            // 전송
            const page = '/mail/auth/send/number';
            const parameter = {
                user_email: user_email,
                user_seq: user_seq,
                user_type: user_type,
                user_name: user_name
            };
            queryFetch(page, parameter, function(result){
                document.querySelectorAll('.email_check_wrap1 span').forEach(function(item){
                    item.hidden = true;
                });
                if((result.resultCode||'') == 'success'){
                    toast('인증번호가 전송되었습니다. 인증번호는 3분동안 유효합니다.');
                    document.querySelector('.email_check_wrap1 .suc').hidden = false;
                    email_status1_el.value = 'email_suc';
                    document.querySelector('.div_email_auth').hidden = false;

                }
                else{
                    toast('인증번호 전송에 실패하였습니다. 다시 시도해주세요. 유효한 메일인지 확인해주세요.');
                    document.querySelector('.email_check_wrap1 .fail').hidden = false;
                    email_status1_el.value = 'email_fail';
                    document.querySelector('.div_email_auth').hidden = true;
                }
            });
        }

        // 이메일 인증
        function mailAuth(){
            const user_seq = '';
            const user_type = 'teacher';
            const user_email = document.querySelector('#email').value;
            const user_auth = document.querySelector('#email_auth').value;
            const status_email1 = document.querySelector('.inp_status_email1').value;
            const email_status2_el = document.querySelector('.inp_status_email2');

            if(user_email.length < 1){
                toast('이메일을 입력해주세요.');
                return;
            }
            if(user_auth.length < 1){
                toast('인증번호를 입력해주세요.');
                return;
            }
            if(status_email1 != 'email_suc'){
                toast('먼저 이메일 발송을 해주세요.');
                return;
            }

            const page = '/mail/auth/check/number';
            const parameter = {
                user_seq: user_seq,
                user_type: user_type,
                user_email: user_email,
                user_auth: user_auth,
                is_join:'Y'
            };
            queryFetch(page, parameter, function(result){
                document.querySelectorAll('.email_check_wrap2 span').forEach(function(item){
                    item.hidden = true;
                });
                email_status2_el.value = 'email_fail';
                if((result.resultCode||'') == 'success'){
                    toast('메일이 인증되었습니다.');
                    document.querySelector('.email_check_wrap2 .suc').hidden = false;
                    email_status2_el.value = 'email_suc';
                }else if((result.resultCode||'') == 'timeover'){
                    toast('인증번호가 시간이 만료되었습니다. 다시 인증번호를 받아주세요.');
                    document.querySelector('.email_check_wrap2 .fail').hidden = false;
                }
                else{
                    toast('인증번호가 일치하지 않습니다. 다시 확인해주세요.');
                    document.querySelector('.email_check_wrap2 .fail').hidden = false;
                }
            });
        }

        // 이메일 번호 변경시 초기화.
        function emailStatusClear(){
            document.querySelector('.inp_status_email1').value = '';
            document.querySelector('.email_check_wrap1 .suc').hidden = true;
            document.querySelector('.email_check_wrap1 .fail').hidden = true;
        }


        // 회원가입 전 체크.
        function joinChk(){
            let return_bool = true;
            // 아이디 체크.
            const id_chk = document.querySelector('#inp_status_id').value;
            if(id_chk != 'id_ok'){
                return_bool = false;
                document.querySelectorAll('.id_check_wrap span').forEach(function(item){
                    item.hidden = true;
                });
                document.querySelector('.id_check_wrap .id_duplicate_chk').hidden = false;
            }
            // 비밀번호 체크.
            const pw_chk = document.querySelector('#inp_status_pw').value;
            if(pw_chk != ''){
                return_bool = false;
            }
            // 선생님 이름 체크.
            const name = document.querySelector('#name').value;
            if(name == ''){
                return_bool = false;
                document.querySelector('.name_check_wrap .check_fail').hidden = false;
            }else{
                document.querySelector('.name_check_wrap .check_fail').hidden = true;
            }
            // 휴대폰 번호 체크.
            const phone = document.querySelector('#phone').value;
            const phone_auth = document.querySelector('#phone_auth').value;
            if(phone.length < 1 && phone_auth.length < 1){
                return_bool = false;
                toast('휴대폰 번호를 먼저 입력해주세요.');
            }
            const status_phone1 = document.querySelector('.inp_status_phone1').value;
            const status_phone2 = document.querySelector('.inp_status_phone2').value;
            if(status_phone1 != 'phone_suc' && status_phone2 != 'phone_suc'){
                return_bool = false;
                toast('휴대폰 인증을 진행해주세요.');
            }
            // 이메일 인증 체크.
            const email = document.querySelector('#email').value;
            const email_auth = document.querySelector('#email_auth').value;
            if(email.length < 1 && email_auth.length < 1){
                return_bool = false;
                toast('이메일을 먼저 입력해주세요.');
            }
            const status_email1 = document.querySelector('.inp_status_email1').value;
            const status_email2 = document.querySelector('.inp_status_email2').value;
            if(status_email1 != 'email_suc' && status_email2 != 'email_suc'){
                return_bool = false;
                toast('이메일 인증을 진행해주세요.');
            }

            // 동의.
            const agree_privacy = document.querySelector('#agree_privacy').checked;
            const agree_terms = document.querySelector('#agree_terms').checked;
            if(agree_privacy == false || agree_terms == false){
                return_bool = false;
                toast('동의 필수 약관에 동의해주세요.');
            }

            return return_bool;
        }
    </script>
</body>
</html>
