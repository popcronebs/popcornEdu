    // 학부모 프로필 메뉴 열기
    function parentLayoutProfileOpen(vthis) {
      // data-main-top-user-menu  toggle hidden
      // vthis의 bottom 위치를 가져와서
      // datamaintopusermenu의 top을 vthis의 bottom + 50px 으로 설정
      const bottom = vthis.getBoundingClientRect().bottom;
      const layout_div_navbar = document.querySelector('[data-main-top-user-menu]');
      layout_div_navbar.style.top = bottom + 20 + 'px';

      layout_div_navbar.toggleAttribute('hidden');
      if (!layout_div_navbar.hidden) {
        // 외부 클릭 감지를 위한 이벤트 리스너 추가
        document.addEventListener('click', function handleClickOutside(event) {
            // 클릭된 요소가 layout_div_navbar 이거나 그 자식이 아닐 경우
            if (!layout_div_navbar.contains(event.target) && !vthis.contains(event.target)) {
                layout_div_navbar.setAttribute('hidden', true);
                document.removeEventListener('click', handleClickOutside); // 리스너 제거
            }
        });
    }
  }
  // 페이지 이동
  function parentLayoutPageMove(vthis) {
      const page_url = vthis.dataset.layoutMenu;
      location.href = page_url;
  }
