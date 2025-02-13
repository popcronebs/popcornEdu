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

    <link rel="stylesheet" href="{{ asset('font/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/neulbom.css?1') }}">

    <link rel="stylesheet" as="style" crossorigin href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/static/pretendard.min.css" />
    <link rel="stylesheet" href="{{ asset('css/reset.css?5') }}">

    <script src="{{ asset('js/admin_script.js?19') }}"></script>
    <title>팝콘 로그인</title>
</head>
<body>
    <input type="hidden" id="csrf_token" value="{{ csrf_token() }}" />
    <div id="toast" style=" font-size: 1.5rem; padding: 1.1rem; "></div>
    <input type="hidden" id="is_join" value="{{ $is_join }}">
    <main>
        <div class="register_neulbom_layout">
            <div class="register_neulbom_layout_left d-flex flex-column justify-content-center align-items-center">
                <div class="register_neulbom_layout_left_top_wrap mb-5">
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
                <h2 class="mb-5 login_title">로그인</h2>
            <form class="form-horizontal" action="/login/check" method="post" onsubmit="return validateForm()" id="loginForm">
                    @csrf
                    <input type="hidden" name="is_neulbom" value="Y">
                    <div class="register_neulbom_layout_left_form">
                        <div class="form_group ">
                            <div class="input_wrap_wrap">
                                <div class="input_wrap">
                                    <input type="text" id="id" name="id" required placeholder=" " value="{{$_COOKIE['save_usr_id']??''}}">
                                    <label for="id">아이디</label>
                                </div>
                            </div>
                        </div>
                        <div class="form_group">
                            <div class="input_wrap">
                                <input type="password" id="password" name="password" required placeholder=" ">
                                <label for="password">비밀번호</label>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap justify-content-center align-items-center gap-3">
                            <button type="submit" class="submit_btn" id="loginButton">로그인</button>
                            <button type="button" class="submit_btn" onclick="location.href='/register/neulbom'">회원가입</button>
                        </div>
                    </div>
                    {{-- 이용약관동의 --}}

                </form>
            </div>
            <div class="register_neulbom_layout_right d-none">

            </div>
        </div>


            @if(!(isset($is_not_login) && $is_not_login || Session::get('error')))
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
            @endif
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
        @if(isset($is_not_login) && $is_not_login)
            @php
                $is_not_login = false;
                $msg = "정확한 로그인 정보가 필요합니다.";
                if(isset($rt_code) && $rt_code == 'no_region'){
                    $msg = "소속이 없습니다.";
                }else if(isset($rt_code) && $rt_code == 'no_use'){
                    $msg = "비활성화된 아이디 입니다.";
                }
            @endphp
            sAlert('',"{{$msg}}", 1, function(){ });
        @endif
        document.addEventListener('DOMContentLoaded', function() {
            const is_join = document.querySelector('#is_join').value;
            if(is_join == 'Y'){
                setTimeout(function(){
                    const msg = "<div class='text-sb-28px'>회원가입이 완료되었습니다.</div>";
                    sAlert('', msg, 4);
                }, 2000);
            }
        });

    function validateForm() {
         document.getElementById('loginButton').disabled = true;
        // var team_code = document.querySelector("#team_code").value;
        var id = document.querySelector("#id").value;
        setCookie('save_usr_id', id, 999);
        var password = document.querySelector("#password").value;
        if (id == "" || password == "") {
            sAlert('',"모든 정보를 입력해주세요.");
            document.getElementById('loginButton').disabled = false;
            return false;
        }
        setTimeout(function(){
            document.getElementById('loginButton').disabled = false;
        }, 1000);
        return true;
    }
    @if(Session::get('error'))
        toast('{{ Session::get('error') }}');
    @endif
    </script>
</body>
</html>
