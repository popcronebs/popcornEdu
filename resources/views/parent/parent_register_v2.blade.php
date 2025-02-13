{{-- 로그인 페이지 --}}
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="{{ asset('css/bootstrap.css?8') }}" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
  </script>

  {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.15.5/xlsx.full.min.js"></script> --}}
  <link href="{{ asset('css/admin_style.css?28') }}" rel="stylesheet">
  <link href="{{ asset('css/mainstyle.css?6') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('font/font.css') }}">
  <link rel="stylesheet" href="{{ asset('css/colors-system.css?1') }}">

  <link rel="stylesheet" as="style" crossorigin href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/static/pretendard.min.css" />
  <link rel="stylesheet" href="{{ asset('css/reset.css?5') }}">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="{{ asset('js/admin_script.js?19') }}"></script>

  <title>학부모 로그인</title>
</head>
<body>
  <main>
    <div class="main-wrap d-flex justify-content-center">
      <div class="login-wrap w-100">
        <div class="d-flex justify-content-center mb-5 mt-5">
          <svg width="281" height="53" viewBox="0 0 281 53" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M54.5684 23.1843C54.5684 18.95 51.1105 15.518 46.8441 15.518C46.7753 15.518 46.7064 15.518 46.6376 15.521C44.0748 8.32418 37.1618 3.16577 29.0333 3.16577C20.3121 3.16577 12.983 9.10864 10.9351 17.1315C4.52814 19.9989 0.064209 26.4024 0.064209 33.8339C0.064209 43.9487 8.32442 52.1499 18.5187 52.1499C22.0725 52.1499 25.3838 51.1574 28.201 49.431C28.8567 51.5021 30.8057 52.9997 33.111 52.9997C35.9553 52.9997 38.2606 50.7117 38.2606 47.8888C38.2606 47.7313 38.2516 47.5738 38.2396 47.4193C39.6348 47.9958 41.1617 48.3137 42.7664 48.3137C49.2842 48.3137 54.5684 43.0691 54.5684 36.6003C54.5684 33.724 53.5266 31.0972 51.7991 29.0618C53.4906 27.6563 54.5684 25.5436 54.5684 23.1843Z" fill="#FFD15F" />
            <path d="M26.5577 24.5931C28.5469 24.5931 30.1594 22.3514 30.1594 19.5862C30.1594 16.821 28.5469 14.5793 26.5577 14.5793C24.5686 14.5793 22.9561 16.821 22.9561 19.5862C22.9561 22.3514 24.5686 24.5931 26.5577 24.5931Z" fill="white" />
            <path d="M28.0277 21.6483C29.1653 21.6483 30.0875 20.3751 30.0875 18.8046C30.0875 17.2341 29.1653 15.9609 28.0277 15.9609C26.8901 15.9609 25.9679 17.2341 25.9679 18.8046C25.9679 20.3751 26.8901 21.6483 28.0277 21.6483Z" fill="#472B16" />
            <path d="M38.2756 24.5931C40.2648 24.5931 41.8773 22.3514 41.8773 19.5862C41.8773 16.821 40.2648 14.5793 38.2756 14.5793C36.2865 14.5793 34.674 16.821 34.674 19.5862C34.674 22.3514 36.2865 24.5931 38.2756 24.5931Z" fill="white" />
            <path d="M39.7512 21.6483C40.8888 21.6483 41.811 20.3751 41.811 18.8046C41.811 17.2341 40.8888 15.9609 39.7512 15.9609C38.6136 15.9609 37.6914 17.2341 37.6914 18.8046C37.6914 20.3751 38.6136 21.6483 39.7512 21.6483Z" fill="#472B16" />
            <path d="M40.5492 28.1285C40.19 27.9532 39.7559 28.1018 39.5822 28.4584C38.5014 30.6424 36.3398 32.0033 33.6453 32.1875C30.4717 32.4014 27.2173 30.9276 25.4898 28.5029C25.4779 27.347 25.0767 26.2625 24.6635 25.5701C24.4599 25.2284 24.0198 25.1155 23.6755 25.3176C23.3312 25.5166 23.2145 25.9564 23.4151 26.2981C23.421 26.307 23.915 27.1598 24.0168 28.2058C24.1456 29.5073 23.6336 30.4849 22.457 31.195C22.1157 31.4001 22.0079 31.8428 22.2145 32.1816C22.3522 32.4044 22.5887 32.5262 22.8342 32.5262C22.963 32.5262 23.0917 32.4936 23.2085 32.4222C24.1905 31.828 24.7923 31.1 25.1306 30.3333C27.0886 32.4074 29.9927 33.6465 33.0016 33.6465C33.2501 33.6465 33.4956 33.6376 33.7441 33.6227C36.9655 33.4028 39.5642 31.7537 40.8786 29.0942C41.0552 28.7377 40.9055 28.3068 40.5492 28.1345V28.1285Z" fill="#280800" />
            <path d="M6.41346 14.4197C7.21139 13.4732 6.77265 11.8044 5.4335 10.6923C4.09435 9.58023 2.36191 9.44598 1.56398 10.3925C0.766051 11.3389 1.2048 13.0077 2.54394 14.1198C3.88309 15.2319 5.61553 15.3661 6.41346 14.4197Z" fill="#FFD15F" />
            <path d="M55.6423 9.67085C56.9175 7.29751 56.6656 4.69308 55.0797 3.85369C53.4937 3.01429 51.1743 4.25781 49.8991 6.63115C48.6239 9.00449 48.8757 11.6089 50.4617 12.4483C52.0476 13.2877 54.367 12.0442 55.6423 9.67085Z" fill="#FFD15F" />
            <path d="M49.0333 3.19059C49.2692 1.73893 48.6743 0.436335 47.7046 0.281155C46.735 0.125974 45.7577 1.17698 45.5219 2.62863C45.286 4.08028 45.8809 5.38288 46.8506 5.53806C47.8202 5.69324 48.7975 4.64224 49.0333 3.19059Z" fill="#FFD15F" />
            <path d="M96.9464 21.2888C96.9464 29.9773 90.3927 34.2948 82.2852 34.7762C82.2852 38.4073 82.372 41.5243 82.6205 43.1289C82.6744 43.4706 82.5277 43.8182 82.2313 44.0054C79.8092 45.5238 73.3573 45.3723 72.5071 44.5314C71.9173 43.946 71.8095 30.5122 72.1298 20.8104H71.9682C70.8933 20.8104 70.57 11.6941 71.0011 11.2692C71.4831 10.7878 77.7135 9.34961 82.9259 9.34961C91.0364 9.34961 96.9434 14.7338 96.9434 21.2888H96.9464ZM82.3391 26.6731C84.5426 26.5126 86.6892 25.6598 86.6892 20.7569C86.6892 18.9979 85.345 18.0381 83.5217 17.9846C83.1983 17.9846 82.878 17.9846 82.5008 18.0381L82.3391 26.6731Z" fill="#473300" />
            <path d="M215.035 13.2032C214.765 12.8467 214.472 12.5079 214.152 12.1811C212.05 10.0387 209.269 8.95706 206.305 8.67478C206.089 8.65398 205.874 8.64209 205.655 8.64209C196.416 8.64209 193.14 13.6519 193.14 28.9518C193.14 44.2517 200.874 45.6364 207.373 45.6364C213.873 45.6364 217.954 38.0682 217.954 24.2065C217.954 20.4387 217.373 16.2846 215.032 13.2032H215.035ZM206.194 39.3994C203.778 39.3994 203.67 35.5068 203.67 27.1393C203.67 18.7717 204.96 15.9459 206.248 15.9459C206.891 15.9459 208.397 18.8252 208.397 21.4906C208.505 23.0357 208.559 24.9018 208.559 27.1422C208.559 34.7105 208.451 39.4024 206.197 39.4024L206.194 39.3994Z" fill="#473300" />
            <path d="M164.706 21.1611C164.706 29.8496 158.152 34.1671 150.041 34.6485C150.041 38.2796 150.128 41.3966 150.377 43.0012C150.431 43.3429 150.284 43.6906 149.988 43.8778C147.565 45.3962 141.114 45.2446 140.263 44.4037C139.674 43.8183 139.566 30.3845 139.886 20.6827H139.724C138.65 20.6827 138.329 11.5664 138.757 11.1415C139.239 10.6601 145.47 9.22192 150.679 9.22192C158.79 9.22192 164.697 14.6062 164.697 21.1611H164.706ZM150.095 26.5454C152.299 26.3849 154.445 25.5321 154.445 20.6293C154.445 18.8702 153.101 17.9104 151.278 17.8569C150.955 17.8569 150.634 17.8569 150.257 17.9104L150.095 26.5454Z" fill="#473300" />
            <path d="M179.591 46.0259C172.34 46.0259 167.72 39.0966 167.72 28.4885C167.72 17.8805 171.535 8.76416 178.945 8.76416C186.354 8.76416 189.794 14.8407 189.794 20.332C189.794 20.6113 189.22 20.8549 189.028 20.9589C188.309 21.3452 187.543 21.6483 186.783 21.9425C185.932 22.2693 185.073 22.5724 184.205 22.8547C183.858 22.9676 183.51 23.0746 183.16 23.1756C182.959 23.2321 182.322 23.5025 182.226 23.241C182.166 23.0776 182.196 22.8517 182.202 22.6824C182.211 22.3882 182.235 22.091 182.256 21.7969C182.31 21.0451 182.358 20.2933 182.331 19.5386C182.307 18.8403 182.238 18.0945 181.92 17.4586C181.639 16.897 181.139 16.5434 180.495 16.5434C177.433 16.5434 176.253 37.7059 179.852 37.7059C181.612 37.7059 181.941 35.6111 181.992 34.3185C182.001 34.06 182.226 33.8668 182.483 33.8906C184.843 34.1075 189.681 35.7032 189.681 36.7461C189.681 39.1441 185.6 46.02 179.585 46.02L179.591 46.0259Z" fill="#473300" />
            <path d="M247.727 21.1607C247.727 26.8124 244.988 30.5445 240.853 32.675C242.865 35.0046 246.628 39.6668 248.523 40.6474C248.901 40.8435 249.035 41.3159 248.814 41.6784C247.748 43.4286 244.173 46.1594 242.089 46.1594C240.317 46.1594 235.644 40.5612 233.066 36.9925C233.114 39.5895 233.2 41.7438 233.398 42.9978C233.452 43.3395 233.308 43.6901 233.012 43.8743C230.59 45.3927 224.138 45.2412 223.291 44.4003C222.701 43.8149 222.593 30.3811 222.913 20.6793H222.752C221.677 20.6793 221.354 11.563 221.785 11.1381C222.267 10.6567 228.497 9.21851 233.709 9.21851C241.82 9.21851 247.727 14.6027 247.727 21.1577V21.1607ZM233.12 26.5449C235.32 26.3845 237.47 25.5317 237.47 20.6288C237.47 18.8697 236.126 17.9099 234.299 17.8565C233.976 17.8565 233.656 17.8565 233.278 17.9099L233.117 26.5449H233.12Z" fill="#473300" />
            <path d="M262.617 10.6626C263.344 13.1586 267.093 25.6416 268.73 30.2236C268.676 21.0537 269 10.8736 269.644 10.2347C270.826 9.06101 280.062 10.5022 280.062 11.141C280.062 13.0606 279.095 38.3267 280.062 42.9116C278.182 44.8311 270.233 45.6839 269.374 44.8311C268.569 44.0318 264.272 31.1833 261.964 24.7888C261.802 30.1196 261.641 36.4101 261.695 42.8611C261.695 43.4464 251.488 45.5799 251.488 44.5132C251.488 43.4464 252.24 14.6087 252.24 12.0503C252.24 9.85438 260.132 10.1367 262.072 10.2407C262.326 10.2555 262.542 10.4249 262.614 10.6686L262.617 10.6626Z" fill="#473300" />
            <path d="M102.749 19.8265C102.486 20.1059 102.225 20.3941 101.977 20.6972C96.4021 27.5315 97.4649 37.5571 104.351 43.0869C111.237 48.6197 121.338 47.5649 126.91 40.7306C128.144 39.2181 129.051 37.5482 129.644 35.8069C131.099 35.0373 132.422 33.9706 133.527 32.6215C137.934 27.2195 137.092 19.2947 131.649 14.9207C127.446 11.5452 121.731 11.2748 117.315 13.8124C116.851 12.4633 116.015 11.2183 114.815 10.2556C111.42 7.52778 106.435 8.04778 103.686 11.4174C101.683 13.8748 101.426 17.1641 102.743 19.8265H102.749Z" fill="#FFD15F" />
            <path d="M121.219 32.6627C121.159 32.6241 115.297 28.7374 116.106 22.1765C116.231 21.1543 115.501 20.2272 114.471 20.1024C113.444 19.9747 112.507 20.7027 112.381 21.7248C112.145 23.6384 112.315 25.3708 112.728 26.9159C111.169 26.9159 109.283 27.2487 107.384 28.3927C106.498 28.9276 106.217 30.0746 106.756 30.9511C107.109 31.5276 107.726 31.8455 108.36 31.8455C108.693 31.8455 109.028 31.7594 109.333 31.5751C111.426 30.3123 113.501 30.5975 114.459 30.8412C116.408 33.9493 118.98 35.6579 119.165 35.7797C119.483 35.9848 119.839 36.0828 120.189 36.0828C120.8 36.0828 121.402 35.7857 121.761 35.2389C122.327 34.3772 122.084 33.2243 121.219 32.6597V32.6627Z" fill="#473300" />
          </svg>
        </div>
        <!-- <div class="d-flex justify-content-center mt-52 mb-4">
          <svg width="544" height="8" viewBox="0 0 544 8" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="100%" height="8" rx="4" fill="#F9F9F9"/>
            <rect width="66.6%" height="8" rx="4" fill="#FFC747"/>
          </svg>
        </div>
        <div class="d-flex justify-content-center mb-120">
          <p class="text-sb-18px scale-text-black">팝콘 이용 약관에 동의해주세요.</p>
        </div> -->
        <form action="{{ route('parent.register.insert') }}" method="post" class="" onsubmit="">
          @csrf
          <input type="hidden" id="main_code" name="main_code" value="elementary">
          <input type="hidden" id="teach_seq" name="teach_seq" value="{{ session()->get('teachSeq') }}">
          <div>
            <p class="text-b-20px mb-12 mt-4">아이디</p>
            <div class="row w-100">
              <div class="col-9 ps-0 pe-sm-2 pe-xl-4">
                <label class="label-input-wrap w-100">
                  <input type="text" id="parent_id" name="parent_id" class="smart-ht-search border-gray rounded text-m-20px w-100 h-68" placeholder="아이디를 입력해주세요." autocomplete="parent_id" required>
                </label>
              </div>
              <div class="col-3 p-0">
                <button type="button" id="usernameCheck" name="usernameCheck" class="btn-lg-primary text-b-20px rounded scale-text-white scale-bg-black scale-bg-gray_06-hover w-100 text-center justify-content-center h-68">중복체크</button>
              </div>
              <div id="username-message" class="mt-2"></div>
            </div>
          </div>
          <div>
            <p class="text-b-20px mb-12 mt-4">비밀번호</p>
            <div class="row w-100">
              <div class="col-12 p-0">
                <label class="label-input-wrap w-100">
                  <input type="password" id="password" name="password" class="smart-ht-search border-gray rounded text-m-20px w-100 h-68" placeholder="비밀번호를 입력해주세요." autocomplete="password" required>
                </label>
              </div>
            </div>
            <div id="password-message" class="mt-2"></div>
          </div>
          <div>
            <p class="text-b-20px mb-12 mt-4">비밀번호 확인</p>
            <div class="row w-100">
              <div class="col-12 p-0">
                <label class="label-input-wrap w-100">
                  <input type="password" id="passwordCheck" name="passwordCheck" class="smart-ht-search border-gray rounded text-m-20px w-100 h-68" placeholder="비밀번호를 입력해주세요." autocomplete="passwordCheck" required>
                </label>
              </div>
            </div>
            <div id="passwordCheck-message" class="mt-2"></div>
          </div>
          <div>
            <p class="text-b-20px mb-12 mt-4">학부모님 성함</p>
            <div class="row w-100">
              <div class="col-12 p-0">
                <label class="label-input-wrap w-100">
                  <input type="text" id="parentName" name="parentName" class="smart-ht-search border-gray rounded text-m-20px w-100 h-68" placeholder="성함을 입력해주세요." required>
                </label>
              </div>
            </div>
          </div>
          <div>
            <p class="text-b-20px mb-12 mt-4">휴대폰 번호</p>
            <div class="row w-100">
              <div class="col-9 ps-0 pe-sm-2 pe-xl-4">
                <label class="label-input-wrap w-100">
                  <input type="text" id="phoneNumber" name="phoneNumber" class="smart-ht-search border-gray rounded text-m-20px w-100 h-68" placeholder="휴대폰 번호를 입력해주세요." required>
                </label>
              </div>
              <div class="col-3 p-0">
                <button type="button" id="phoneNumberCheck" class="btn-lg-primary text-b-20px rounded scale-text-white scale-bg-black scale-bg-gray_06-hover w-100 text-center justify-content-center h-68">확인</button>
              </div>
              <div id="phoneNumber-message" class="mt-2"></div>
            </div>
          </div>
          <div>
            <p class="text-b-20px mb-12 mt-4">이메일</p>
            <div class="row w-100">
              <div class="col-12 ps-0 pe-0">
                <label class="label-input-wrap w-100">
                  <input type="email" id="email" name="email" class="smart-ht-search border-gray rounded text-m-20px w-100 h-68" placeholder="이메일을 입력해주세요." autocomplete="email" required>
                </label>
              </div>
              <div id="email-message" class="mt-2"></div>
              <!-- <div class="col-3 p-0">
                <label class="label-input-wrap w-100">
                  <button type="button" class="btn-lg-primary text-b-20px rounded scale-text-white scale-bg-black scale-bg-gray_06-hover w-100 text-center justify-content-center h-68">인증하기</button>
                </label>
              </div> -->
            </div>
          </div>
          <div class="mt-5">
            <p class="text-b-24px mb-12 pt-4 border-top">자녀등록</p>
          </div>
          <div>
            <p class="text-b-20px mb-12 mt-4">학교</p>
            <div class="row w-100">
              <div class="col-9 ps-0 pe-sm-2 pe-xl-4">
                <label class="label-input-wrap w-100">
                  <input type="text" id="schoolName" name="schoolName" class="smart-ht-search border-gray rounded text-m-20px w-100 h-68" placeholder="학교를 입력해주세요." value="{{ session('schoolName') }}" readonly required>
                </label>
                <input type="hidden" id="schoolCode" name="schoolCode" value="{{ session('schoolCode') }}">
              </div>
              <div class="col-3 p-0">
                <button type="button" data-bs-toggle="modal" data-bs-target="#modal-1" class="btn-lg-primary text-b-20px rounded scale-text-white scale-bg-black scale-bg-gray_06-hover w-100 text-center justify-content-center h-68">주소검색</button>
              </div>
            </div>
          </div>
          <div>
            <div class="d-flex justify-content-between w-100 gap-2">
              <div class=" ps-0 pe-0 w-50">
                <p class="text-b-20px mb-12 mt-4">학년</p>
                <div class="d-inline-block select-wrap select-icon h-68 w-100">
                  {{-- <select class="border-gray lg-select text-sb-20px h-100 w-100">
                    <option value="">학년선택</option>
                    <option value="1">1학년</option>
                    <option value="2">2학년</option>
                    <option value="3">3학년</option>
                    <option value="4">4학년</option>
                    <option value="5">5학년</option>
                    <option value="6">6학년</option>
                  </select> --}}
                  <select id="studentGrade" name="studentGrade" class="border-gray lg-select text-sb-20px h-100 w-100" required>
                    <option value="">학년선택</option>
                    @foreach ($code as $item)
                    @if ($item->code_name !== '공통')
                    <option value="{{ $item->id }}" {{ session('gradeCode') == $item->id ? 'selected' : '' }}>{{ $item->code_name }}</option>
                    @endif
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="px-0 w-50">
                <p class="text-b-20px mb-12 mt-4">반</p>
                <label class="label-input-wrap w-100">
                  <input type="text" id="studentClass" name="studentClass" class="smart-ht-search border-gray rounded text-m-20px w-100 h-68" placeholder="반" required>
                </label>
              </div>
              {{-- <div class=" ps-0 pe-0">
                <p class="text-b-20px mb-12 mt-4">학번</p>
                <label class="label-input-wrap w-100">
                  <input type="text" id="studentNumber" name="studentNumber" class="smart-ht-search border-gray rounded text-m-20px w-100 h-68" placeholder="학번" required>
                </label>
              </div> --}}
            </div>
          </div>
          <div>
            <p class="text-b-20px mb-12 mt-4">자녀 이름</p>
            <div class="row w-100">
              <div class="col-12 px-0">
                <label class="label-input-wrap w-100">
                  <input type="text" id="studentName" name="studentName" class="smart-ht-search border-gray rounded text-m-20px w-100 h-68" placeholder="자녀의 이름을 입력해주세요." required>
                </label>
              </div>
              {{-- <div class="col-3 p-0">
                <button type="button" class="btn-lg-primary text-b-20px rounded scale-text-white scale-bg-black scale-bg-gray_06-hover w-100 text-center justify-content-center h-68">주소검색</button>
              </div> --}}
            </div>
          </div>
          <div>
            <p class="text-b-20px mb-12 mt-4">아이디</p>
            <div class="row w-100">
              <div class="col-9 ps-0 pe-sm-2 pe-xl-4">
                <label class="label-input-wrap w-100">
                  <input type="text" id="studentId" name="studentId" class="smart-ht-search border-gray rounded text-m-20px w-100 h-68" placeholder="자녀의 아이디를 입력해주세요." required>
                </label>
              </div>
              <div class="col-3 p-0">
                <button type="button" id="studentIdCheck" name="studentIdCheck" class="btn-lg-primary text-b-20px rounded scale-text-white scale-bg-black scale-bg-gray_06-hover w-100 text-center justify-content-center h-68">중복체크</button>
              </div>
              <div id="studentId-message" class="mt-2"></div>
            </div>
          </div>
          <div>
            <p class="text-b-20px mb-12 mt-4">비밀번호</p>
            <div class="row w-100">
              <div class="col-12 ps-0 pe-0">
                <label class="label-input-wrap w-100">
                  <input type="password" id="studentPassword" name="studentPassword" class="smart-ht-search border-gray rounded text-m-20px w-100 h-68" placeholder="비밀번호를 입력해주세요." autocomplete="off" required>
                </label>
              </div>
            </div>
            <div id="studentPassword-message" class="mt-2"></div>
          </div>
          <div>
            <p class="text-b-20px mb-12 mt-4">비밀번호 확인</p>
            <div class="row w-100">
              <div class="col-12 ps-0 pe-0">
                <label class="label-input-wrap w-100">
                  <input type="password" id="studentPasswordCheck" name="studentPasswordCheck" class="smart-ht-search border-gray rounded text-m-20px w-100 h-68" placeholder="비밀번호 확인" autocomplete="off" required>
                </label>
              </div>
            </div>
            <div id="studentPasswordCheck-message" class="mt-2"></div>
          </div>
          <div class="row w-100 mt-12 ">
            <div class="col-12 pe-0 ps-0">
              {{-- <button type="button" class="btn-lg-primary text-m-24px rounded-3 justify-content-center scale-bg-gray_01 scale-text-gray_05 scale-text-white-hover scale-bg-gray_05-hover w-100 ">자녀 추가등록</button> --}}
            </div>
          </div>
          <div class="row w-100 mt-12 ">
            {{-- <div class="col-6 pe-2 ps-0">
              <button type="button" class="btn-lg-primary text-m-24px rounded-3 justify-content-center scale-bg-gray_01 scale-text-gray_05 scale-text-white-hover scale-bg-gray_05-hover w-100 mt-80 mb-120">이전</button>
            </div> --}}
            <div class="col-12 px-0">
              <button type="submit" class="btn-lg-primary text-m-24px rounded-3 justify-content-center scale-text-white w-100 mt-80 mb-120">회원가입</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </main>
  <div id="modal-1" class="modal fade" tabindex="-1" aria-labelledby="modal-1-label" aria-hidden="true">
    <div class="modal-dialog modal-shadow-style rounded">
      <div class="modal-content border-none rounded p-3">
        <div class="modal-header border-bottom-0">
          <h1 class="modal-title fs-5 text-b-24px" id="">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="transform: scale(1.5);" class="me-2">
              <path d="M12.2598 4H11.7598V8H12.2598V4Z" fill="#FFC747" />
              <path d="M17.3794 7.58008H6.61938C6.23938 7.58008 5.88939 7.79008 5.72939 8.13008L5.29939 9.00008C5.16939 9.27008 5.35939 9.58008 5.65939 9.58008H18.3494C18.6494 9.58008 18.8394 9.27008 18.7094 9.00008L18.2794 8.13008C18.1094 7.79008 17.7594 7.58008 17.3894 7.58008H17.3794Z" fill="#FF5065" />
              <path d="M19 12H5C4.44771 12 4 12.4477 4 13V19C4 19.5523 4.44771 20 5 20H19C19.5523 20 20 19.5523 20 19V13C20 12.4477 19.5523 12 19 12Z" fill="#FFE195" />
              <path d="M18 9.58982H15L12.71 7.29982C12.32 6.90982 11.69 6.90982 11.3 7.29982L9.01 9.58982H6V19.9998H18V9.58982Z" fill="#FFC747" />
              <path d="M12 10.5801C12.5523 10.5801 13 10.1324 13 9.58008C13 9.02779 12.5523 8.58008 12 8.58008C11.4477 8.58008 11 9.02779 11 9.58008C11 10.1324 11.4477 10.5801 12 10.5801Z" fill="#F8FAFB" />
              <path d="M9 12H7.5C7.22 12 7 12.22 7 12.5V14C7 14.28 7.22 14.5 7.5 14.5H9C9.28 14.5 9.5 14.28 9.5 14V12.5C9.5 12.22 9.28 12 9 12Z" fill="white" />
              <path d="M16.5 12H15C14.72 12 14.5 12.22 14.5 12.5V14C14.5 14.28 14.72 14.5 15 14.5H16.5C16.78 14.5 17 14.28 17 14V12.5C17 12.22 16.78 12 16.5 12Z" fill="white" />
              <path d="M9 15.5H7.5C7.22 15.5 7 15.72 7 16V17.5C7 17.78 7.22 18 7.5 18H9C9.28 18 9.5 17.78 9.5 17.5V16C9.5 15.72 9.28 15.5 9 15.5Z" fill="white" />
              <path d="M16.5 15.5H15C14.72 15.5 14.5 15.72 14.5 16V17.5C14.5 17.78 14.72 18 15 18H16.5C16.78 18 17 17.78 17 17.5V16C17 15.72 16.78 15.5 16.5 15.5Z" fill="white" />
              <path d="M12.75 12H11.25C10.97 12 10.75 12.22 10.75 12.5V14C10.75 14.28 10.97 14.5 11.25 14.5H12.75C13.03 14.5 13.25 14.28 13.25 14V12.5C13.25 12.22 13.03 12 12.75 12Z" fill="white" />
              <path d="M12.2598 4H14.7898C15.0498 4 15.2598 4.21 15.2598 4.47V5.53C15.2598 5.79 15.0498 6 14.7898 6H12.2598V4Z" fill="#FF5065" />
            </svg>
            주소찾기
          </h1>
          <button type="button" class="btn-close close-btn" data-bs-dismiss="modal" aria-label="Close" style="transform: scale(2);"></button>
        </div>
        <div class="modal-body">
          <p class="text-sb-20px mb-3">학교명을 입력해주세요.</p>
          <div class="col-12 px-0 mb-3">
            <label class="label-input-wrap w-100">
              <input type="text" class="smart-ht-search border-gray rounded text-m-20px w-100" placeholder="제목을 입력해주세요." id="schoolSearchName" name="schoolSearchName" required>
            </label>
          </div>
          <p class="text-sb-20px mb-3">목록</p>
          <ul class="list-group">
            <li class="list-group-item px-2 py-5 text-center">학교명을 검색해주세요.</li>
          </ul>
        </div>
        <div class="modal-footer border-top-0">
          <button type="button" id="schoolListSearch" class="btn-lg-primary text-b-24px rounded scale-text-white w-100 justify-content-center search-btn">검색</button>
        </div>
      </div>
    </div>
  </div>
  <div id="toast" style=" font-size: 1.5rem; padding: 1.1rem;"></div>
  <script>
  const messageDiv = document.getElementById('username-message');
    const parentNameDiv = document.getElementById('parentName-message');
    const studentIdDiv = document.getElementById('studentId-message');
    const passwordDiv = document.getElementById('password-message');
    const passwordCheckDiv = document.getElementById('passwordCheck-message');
    const studentPasswordDiv = document.getElementById('studentPassword-message');
    const studentPasswordCheckDiv = document.getElementById('studentPasswordCheck-message');
    let isUsername = { value: false };
    let isStudentId = { value: false };
    let isPassword = false;
    let isPasswordCheck = false;

    $('#usernameCheck').on('click', function(event) {
        event.preventDefault();
        checkUsername.checkClick('parent_id', 'web', messageDiv, isUsername);
        console.log(isUsername);
    });

    $('#studentIdCheck').on('click', function(event) {
        event.preventDefault();
        checkUsername.checkClick('studentId', 'web', studentIdDiv, isStudentId);
    });

    $('form').on('submit', function(event) {
        if(!isUsername){
          event.preventDefault();
          messageDiv.textContent = "중복체크를 확인해 주세요.";
          messageDiv.style.color = "red";
          isUsername = false;
          scrollTo(0, 0);
          return;
        }
        if(!isStudentId){
          event.preventDefault();
          studentIdDiv.textContent = "중복체크를 확인해 주세요.";
          studentIdDiv.style.color = "red";
          isStudentId = false;
          scrollTo(0, 0);
          return;
        }
        if(!isPassword){
          event.preventDefault();
          passwordDiv.textContent = "비밀번호를 확인해 주세요.";
          passwordDiv.style.color = "red";
          isPassword = false;
          scrollTo(0, 0);
          return;
        }
        if(!isPasswordCheck){
          event.preventDefault();
          passwordCheckDiv.textContent = "비밀번호 확인을 확인해 주세요.";
          passwordCheckDiv.style.color = "red";
          isPasswordCheck = false;
          scrollTo(0, 0);
          return;
        }
    });

    let checkUsername = {
      checkClick: function(id, type, element, statusObj) {
      // 여기서 실제 중복 체크 로직을 수행합니다.
      // 예를 들어, 서버에 요청을 보내고 응답을 받아 처리합니다.
      // 이 예제에서는 단순히 "사용 가능"으로 가정합니다.
      const usernameElement = document.getElementById(id);
      if (usernameElement === null) {
        element.textContent = "아이디 입력 필드를 찾을 수 없습니다";
        element.style.color = "red";
        statusObj.value = false;
        return;
      }
      const username = usernameElement.value;
      if (username == '') {
        element.textContent = "아이디를 입력해주세요";
        element.style.color = "red";
        statusObj.value = false;
        return;
      } else if (username.length < 4) {
        element.textContent = "아이디는 4자 이상이어야 합니다";
        element.style.color = "red";
        statusObj.value = false;
        return;
      }else if ($('#parent_id').val() && $('#studentId').val() && ($('#parent_id').val() == $('#studentId').val())){
        element.textContent = "부모와 학생은 같은 아이디를 사용할 수 없습니다.";
        element.style.color = "red";
        statusObj.value = false;
        return;
      }

      $.ajax({
        url: '/parent/register/username/check',
        type: 'POST',
        data: {
        type: type,
        username: username,
        _token: '{{ csrf_token() }}'
        },
        success: function(response) {
          if (response.resultCode == 'success') {
            element.textContent = "사용 가능한 아이디입니다";
            element.style.color = "green";
            statusObj.value = true;
          } else {
            element.textContent = "이미 사용 중인 아이디입니다";
            element.style.color = "red";
            statusObj.value = false;
          }
        },
        error: function(response) {
          console.log(response);
          }
        });
      },
      checkInput: function() {
        if(statusObj.value){
          statusObj.value = false;
          return;
        }
      }
    }

    function searchSchools() {
      $.ajax({
        url: '/parent/register/school/list',
        type: 'POST', 
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        data: {
          schoolName: $('#schoolSearchName').val(), 
          _token: '{{ csrf_token() }}'
        }, 
        dataType: 'json',
        success: function(response) {
          let list;
          let html = $('<ul></ul>').addClass('list-group');
          if (response.resultCode == 'fail') {
            list = `<li class="list-group-item px-2 py-5 text-center">학교를 찾을 수 없습니다.</li>`;
            html.append(list);
            $('.list-group')?.remove();
            $('.modal-body').append(html);
            return false;
          } else {
            response.forEach(school => {
              list = `
                <li class="list-group-item px-2">
                    <div class="d-flex justify-content-between flex-row">
                        <div>
                            <div class="list-group-item-content d-flex flex-column gap-1">
                                <div class="justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <span class="label-text">학교명</span>
                                        <span class="school-name">${school.SCHUL_NM}</span>
                                        <input type="hidden" class="school-code" value="${school.SD_SCHUL_CODE}">
                                    </div>
                                    <div class="address-search-btn">
                                        <a href="https://map.naver.com/p/search/${school.ORG_RDNMA}" target="_blank" class="">지도</a>
                                    </div>
                                </div>
                                <div><span class="label-text">관할조직명</span> <span class="org-name">${school.JU_ORG_NM}</span></div>
                                <div><span class="label-text">시도교육청명</span> <span class="edu-office-name">${school.ATPT_OFCDC_SC_NM}</span></div>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn-lg-primary text-b-18px rounded scale-text-white scale-bg-black scale-bg-gray_06-hover text-center justify-content-center h-100 select-button">선택</button>
                        </div>
                    </div>
                </li>`;
              html.append(list);
            });
          }
          $('.list-group')?.remove();
          $('.modal-body').append(html);
        }
        , error: function(response) {
          console.log(response);
        }
      });
    }

    $(document).on('click', '.select-button', function() {
      $('#schoolName').val($(this).closest('.list-group-item').find('.school-name').text());
      $('#schoolCode').val($(this).closest('.list-group-item').find('.school-code').val());
      $('#modal-1').modal('hide');
      $('#modal-1').on('hidden.bs.modal', function() {
        $('#schoolSearchName').val('');
        $('.list-group')?.remove();
      });
    });

    // 검색 버튼 클릭 이벤트
    $('.search-btn').on('click', function() {
      searchSchools();
    });

    // 엔터 키 눌렀을 때 이벤트
    $('#schoolSearchName').on('keyup', function(event) {
      if (event.key === 'Enter') {
        searchSchools();
      }
    });

    const passwordChecker = {
      checkPassword: function(password, callback) {
        let message = "";
        let color = "";
        let status = false;
        if (password.length === 0) {
          message = "";
          color = "";
          status = false;
        } else if (password.length < 8) {
          message = "비밀번호는 8자 이상이어야 합니다";
          color = "red";
          status = false;
        } else {
          message = "";
          color = "green";
          status = true;
        }
        callback(message, color, status);
      },
      checkPasswordCheck: function(password, passwordCheck, callback) {
        let message = "";
        let color = "";
        let status = false;

        if (password.length == passwordCheck.length && password !== passwordCheck) {
          message = "비밀번호가 일치하지 않습니다";
          color = "red";
          status = false;
        }else{
          message = "";
          color = "green";
          status = true;
        }
        callback(message, color, status);
      },

      checkPasswordCheckBlur: function(password, passwordCheck, callback) {
        if(password !== passwordCheck){
          message = "비밀번호가 일치하지 않습니다";
          color = "red";
          status = false;
        }else{
          message = "";
          color = "green";
          status = true;
        }
        callback(message, color, status);
      }
    };


    function updatePasswordDiv(message, color, status, element) {
      element.textContent = message;
      element.style.color = color;
      isPassword = status;
    }

    function updatePasswordCheckDiv(message, color, status, element) {
      element.textContent = message;
      element.style.color = color;
      isPasswordCheck = status;
    }

    $('#password').on('input', function() {
      passwordChecker.checkPassword($('#password').val(), function(message, color, status) {
        updatePasswordDiv(message, color, status, $('#password-message')[0]);
      });
    });
    $('#password').on('blur', function() {
      passwordChecker.checkPassword($('#password').val(), function(message, color, status) {
        updatePasswordDiv(message, color, status, $('#password-message')[0]);
      });
    });
    $('#passwordCheck').on('input', function() {
      passwordChecker.checkPasswordCheck($('#password').val(), $('#passwordCheck').val(), function(message, color, status) {
        updatePasswordCheckDiv(message, color, status, $('#passwordCheck-message')[0]);
      });
    });
    $('#passwordCheck').on('blur', function() {
      passwordChecker.checkPasswordCheckBlur($('#password').val(), $('#passwordCheck').val(), function(message, color, status) {
        updatePasswordCheckDiv(message, color, status, $('#passwordCheck-message')[0]);
      });
    });

    $('#studentPassword').on('click', function(event) {
      event.preventDefault();
      passwordChecker.checkPassword($('#studentPassword').val(), function(message, color, status) {
        updatePasswordDiv(message, color, status, $('#studentPassword-message')[0]);
      });
    });

    $('#studentPassword').on('blur', function() {
      passwordChecker.checkPassword($('#studentPassword').val(), function(message, color, status) {
        updatePasswordDiv(message, color, status, $('#studentPassword-message')[0]);
      });
    });

    $('#studentPasswordCheck').on('input', function() {
      passwordChecker.checkPasswordCheck($('#studentPassword').val(), $('#studentPasswordCheck').val(), function(message, color, status) {
        updatePasswordCheckDiv(message, color, status, $('#studentPasswordCheck-message')[0]);
      });
    });

    $('#studentPasswordCheck').on('blur', function() {
      passwordChecker.checkPasswordCheckBlur($('#studentPassword').val(), $('#studentPasswordCheck').val(), function(message, color, status) {
        updatePasswordCheckDiv(message, color, status, $('#studentPasswordCheck-message')[0]);
      });
    });


$('#phoneNumberCheck').on('click', function(event) {
    event.preventDefault();
    const phoneNumber = $('#phoneNumber').val();
    const parentName = $('#parentName').val();
        const page = '/phone/auth/send/number';

        if(phoneNumber.replace(/[^0-9]/g, '').length !== 11){
            toast('유효한 휴대폰 번호인지 확인해주세요.');
            return;
        }
        $.ajax({
            url: page,
            type: 'POST',
            data: {
                user_phone: phoneNumber,
                user_seq: null,
                user_type: 'parent',
                user_name: parentName,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(result) {
              if(result.resultCode === 'success'){
                  toast('인증번호가 전송되었습니다. 인증번호는 3분동안 유효합니다.');
                  $(this).prop('disabled', true);
              } else if(result.resultCode === 'already'){
                  toast('이미 전송을 진행했습니다. 3분이 지난후 다시 전송해주세요.');
                  $(this).prop('disabled', true);
              } else {
                  toast('인증번호 전송에 실패하였습니다. 다시 시도해주세요. 유효한 휴대폰 번호인지 확인해주세요.');
                  $(this).prop('disabled', false);
              }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX Error:', textStatus, errorThrown);
                console.log('Response Text:', jqXHR.responseText);
                toast('오류가 발생했습니다. 다시 시도해주세요.');
            }
        });
        const authInputHtml = `
        <div class="row w-100 mt-2">
            <div class="col-9 ps-0 pe-sm-2 pe-xl-4">
                <label class="label-input-wrap w-100">
                    <input type="text" id="authNumber" name="authNumber" class="smart-ht-search border-gray rounded text-m-20px w-100 h-68" placeholder="인증번호를 입력해주세요." required>
                </label>
            </div>
            <div class="col-3 p-0">
                <button type="button" id="authCheck" class="btn-lg-primary text-b-20px rounded scale-text-white scale-bg-black scale-bg-gray_06-hover w-100 text-center justify-content-center h-68">인증하기</button>
            </div>
        </div>
            <div id="auth-message" class="mt-2"></div>
        `;
        $('#authNumber')?.closest('.row').remove();
        $('#phoneNumber').closest('.row').after(authInputHtml);
            $('#authCheck').on('click', function(event) {
            event.preventDefault();
            const authNumber = $('#authNumber').val();
            const authParameter = {
                user_seq: null,
                user_type: 'parent',
                user_phone: phoneNumber,
                user_auth: authNumber,
                _token: '{{ csrf_token() }}'
            };

          $.ajax({
              url: '/phone/auth/check/number/register',
              type: 'POST',
              data: authParameter,
              dataType: 'json',
              success: function(result) {
                  if(result.resultCode === 'success'){
                      $('#auth-message').html('<span class="text-success">인증에 성공했습니다.</span>');
                      $('#authCheck').prop('disabled', true);
                  } else if(result.resultCode === 'fail'){
                      $('#auth-message').html('<span class="text-danger">인증에 실패했습니다. 다시 시도해주세요.</span>');
                      $('#authCheck').prop('disabled', false);
                  }
              },
              error: function(jqXHR, textStatus, errorThrown) {
                  console.error('AJAX Error:', textStatus, errorThrown);
                  console.log('Response Text:', jqXHR.responseText);
                  $('#phoneNumber-message').html('<span class="text-danger">오류가 발생했습니다. 다시 시도해주세요.</span>');
              }
          });
        });
    });
  </script>
</body>
</html>
