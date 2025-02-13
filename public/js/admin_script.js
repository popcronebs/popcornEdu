function queryFetch(page, parameter, callback) {
    const xtken = document.querySelector("#csrf_token").value;
    fetch(page, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": xtken,
        },
        body: JSON.stringify(parameter),
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (result) {
            if (callback != undefined) {
                callback(result);
            }
        })
        .catch(function (error) {
            if (error != undefined) {
                callback(error);
            }
            console.log(error);
        });
}
function queryFormFetch(page, formData, callback) {
    const xtken = document.querySelector("#csrf_token").value;
    fetch(page, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": xtken,
        },
        body: formData,
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (result) {
            if (callback != undefined) {
                callback(result);
            }
        })
        .catch(function (error) {
            if (error != undefined) {
                callback(error);
            }
            console.log(error);
        });
}
//system_alert
function sAlert(
    title_str,
    content_str,
    type,
    callback1,
    callback2,
    btn_text1,
    btn_text2
) {
    const alert = document.querySelector("#system_alert");
    let btn1 = alert.querySelector(".msg_btn1");
    let btn2 = alert.querySelector(".msg_btn2");
    const title = alert.querySelector(".msg_title");
    const content = alert.querySelector(".msg_content");
    //초기화
    sAlertClear();
    let btn1_clone = btn1.cloneNode(true);
    btn1.parentNode.appendChild(btn1_clone);
    btn1.remove();
    btn1 = btn1_clone;

    let btn2_clone = btn2.cloneNode(true);
    btn2.parentNode.appendChild(btn2_clone);
    btn2.remove();
    btn2 = btn2_clone;

    //설정 type == '' or 1 alrt / 2 confirm
    if (type == 2 || type == 3) {
        btn1.hidden = false;
        btn2.hidden = false;
    }
    if (type == 3 || type == 4) {
        sAlertType3();
    }
    //값 넣기
    if ((title_str || "") != "") title.innerHTML = title_str;
    if ((content || "") != "") content.innerHTML = content_str;
    if ((btn_text1 || "") != "") btn1.innerHTML = btn_text1;
    if ((btn_text2 || "") != "") btn2.innerHTML = btn_text2;
    //이벤트 초기화 후 등록
    btn1.addEventListener("click", function () {
        if (callback1 != undefined) {
            callback1();
        }
        document
            .querySelector("#system_alert .modal")
            .classList.add("fade-out-animation");
        setTimeout(function () {
            document
                .querySelector("#system_alert .modal")
                .classList.remove("fade-out-animation");
            alert.hidden = true;
        }, 500); // Adjust the timeout duration to match the fade-out duration
    });
    btn2.addEventListener("click", function () {
        if (callback2 != undefined) {
            callback2();
        }
        alert.hidden = true;
    });
    alert.hidden = false;
}

function sAlertClear() {
    const alert = document.querySelector("#system_alert");
    const alert_footer = alert.querySelector(".modal-footer");
    const btn_close = alert.querySelector("[data-bs-dismiss]");
    let btn1 = alert.querySelector(".msg_btn1");
    let btn2 = alert.querySelector(".msg_btn2");
    const title = alert.querySelector(".msg_title");
    const content = alert.querySelector(".msg_content");
    //초기화
    btn1.hidden = false;
    btn2.hidden = true;
    title.innerHTML = "";
    content.innerHTML = "";
    btn1.innerHTML = "확인";
    btn2.innerHTML = "취소";

    btn_close.hidden = false;
    title.classList.remove("pt-4");
    title.classList.remove("text-b-20px");
    title.classList.remove("w-100");
    title.classList.remove("text-center");
    content.classList.remove("text-center");
    alert_footer.classList.add("flex-column");
    alert_footer.classList.remove("flex-row-reverse");
    alert_footer.classList.remove("pt-4");
    btn1.classList.remove("col");
    btn1.classList.remove("text-r-24px");
    btn1.classList.remove("py-3");
    btn2.classList.remove("col");
    btn2.classList.remove("text-r-24px");
}

function sAlertType3() {
    const alert = document.querySelector("#system_alert");
    const alert_footer = alert.querySelector(".modal-footer");
    const btn_close = alert.querySelector("[data-bs-dismiss]");
    let btn1 = alert.querySelector(".msg_btn1");
    let btn2 = alert.querySelector(".msg_btn2");
    const title = alert.querySelector(".msg_title");
    const content = alert.querySelector(".msg_content");

    btn_close.hidden = true;
    title.classList.add("pt-4");
    title.classList.add("text-b-20px");
    title.classList.add("w-100");
    title.classList.add("text-center");
    content.classList.add("text-center");
    alert_footer.classList.remove("flex-column");
    alert_footer.classList.add("flex-row-reverse");
    alert_footer.classList.add("pt-4");
    btn1.classList.add("col");
    btn1.classList.add("text-b-24px");
    btn1.classList.add("py-3");
    btn2.classList.add("col");
    btn2.classList.add("text-b-24px");
}

Date.prototype.format = function (f) {
    if (!this.valueOf()) return " ";

    var weekName = [
        "일요일",
        "월요일",
        "화요일",
        "수요일",
        "목요일",
        "금요일",
        "토요일",
    ];
    var weekName2 = ["일", "월", "화", "수", "목", "금", "토"];
    var d = this;

    return f.replace(
        /(yyyy|yy|MM|dd|E|hh|mm|ss|a\/p|MsM|dsd|hsh|msm|e)/gi,
        function ($1) {
            switch ($1) {
                case "yyyy":
                    return d.getFullYear();
                case "yy":
                    return (d.getFullYear() % 1000).zf(2);
                case "MM":
                    return (d.getMonth() + 1).zf(2);
                case "MsM":
                    return (d.getMonth() + 1).zf(1);
                case "dd":
                    return d.getDate().zf(2);
                case "dsd":
                    return d.getDate().zf(1);
                case "e":
                    return weekName2[d.getDay()];
                case "E":
                    return weekName[d.getDay()];
                case "HH":
                    return d.getHours().zf(2);
                case "hh":
                    return ((h = d.getHours() % 12) ? h : 12).zf(2);
                case "hsh":
                    return ((h = d.getHours() % 12) ? h : 12).zf(1);
                case "mm":
                    return d.getMinutes().zf(2);
                case "msm":
                    return d.getMinutes().zf(1);
                case "ss":
                    return d.getSeconds().zf(2);
                case "a/p":
                    return d.getHours() < 12 ? "오전" : "오후";
                default:
                    return $1;
            }
        }
    );
};

String.prototype.string = function (len) {
    var s = "",
        i = 0;
    while (i++ < len) {
        s += this;
    }
    return s;
};
String.prototype.zf = function (len) {
    return "0".string(len - this.length) + this;
};
Number.prototype.zf = function (len) {
    return this.toString().zf(len);
};

function toast(string, time) {
    if ((time || "") == "") time = 2000;
    const toast = document.getElementById("toast");

    toast.classList.contains("reveal")
        ? (clearTimeout(removeToast),
          (removeToast = setTimeout(function () {
              document.getElementById("toast").classList.remove("reveal");
          }, time)))
        : (removeToast = setTimeout(function () {
              document.getElementById("toast").classList.remove("reveal");
          }, time));
    toast.classList.add("reveal"), (toast.innerText = string);
}

function setCookie(cookieName, value, exdays) {
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var cookieValue =
        escape(value) +
        (exdays == null ? "" : "; expires=" + exdate.toGMTString());
    document.cookie = cookieName + "=" + cookieValue;
}
function deleteCookie(cookieName){
    var expireDate = new Date();
    expireDate.setDate(expireDate.getDate() - 1);
    document.cookie = cookieName + "= " + "; expires=" + expireDate.toGMTString();
}

function _excelDown(fileName, sheetName, sheetHtml) {
    var html = "";
    html += '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
    html += "    <head>";
    html +=
        '        <meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">';
    html += "        <xml>";
    html += "            <x:ExcelWorkbook>";
    html += "                <x:ExcelWorksheets>";
    html += "                    <x:ExcelWorksheet>";
    html += "                        <x:Name>" + sheetName + "</x:Name>"; // 시트이름
    html +=
        "                        <x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions>";
    html += "                    </x:ExcelWorksheet>";
    html += "                </x:ExcelWorksheets>";
    html += "            </x:ExcelWorkbook>";
    html += "        </xml>";
    html += "    </head>";
    html += "    <body>";

    // ----------------- 시트 내용 부분 -----------------
    html += sheetHtml;
    // ----------------- //시트 내용 부분 -----------------

    html += "    </body>";
    html += "</html>";

    // 데이터 타입
    var data_type = "data:application/vnd.ms-excel";
    var ua = window.navigator.userAgent;
    var blob = new Blob([html], { type: "application/csv;charset=utf-8;" });

    if (
        (ua.indexOf("MSIE ") > 0 ||
            !!navigator.userAgent.match(/Trident.*rv\:11\./)) &&
        window.navigator.msSaveBlob
    ) {
        // ie이고 msSaveBlob 기능을 지원하는 경우
        navigator.msSaveBlob(blob, fileName);
    } else {
        // ie가 아닌 경우 (바로 다운이 되지 않기 때문에 클릭 버튼을 만들어 클릭을 임의로 수행하도록 처리)
        var anchor = window.document.createElement("a");
        anchor.href = window.URL.createObjectURL(blob);
        anchor.download = fileName;
        document.body.appendChild(anchor);
        anchor.click();

        // 클릭(다운) 후 요소 제거
        document.body.removeChild(anchor);
    }
}


// 현재 url 기억
function rememberScreenOnSubmit(is_reset) {
    if(is_reset) localStorage.setItem('rememberedScreenMoveCnt', 0);
    const rememberedScreenMoveCnt = localStorage.getItem('rememberedScreenMoveCnt')*1+1;
    // 현재 url 저장.
    localStorage.setItem('rememberedScreenMoveUrl', location.href);
    localStorage.setItem('rememberedScreenMoveCnt', rememberedScreenMoveCnt);
}


// 기억한 url로 이동
function goToRememberedScreen() {
    let rememberedScreenMoveCnt = localStorage.getItem('rememberedScreenMoveCnt')*1;
    localStorage.setItem('rememberedScreenMoveCnt', 0);
    // hisotry.go(-) 를 쓰면 이동한상태 그대로가 아니라 변형되어있어서 변경처리.
    if(rememberedScreenMoveCnt < 1) rememberedScreenMoveCnt = 1;
    for (let i = 0; i < rememberedScreenMoveCnt; i++){
        history.back();
    }
}
