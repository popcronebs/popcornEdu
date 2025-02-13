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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="{{ asset('css/reset.css?5') }}">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="{{ asset('js/admin_script.js?19') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <title>팝콘 소개</title>
</head>
<style>
  .swiper-slide{
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .swiper-slide img {
    height: 100vh;
    object-fit: cover;
  }
  .swiper-button-prev{
    display: none;
  }
  .swiper-button-next{
    display: none;
  }
  .swiper-pagination-progressbar {
    background-color: #e0e0e0; /* 진행 바의 배경색 */
    height: 10px !important;
  }
  .swiper-pagination-progressbar-fill {
    background-color: #222222 !important; /* 진행 바의 채워진 부분 색상 */
  }
</style>
<body>
  <main>
    <div class="swiper" style="background-color: #ff7471;">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
            <img src="{{ asset('/images/marketing_img/메세지_한자_1.jpg') }}" alt="팝콘 한자 소개 이미지 1">
        </div>
        <div class="swiper-slide">
            <img src="{{ asset('/images/marketing_img/메세지_한자_2.jpg') }}" alt="팝콘 한자 소개 이미지 2">
        </div>
        <div class="swiper-slide">
            <img src="{{ asset('/images/marketing_img/메세지_한자_3.jpg') }}" alt="팝콘 한자 소개 이미지 3">
        </div>
      </div>
      <div class="swiper-pagination"></div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
    </div>
  </main>
</body>
<script>
  const swiper = new Swiper('.swiper', {
    direction: 'horizontal',
    loop: true,
    effect: 'creative',
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
      type : "progressbar",
      // 페이지네이션 컬러 변경
    },
    creativeEffect: {
        prev: {
          translate: [0, 0, -400],
        },
        next: {
          translate: ["100%", 0, 0],
        },
    },
  });
</script>
</html>
