
{{-- 로그인 페이지 --}}
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="{{ asset('js/admin_script.js?16') }}"></script>
    <title>관리자 로그인</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center">관리자 로그인</h3>
                        <form class="form-horizontal" action="/manage/login" method="post" onsubmit="return validateForm()">
                            @csrf
                            {{-- 학원코드 --}}
                            <div class="mb-3">
                                <label for="team_code" class="form-label">팀 코드</label>
                                <input type="text" class="form-control" id="team_code" name="team_code" placeholder="학원 코드 입력">
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">아이디</label>
                                <input type="text" class="form-control" id="id" name="id" placeholder="아이디">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">비밀번호</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="비밀번호 입력">
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-block">로그인</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <a href="#">비밀번호를 잊으셨나요?</a>
                </div>
            </div>
        </div>
    </div>

    <div id="system_alert" hidden>
        <div class="modal modal-sheet position-fixed d-block top-50 start-50 translate-middle" tabindex="-1" role="dialog"
            style="width:20%;height:auto">
            <div class="modal-dialog m-0" role="document">
                <div class="modal-content rounded-4 shadow">
                    <div class="modal-header border-bottom-0">
                        <h1 class="modal-title fs-5 msg_title"></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="this.closest('#system_alert').hidden = true;"></button>
                    </div>
                    <div class="modal-body py-0">
                        <p class="msg_content"></p>
                    </div>
                    <div class="modal-footer flex-column align-items-stretch w-100 gap-2 pb-3 border-top-0">
                        <button type="button" class="msg_btn1 btn btn-lg btn-primary"></button>
                        <button type="button" class="msg_btn2 btn btn-lg btn-secondary" hidden></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    @if(isset($is_not_login) && $is_not_login)
    @php
        $is_not_login = false;
    @endphp
        sAlert('',"정확한 로그인 정보가 필요합니다.", 1, function(){
            location.href = "/manage";
        });
    @endif

    function validateForm() {
        var team_code = document.querySelector("#team_code").value;
        var id = document.querySelector("#id").value;
        var password = document.querySelector("#password").value;
        if (team_code == "" || id == "" || password == "") {
            alert("모든 정보를 입력해주세요.");
            return false;
        }
    }
</script>
</body>
</html>