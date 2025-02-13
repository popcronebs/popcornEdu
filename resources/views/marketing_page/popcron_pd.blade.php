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

  <title>팝콘 소개</title>
</head>
<style>
main{
  background-color: #abc0d1;
  height: 100vh;
}
  .swiper-slide{
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .img-container-wrapper {
      background-color: #abc0d1;
    object-fit: cover;
  }
  .img-container-wrapper .img-container{
    width: 40%;
        line-height: 0px;
  }
  .img-container-wrapper .img-container img{
    width: 100%;
  }
  .swiper-button-prev{
    display: none;
  }
  .swiper-button-next{
    display: none;
  }
  @media (max-width: 768px) {
    .img-container-wrapper .img-container{
      width: 100%;
    }
  }
</style>
<body>
  <main>
    <div class="img-container-wrapper d-flex flex-column justify-content-center align-items-center">
      <div class="img-container">
        <img src="{{ asset('/images/marketing_img/kakaoimg/레이어 01.png') }}" alt="팝콘 소개 이미지 1">
      </div>
      <div class="img-container">
        <a href="{{ route('popcron.pd.math') }}">
          <img src="{{ asset('/images/marketing_img/kakaoimg/레이어 02.png') }}" alt="팝콘 소개 이미지 2">
        </a>
      </div>
      <div class="img-container">
        <a href="{{ route('popcron.pd.english') }}">
          <img src="{{ asset('/images/marketing_img/kakaoimg/레이어 03.png') }}" alt="팝콘 소개 이미지 3">
        </a>
      </div>
      <div class="img-container">
        <a href="{{ route('popcron.pd.hanja') }}">
          <img src="{{ asset('/images/marketing_img/kakaoimg/레이어 04.png') }}" alt="팝콘 소개 이미지 4">
        </a>
      </div>
      <div class="img-container">
          <img src="{{ asset('/images/marketing_img/kakaoimg/레이어 05.png') }}" alt="팝콘 소개 이미지 5">
      </div>
    </div>
  </main>
</body>

</html>
