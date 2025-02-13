// 상수 정의
const MESSAGES = {
  USERNAME_REQUIRED: "아이디를 입력해주세요",
  USERNAME_TOO_SHORT: "아이디는 4자 이상이어야 합니다",
  USERNAME_AVAILABLE: "사용 가능한 아이디입니다",
  USERNAME_TAKEN: "이미 사용 중인 아이디입니다",
  DUPLICATE_CHECK: "중복체크를 확인해 주세요.",
  PASSWORD_TOO_SHORT: "비밀번호는 8자 이상이어야 합니다",
  PASSWORD_MISMATCH: "비밀번호가 일치하지 않습니다",
  SERVER_ERROR: "서버 오류가 발생했습니다",
  PHONE_INVALID: "유효한 휴대폰 번호인지 확인해주세요.",
  AUTH_SENT: "인증번호가 전송되었습니다. 인증번호는 3분동안 유효합니다.",
  AUTH_ALREADY_SENT: "이미 전송을 진행했습니다. 3분이 지난후 다시 전송해주세요.",
  AUTH_SEND_FAIL: "인증번호 전송에 실패하였습니다. 다시 시도해주세요.",
  AUTH_SUCCESS: "인증에 성공했습니다.",
  AUTH_FAIL: "인증에 실패했습니다. 다시 시도해주세요."
};

const SELECTORS = {
  usernameMessage: '#username-message',
  parentNameMessage: '#parentName-message',
  studentIdMessage: '#studentId-message',
  passwordMessage: '#password-message',
  passwordCheckMessage: '#passwordCheck-message',
  studentPasswordMessage: '#studentPassword-message',
  studentPasswordCheckMessage: '#studentPasswordCheck-message',
  authMessage: '#auth-message',
  phoneNumberMessage: '#phoneNumber-message'
};
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
// 유틸리티 함수
const updateElement = (selector, message, color) => {
  const element = document.querySelector(selector);
  if (element) {
    element.textContent = message;
    element.style.color = color;
  }
};

const scrollToTop = () => window.scrollTo(0, 0);

// 폼 유효성 검사 함수
const createFormValidator = () => {
  const state = {
    isUsername: false,
    isStudentId: false,
    isPassword: false,
    isPasswordCheck: false
  };

  const validateForm = (event) => {
    if (!state.isUsername || !state.isStudentId || !state.isPassword || !state.isPasswordCheck) {
      event.preventDefault();
      showValidationMessage();
      scrollToTop();
    }
  };

  const showValidationMessage = () => {
    if (!state.isUsername) updateElement(SELECTORS.usernameMessage, MESSAGES.DUPLICATE_CHECK, 'red');
    if (!state.isStudentId) updateElement(SELECTORS.studentIdMessage, MESSAGES.DUPLICATE_CHECK, 'red');
    if (!state.isPassword) updateElement(SELECTORS.passwordMessage, MESSAGES.PASSWORD_TOO_SHORT, 'red');
    if (!state.isPasswordCheck) updateElement(SELECTORS.passwordCheckMessage, MESSAGES.PASSWORD_MISMATCH, 'red');
  };

  return { state, validateForm };
};

// 사용자 이름 체크 함수
const checkUsername = async (id, type) => {
  const usernameElement = document.getElementById(id);
  if (!usernameElement) {
    updateElement(SELECTORS.usernameMessage, "아이디 입력 필드를 찾을 수 없습니다", 'red');
    return false;
  }

  const username = usernameElement.value.trim();
  if (username.length < 4) {
    updateElement(SELECTORS.usernameMessage, MESSAGES.USERNAME_TOO_SHORT, 'red');
    return false;
  }

  try {
    const response = await fetch('/parent/register/username/check', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      },
      body: JSON.stringify({ type, username })
    });

    const data = await response.json();
    const isAvailable = data.resultCode === 'success';
    updateElement(
      SELECTORS.usernameMessage,
      isAvailable ? MESSAGES.USERNAME_AVAILABLE : MESSAGES.USERNAME_TAKEN,
      isAvailable ? 'green' : 'red'
    );
    return isAvailable;
  } catch (error) {
    console.error('Username check error:', error);
    updateElement(SELECTORS.usernameMessage, MESSAGES.SERVER_ERROR, 'red');
    return false;
  }
};

// 비밀번호 체크 함수
const checkPassword = (password) => {
  if (password.length === 0) return { message: "", color: "", status: false };
  if (password.length < 8) return { message: MESSAGES.PASSWORD_TOO_SHORT, color: "red", status: false };
  return { message: "", color: "green", status: true };
};

const checkPasswordMatch = (password, passwordCheck) => {
  if (password !== passwordCheck) {
    return { message: MESSAGES.PASSWORD_MISMATCH, color: "red", status: false };
  }
  return { message: "", color: "green", status: true };
};

// 전화번호 인증 함수
const sendAuthNumber = async (phoneNumber, parentName) => {
  if (phoneNumber.replace(/[^0-9]/g, '').length !== 11) {
    toast(MESSAGES.PHONE_INVALID);
    return false;
  }

  try {
    const response = await fetch('/phone/auth/send/number', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      },
      body: JSON.stringify({
        user_phone: phoneNumber,
        user_seq: null,
        user_type: 'parent',
        user_name: parentName
      })
    });

    const result = await response.json();
    if (result.resultCode === 'success') {
      toast(MESSAGES.AUTH_SENT);
      return true;
    } else if (result.resultCode === 'already') {
      toast(MESSAGES.AUTH_ALREADY_SENT);
    } else {
      toast(MESSAGES.AUTH_SEND_FAIL);
    }
  } catch (error) {
    console.error('Phone auth error:', error);
    toast(MESSAGES.SERVER_ERROR);
  }
  return false;
};

const checkAuthNumber = async (phoneNumber, authNumber) => {
  try {
    const response = await fetch('/phone/auth/check/number/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      },
      body: JSON.stringify({
        user_seq: null,
        user_type: 'parent',
        user_phone: phoneNumber,
        user_auth: authNumber
      })
    });

    const result = await response.json();
    if (result.resultCode === 'success') {
      updateElement(SELECTORS.authMessage, MESSAGES.AUTH_SUCCESS, 'green');
      return true;
    } else {
      updateElement(SELECTORS.authMessage, MESSAGES.AUTH_FAIL, 'red');
    }
  } catch (error) {
    console.error('Auth check error:', error);
    updateElement(SELECTORS.authMessage, MESSAGES.SERVER_ERROR, 'red');
  }
  return false;
};

const schoolSearch = async (schoolName) => {
  try {
    const response = await fetch('/parent/register/school/list', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      },
      body: JSON.stringify({ schoolName })
    });

    if (!response.ok) {
      throw new Error('Network response was not ok');
    }

    const data = await response.json();
    let list;
    let html = document.createElement('ul');
    html.classList.add('list-group');

    if (data.resultCode === 'fail') {
      list = `<li class="list-group-item px-2 py-5 text-center">학교를 찾을 수 없습니다.</li>`;
      html.insertAdjacentHTML('beforeend', list);
      document.querySelector('.list-group')?.remove();
      document.querySelector('.modal-body').appendChild(html);
      return false;
    } else {
      data.forEach(school => {
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
        html.insertAdjacentHTML('beforeend', list);
      });
    }
    document.querySelector('.list-group')?.remove();
    document.querySelector('.modal-body').appendChild(html);
  } catch (error) {
    console.error('School search error:', error);
  }
};

const schoolSelectButton = () => {
  document.querySelector('.select-button').addEventListener('click', (event) => {
    const schoolName = event.target.closest('.list-group-item').querySelector('.school-name').textContent;
    const schoolCode = event.target.closest('.list-group-item').querySelector('.school-code').value;
    document.querySelector('#schoolName').value = schoolName;
    document.querySelector('#schoolCode').value = schoolCode;
    $("#modal-1").on('hidden.bs.modal', function () {
      console.log('Modal is closed');
    }).modal("hide");
  });
}

// 이벤트 리스너 설정
document.addEventListener('DOMContentLoaded', () => {
  const formValidator = createFormValidator();

  document.querySelector('#usernameCheck').addEventListener('click', async (event) => {
    event.preventDefault();
    formValidator.state.isUsername = await checkUsername('parent_id', 'parent');
  });

  document.querySelector('#studentIdCheck').addEventListener('click', async (event) => {
    event.preventDefault();
    formValidator.state.isStudentId = await checkUsername('studentId', 'student');
  });

  document.querySelector('#schoolListSearch').addEventListener('click', async (event) => {
    event.preventDefault();
    await schoolSearch(document.querySelector('#schoolSearchName').value);
    schoolSelectButton();
  }); 

  document.querySelector('#password').addEventListener('input', (event) => {
    const result = checkPassword(event.target.value);
    updateElement(SELECTORS.passwordMessage, result.message, result.color);
    formValidator.state.isPassword = result.status;
  });

  document.querySelector('#passwordCheck').addEventListener('input', (event) => {
    const password = document.querySelector('#password').value;
    const result = checkPasswordMatch(password, event.target.value);
    updateElement(SELECTORS.passwordCheckMessage, result.message, result.color);
    formValidator.state.isPasswordCheck = result.status;
  });

  document.querySelector('#phoneNumberCheck').addEventListener('click', async (event) => {
    event.preventDefault();
    const phoneNumber = document.querySelector('#phoneNumber').value;
    const parentName = document.querySelector('#parentName').value;
    if (await sendAuthNumber(phoneNumber, parentName)) {
      // 인증번호 입력 필드 추가
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
      document.querySelector('#phoneNumber').closest('.row').insertAdjacentHTML('afterend', authInputHtml);

      document.querySelector('#authCheck').addEventListener('click', async () => {
        const authNumber = document.querySelector('#authNumber').value;
        await checkAuthNumber(phoneNumber, authNumber);
      });
    }
  });

  document.querySelector('form').addEventListener('submit', (event) => {
    formValidator.validateForm(event);
  });
});