
{{-- 로그인 페이지 --}}
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="{{ asset('css/bootstrap.css?8') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.15.5/xlsx.full.min.js"></script> --}}
    <link href="{{ asset('css/admin_style.css?28') }}" rel="stylesheet">
    <link href="{{ asset('css/mainstyle.css?6') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('font/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/colors-system.css?1') }}">
    
    <link rel="stylesheet" as="style" crossorigin
    href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/static/pretendard.min.css" />
    <link rel="stylesheet" href="{{ asset('css/reset.css?5') }}">

    <script src="{{ asset('js/admin_script.js?19') }}"></script>
    <title>학생 로그인</title>
</head>
<body>
    <main>
        <div class="main-wrap all-center" style="height:100vh;max-width:448px">
          <div class="login-wrap">
            <div class="d-flex justify-content-center">
              <span class="ht-make-title on text-r-20px py-1 px-3 ms-1 mb-4">학생 로그인</span>
            </div>
            <div class="d-flex justify-content-center mb-3">
              <svg width="281" height="53" viewBox="0 0 281 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M54.5684 23.1843C54.5684 18.95 51.1105 15.518 46.8441 15.518C46.7753 15.518 46.7064 15.518 46.6376 15.521C44.0748 8.32418 37.1618 3.16577 29.0333 3.16577C20.3121 3.16577 12.983 9.10864 10.9351 17.1315C4.52814 19.9989 0.064209 26.4024 0.064209 33.8339C0.064209 43.9487 8.32442 52.1499 18.5187 52.1499C22.0725 52.1499 25.3838 51.1574 28.201 49.431C28.8567 51.5021 30.8057 52.9997 33.111 52.9997C35.9553 52.9997 38.2606 50.7117 38.2606 47.8888C38.2606 47.7313 38.2516 47.5738 38.2396 47.4193C39.6348 47.9958 41.1617 48.3137 42.7664 48.3137C49.2842 48.3137 54.5684 43.0691 54.5684 36.6003C54.5684 33.724 53.5266 31.0972 51.7991 29.0618C53.4906 27.6563 54.5684 25.5436 54.5684 23.1843Z" fill="#FFD15F"/>
                <path d="M26.5577 24.5931C28.5469 24.5931 30.1594 22.3514 30.1594 19.5862C30.1594 16.821 28.5469 14.5793 26.5577 14.5793C24.5686 14.5793 22.9561 16.821 22.9561 19.5862C22.9561 22.3514 24.5686 24.5931 26.5577 24.5931Z" fill="white"/>
                <path d="M28.0277 21.6483C29.1653 21.6483 30.0875 20.3751 30.0875 18.8046C30.0875 17.2341 29.1653 15.9609 28.0277 15.9609C26.8901 15.9609 25.9679 17.2341 25.9679 18.8046C25.9679 20.3751 26.8901 21.6483 28.0277 21.6483Z" fill="#472B16"/>
                <path d="M38.2756 24.5931C40.2648 24.5931 41.8773 22.3514 41.8773 19.5862C41.8773 16.821 40.2648 14.5793 38.2756 14.5793C36.2865 14.5793 34.674 16.821 34.674 19.5862C34.674 22.3514 36.2865 24.5931 38.2756 24.5931Z" fill="white"/>
                <path d="M39.7512 21.6483C40.8888 21.6483 41.811 20.3751 41.811 18.8046C41.811 17.2341 40.8888 15.9609 39.7512 15.9609C38.6136 15.9609 37.6914 17.2341 37.6914 18.8046C37.6914 20.3751 38.6136 21.6483 39.7512 21.6483Z" fill="#472B16"/>
                <path d="M40.5492 28.1285C40.19 27.9532 39.7559 28.1018 39.5822 28.4584C38.5014 30.6424 36.3398 32.0033 33.6453 32.1875C30.4717 32.4014 27.2173 30.9276 25.4898 28.5029C25.4779 27.347 25.0767 26.2625 24.6635 25.5701C24.4599 25.2284 24.0198 25.1155 23.6755 25.3176C23.3312 25.5166 23.2145 25.9564 23.4151 26.2981C23.421 26.307 23.915 27.1598 24.0168 28.2058C24.1456 29.5073 23.6336 30.4849 22.457 31.195C22.1157 31.4001 22.0079 31.8428 22.2145 32.1816C22.3522 32.4044 22.5887 32.5262 22.8342 32.5262C22.963 32.5262 23.0917 32.4936 23.2085 32.4222C24.1905 31.828 24.7923 31.1 25.1306 30.3333C27.0886 32.4074 29.9927 33.6465 33.0016 33.6465C33.2501 33.6465 33.4956 33.6376 33.7441 33.6227C36.9655 33.4028 39.5642 31.7537 40.8786 29.0942C41.0552 28.7377 40.9055 28.3068 40.5492 28.1345V28.1285Z" fill="#280800"/>
                <path d="M6.41346 14.4197C7.21139 13.4732 6.77265 11.8044 5.4335 10.6923C4.09435 9.58023 2.36191 9.44598 1.56398 10.3925C0.766051 11.3389 1.2048 13.0077 2.54394 14.1198C3.88309 15.2319 5.61553 15.3661 6.41346 14.4197Z" fill="#FFD15F"/>
                <path d="M55.6423 9.67085C56.9175 7.29751 56.6656 4.69308 55.0797 3.85369C53.4937 3.01429 51.1743 4.25781 49.8991 6.63115C48.6239 9.00449 48.8757 11.6089 50.4617 12.4483C52.0476 13.2877 54.367 12.0442 55.6423 9.67085Z" fill="#FFD15F"/>
                <path d="M49.0333 3.19059C49.2692 1.73893 48.6743 0.436335 47.7046 0.281155C46.735 0.125974 45.7577 1.17698 45.5219 2.62863C45.286 4.08028 45.8809 5.38288 46.8506 5.53806C47.8202 5.69324 48.7975 4.64224 49.0333 3.19059Z" fill="#FFD15F"/>
                <path d="M96.9464 21.2888C96.9464 29.9773 90.3927 34.2948 82.2852 34.7762C82.2852 38.4073 82.372 41.5243 82.6205 43.1289C82.6744 43.4706 82.5277 43.8182 82.2313 44.0054C79.8092 45.5238 73.3573 45.3723 72.5071 44.5314C71.9173 43.946 71.8095 30.5122 72.1298 20.8104H71.9682C70.8933 20.8104 70.57 11.6941 71.0011 11.2692C71.4831 10.7878 77.7135 9.34961 82.9259 9.34961C91.0364 9.34961 96.9434 14.7338 96.9434 21.2888H96.9464ZM82.3391 26.6731C84.5426 26.5126 86.6892 25.6598 86.6892 20.7569C86.6892 18.9979 85.345 18.0381 83.5217 17.9846C83.1983 17.9846 82.878 17.9846 82.5008 18.0381L82.3391 26.6731Z" fill="#473300"/>
                <path d="M215.035 13.2032C214.765 12.8467 214.472 12.5079 214.152 12.1811C212.05 10.0387 209.269 8.95706 206.305 8.67478C206.089 8.65398 205.874 8.64209 205.655 8.64209C196.416 8.64209 193.14 13.6519 193.14 28.9518C193.14 44.2517 200.874 45.6364 207.373 45.6364C213.873 45.6364 217.954 38.0682 217.954 24.2065C217.954 20.4387 217.373 16.2846 215.032 13.2032H215.035ZM206.194 39.3994C203.778 39.3994 203.67 35.5068 203.67 27.1393C203.67 18.7717 204.96 15.9459 206.248 15.9459C206.891 15.9459 208.397 18.8252 208.397 21.4906C208.505 23.0357 208.559 24.9018 208.559 27.1422C208.559 34.7105 208.451 39.4024 206.197 39.4024L206.194 39.3994Z" fill="#473300"/>
                <path d="M164.706 21.1611C164.706 29.8496 158.152 34.1671 150.041 34.6485C150.041 38.2796 150.128 41.3966 150.377 43.0012C150.431 43.3429 150.284 43.6906 149.988 43.8778C147.565 45.3962 141.114 45.2446 140.263 44.4037C139.674 43.8183 139.566 30.3845 139.886 20.6827H139.724C138.65 20.6827 138.329 11.5664 138.757 11.1415C139.239 10.6601 145.47 9.22192 150.679 9.22192C158.79 9.22192 164.697 14.6062 164.697 21.1611H164.706ZM150.095 26.5454C152.299 26.3849 154.445 25.5321 154.445 20.6293C154.445 18.8702 153.101 17.9104 151.278 17.8569C150.955 17.8569 150.634 17.8569 150.257 17.9104L150.095 26.5454Z" fill="#473300"/>
                <path d="M179.591 46.0259C172.34 46.0259 167.72 39.0966 167.72 28.4885C167.72 17.8805 171.535 8.76416 178.945 8.76416C186.354 8.76416 189.794 14.8407 189.794 20.332C189.794 20.6113 189.22 20.8549 189.028 20.9589C188.309 21.3452 187.543 21.6483 186.783 21.9425C185.932 22.2693 185.073 22.5724 184.205 22.8547C183.858 22.9676 183.51 23.0746 183.16 23.1756C182.959 23.2321 182.322 23.5025 182.226 23.241C182.166 23.0776 182.196 22.8517 182.202 22.6824C182.211 22.3882 182.235 22.091 182.256 21.7969C182.31 21.0451 182.358 20.2933 182.331 19.5386C182.307 18.8403 182.238 18.0945 181.92 17.4586C181.639 16.897 181.139 16.5434 180.495 16.5434C177.433 16.5434 176.253 37.7059 179.852 37.7059C181.612 37.7059 181.941 35.6111 181.992 34.3185C182.001 34.06 182.226 33.8668 182.483 33.8906C184.843 34.1075 189.681 35.7032 189.681 36.7461C189.681 39.1441 185.6 46.02 179.585 46.02L179.591 46.0259Z" fill="#473300"/>
                <path d="M247.727 21.1607C247.727 26.8124 244.988 30.5445 240.853 32.675C242.865 35.0046 246.628 39.6668 248.523 40.6474C248.901 40.8435 249.035 41.3159 248.814 41.6784C247.748 43.4286 244.173 46.1594 242.089 46.1594C240.317 46.1594 235.644 40.5612 233.066 36.9925C233.114 39.5895 233.2 41.7438 233.398 42.9978C233.452 43.3395 233.308 43.6901 233.012 43.8743C230.59 45.3927 224.138 45.2412 223.291 44.4003C222.701 43.8149 222.593 30.3811 222.913 20.6793H222.752C221.677 20.6793 221.354 11.563 221.785 11.1381C222.267 10.6567 228.497 9.21851 233.709 9.21851C241.82 9.21851 247.727 14.6027 247.727 21.1577V21.1607ZM233.12 26.5449C235.32 26.3845 237.47 25.5317 237.47 20.6288C237.47 18.8697 236.126 17.9099 234.299 17.8565C233.976 17.8565 233.656 17.8565 233.278 17.9099L233.117 26.5449H233.12Z" fill="#473300"/>
                <path d="M262.617 10.6626C263.344 13.1586 267.093 25.6416 268.73 30.2236C268.676 21.0537 269 10.8736 269.644 10.2347C270.826 9.06101 280.062 10.5022 280.062 11.141C280.062 13.0606 279.095 38.3267 280.062 42.9116C278.182 44.8311 270.233 45.6839 269.374 44.8311C268.569 44.0318 264.272 31.1833 261.964 24.7888C261.802 30.1196 261.641 36.4101 261.695 42.8611C261.695 43.4464 251.488 45.5799 251.488 44.5132C251.488 43.4464 252.24 14.6087 252.24 12.0503C252.24 9.85438 260.132 10.1367 262.072 10.2407C262.326 10.2555 262.542 10.4249 262.614 10.6686L262.617 10.6626Z" fill="#473300"/>
                <path d="M102.749 19.8265C102.486 20.1059 102.225 20.3941 101.977 20.6972C96.4021 27.5315 97.4649 37.5571 104.351 43.0869C111.237 48.6197 121.338 47.5649 126.91 40.7306C128.144 39.2181 129.051 37.5482 129.644 35.8069C131.099 35.0373 132.422 33.9706 133.527 32.6215C137.934 27.2195 137.092 19.2947 131.649 14.9207C127.446 11.5452 121.731 11.2748 117.315 13.8124C116.851 12.4633 116.015 11.2183 114.815 10.2556C111.42 7.52778 106.435 8.04778 103.686 11.4174C101.683 13.8748 101.426 17.1641 102.743 19.8265H102.749Z" fill="#FFD15F"/>
                <path d="M121.219 32.6627C121.159 32.6241 115.297 28.7374 116.106 22.1765C116.231 21.1543 115.501 20.2272 114.471 20.1024C113.444 19.9747 112.507 20.7027 112.381 21.7248C112.145 23.6384 112.315 25.3708 112.728 26.9159C111.169 26.9159 109.283 27.2487 107.384 28.3927C106.498 28.9276 106.217 30.0746 106.756 30.9511C107.109 31.5276 107.726 31.8455 108.36 31.8455C108.693 31.8455 109.028 31.7594 109.333 31.5751C111.426 30.3123 113.501 30.5975 114.459 30.8412C116.408 33.9493 118.98 35.6579 119.165 35.7797C119.483 35.9848 119.839 36.0828 120.189 36.0828C120.8 36.0828 121.402 35.7857 121.761 35.2389C122.327 34.3772 122.084 33.2243 121.219 32.6597V32.6627Z" fill="#473300"/>
                </svg>
            </div>
            <div class="d-flex justify-content-center">
              <p class="text-sb-18px scale-text-gray_05 py-3">전문적인 선생님과 함께하는 팝콘에 오신 것을 환영합니다.</p>
            </div>
            <form class="form-horizontal" action="/student/login" method="post" onsubmit="return validateForm()">
                @csrf
            <div class="py-52px">
              <div class="row w-100 mx-0">
                <div class="col-12 p-0 mb-12px">
                  <label class="label-input-wrap w-100">
                    <input type="text" class="smart-ht-search border-gray rounded text-m-20px w-100" 
                    id="id" name="id" placeholder="아이디">
                  </label>
                </div>
              <div class="col-12 p-0 pt-2">
                <label class="label-input-wrap w-100">
                  <input type="password" class="smart-ht-search border-gray rounded text-m-20px w-100" 
                  id="password" name="password" placeholder="비밀번호">
                </label>
                </div>
                {{-- <label class="checkbox d-flex justify-content-start align-items-center ps-0 mt-4">
                  <input type="checkbox" class="">
                  <span class=""></span>
                  <p class="scale-text-gray_05 text-m-18px ps-1">다음부터 자동으로 로그인</p>
                </label> --}}
              </div>
              </div>
                <div class="row w-100 p-0 mb-4 mt-3 mx-0">
                  <div class="col-12 p-0">
                    <button type="submit" class="btn-lg-primary text-b-24px rounded scale-text-white w-100 text-center justify-content-center">로그인</button>
                  </div>
                </div>
            </form>
                <div class="d-flex justify-content-center gap-2">
                  <a href="#" class="scale-text-gray_05 text-m-18px">아이디 찾기</a>
                  <div class="all-center px-1"><div class=" h-75 scale-bg-gray_05" style="width:2px"></div></div>
                  <a href="#" class="scale-text-gray_05 text-m-18px">비밀번호 찾기</a>
                </div>
                <div class="login-footer">
                  <div class="d-flex justify-content-center align-items-center  mb-2">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M20 12C20 7.58172 16.4182 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4182 7.58172 20 12 20C16.4182 20 20 16.4182 20 12Z" fill="#FFC747"/>
                      <path d="M12.5624 7.6001H11.4389C11.3301 7.59992 11.2224 7.61949 11.1225 7.65759C11.0225 7.69567 10.9325 7.75149 10.8578 7.82159C10.7832 7.89169 10.7256 7.97459 10.6885 8.06519C10.6513 8.15578 10.6355 8.25215 10.6421 8.34835L10.9926 13.6001H13.0006L13.3592 8.34835C13.3657 8.25215 13.3499 8.15578 13.3128 8.06519C13.2757 7.97459 13.2181 7.89169 13.1434 7.82159C13.0688 7.75149 12.9787 7.69567 12.8788 7.65759C12.7789 7.61949 12.6712 7.59992 12.5624 7.6001Z" fill="white"/>
                      <path d="M12.0008 17.0885C12.6635 17.0885 13.2008 16.5512 13.2008 15.8885C13.2008 15.2257 12.6635 14.6885 12.0008 14.6885C11.3381 14.6885 10.8008 15.2257 10.8008 15.8885C10.8008 16.5512 11.3381 17.0885 12.0008 17.0885Z" fill="white"/>
                    </svg>
                    <p class="scale-text-gray_05 text-m-18px">최초 로그인 시 발급 받은 임시아이디 / 비밀번호를 입력해주세요.</p>
                  </div>
                  <div class="d-flex justify-content-center align-items-center">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M20 12C20 7.58172 16.4182 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4182 7.58172 20 12 20C16.4182 20 20 16.4182 20 12Z" fill="#FFC747"/>
                      <path d="M12.5624 7.6001H11.4389C11.3301 7.59992 11.2224 7.61949 11.1225 7.65759C11.0225 7.69567 10.9325 7.75149 10.8578 7.82159C10.7832 7.89169 10.7256 7.97459 10.6885 8.06519C10.6513 8.15578 10.6355 8.25215 10.6421 8.34835L10.9926 13.6001H13.0006L13.3592 8.34835C13.3657 8.25215 13.3499 8.15578 13.3128 8.06519C13.2757 7.97459 13.2181 7.89169 13.1434 7.82159C13.0688 7.75149 12.9787 7.69567 12.8788 7.65759C12.7789 7.61949 12.6712 7.59992 12.5624 7.6001Z" fill="white"/>
                      <path d="M12.0008 17.0885C12.6635 17.0885 13.2008 16.5512 13.2008 15.8885C13.2008 15.2257 12.6635 14.6885 12.0008 14.6885C11.3381 14.6885 10.8008 15.2257 10.8008 15.8885C10.8008 16.5512 11.3381 17.0885 12.0008 17.0885Z" fill="white"/>
                    </svg>
                    <p class="scale-text-gray_05 text-m-18px">최초 로그인 시 개인정보 변경 / 등록이 필요합니다.</p>
                  </div>
                </div>
              </div>
          </div>
        </div>
      </main>
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
                        <button type="button" class="msg_btn1 btn btn-lg btn-primary-y"></button>
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
            location.href = "/student/login";
        });
    @endif

    function validateForm() {
        var team_code = document.querySelector("#team_code").value;
        var id = document.querySelector("#id").value;
        var password = document.querySelector("#password").value;
        if (team_code == "" || id == "" || password == "") {
            sAlert('',"모든 정보를 입력해주세요.");
            return false;
        }
    }
</script>
</body>
<footer id="layout_div_foot" class="container-fluid bg-light">
    <div class="row foot_btm">
        <div class="col py-3">
            <div class="dropdown d-flex justify-content-center">
                <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset('/images/popcorn_logo.svg') }}">
                    {{-- <img src="{{ asset('/images/dropdown_arrow_down3.svg') }}" class="ms-2"> --}}
                </button>
                {{-- <ul class="dropdown-menu">
                    <li><button class="dropdown-item" type="button">#미정1</button></li>
                    <li><button class="dropdown-item" type="button">#미정2</button></li>
                </ul> --}}
            </div>
            {{-- <div class="py-3 mt-4">
                <span class="ctext-gc1">주식회사 도원이앤아이</span>
            </div> --}}
            <div class="py-2 d-flex justify-content-center">
                {{-- 대표 --}}
                <span class="ctext-gc1">주식회사 팝콘에듀</span>
                {{-- <img src="{{ asset('images/bar_icon.svg') }}" alt=""> --}}
                
            </div>
            <div class="py-2 d-flex justify-content-center">
                <span class="ctext-gc1" data="#대표이름">대표이사 박범신</span>
            </div>
            <div class="py-2 d-flex justify-content-center">
                {{-- 주소 --}}
                <span class="ctext-gc1">주소</span>
                <img src="{{ asset('images/bar_icon.svg') }}" alt="">
                <span class="ctext-gc1 me-2" data="#주소">부산광역시 해운대구 세실로 45, 9층 901호(좌동, 대승프라자)</span>
                {{-- 사업자등록번호 --}}
                <span class="ctext-gc1">사업자등록번호</span>
                <img class="mx-2" src="{{ asset('images/bar_icon.svg') }}" alt="">
                <span class="ctext-gc1" data="#사업자등록번호">662-86-01102</span>
            </div>
            <div>
                {{-- 대표번호 --}}
                {{-- <span class="ctext-gc1">대표번호</span>
                <img src="{{ asset('images/bar_icon.svg') }}" alt=""> --}}
                {{-- 제휴문의 --}}
                {{-- <span class="ctext-gc1" > <span class="ctext-gc1">제휴문의</span>
                <img src="{{ asset('images/bar_icon.svg') }}" alt="">
                <span class="ctext-gc1" data="#제휴문의">#제휴문의</span> --}}
            </div>
            {{-- <div class="pt-2 d-flex justify-content-center">
                <span class="ctext-gc1">Copyright© 주식회사 팝콘에듀 Corp. Al Rights Reserved.</span>
            </div> --}}
        </div>
        {{-- <div class="col row text-end">
            <div class="dropup-center dropup">
                <button class="btn border border-2 rounded-pill cbtn-p col-4 my-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="row">
                        <span class="col text-start ctext-gc1 fw-semibold">FAMILY STIE</span>
                        <img src="{{ asset('/images/dropdown_arrow_up3.svg') }}" class="col-auto">
                    </div>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">#미정1</a></li>
                    <li><a class="dropdown-item" href="#">#미정2</a></li>
                </ul>
            </div>
            <div class="pt-5">
                <div>
                    <span class="ctext-gc1">(주)도원이앤아이 대표번호</span>
                </div>
                <div class="py-2">
                    <span class="ctext-bc0 cfs-1">1588-0000</span>
                </div>
            </div>
        </div> --}}
    </div>
</footer>
</html>