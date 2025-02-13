const messageDiv = document.getElementById('username-message');
    const parentNameDiv = document.getElementById('parentName-message');
    const studentIdDiv = document.getElementById('studentId-message');
    const passwordDiv = document.getElementById('password-message');
    const passwordCheckDiv = document.getElementById('passwordCheck-message');
    const studentPasswordDiv = document.getElementById('studentPassword-message');
    const studentPasswordCheckDiv = document.getElementById('studentPasswordCheck-message');
    let isUsername = false; // 실제 로직에 따라 변경
    let isStudentId = false;
    let isPassword = false;
    let isPasswordCheck = false;

    $('#usernameCheck').on('click', function(event) {
        event.preventDefault();
        checkUsername.checkClick('parent_id', 'parent', messageDiv, isUsername);
    });

    $('#studentIdCheck').on('click', function(event) {
        event.preventDefault();
        checkUsername.checkClick('studentId', 'student', studentIdDiv, isStudentId);
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
      checkClick: function(id, type, element, variable) {
      // 여기서 실제 중복 체크 로직을 수행합니다.
      // 예를 들어, 서버에 요청을 보내고 응답을 받아 처리합니다.
      // 이 예제에서는 단순히 "사용 가능"으로 가정합니다.
      const usernameElement = document.getElementById(id);
      if (usernameElement === null) {
        element.textContent = "아이디 입력 필드를 찾을 수 없습니다";
        element.style.color = "red";
        variable = false;
        return;
      }
      const username = usernameElement.value;
      if (username == '') {
        element.textContent = "아이디를 입력해주세요";
        element.style.color = "red";
        variable = false;
        return;
      } else if (username.length < 4) {
        element.textContent = "아이디는 4자 이상이어야 합니다";
        element.style.color = "red";
        variable = false;
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
            variable = true;
          } else {
            element.textContent = "이미 사용 중인 아이디입니다";
            element.style.color = "red";
            variable = false;
          }
        },
        error: function(response) {
          console.log(response);
          }
        });
      },
      checkInput: function() {
        if(variable){
          variable = false;
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
  
    $('#usernameCheck').on('click', function(event) {
      event.preventDefault();
      checkUsername.checkClick();
    });
    $('#usernameCheck').on('input', function(event) {
      event.preventDefault();
      checkUsername.checkInput();
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