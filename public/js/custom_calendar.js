var selected_day = {
    year: '',
    month: '',
    week: '',
    day: '',
    date: ''
};
// set_Calendar('#learncal_div_main');
// set_Calendar('#learncal_div_main2');

function set_Calendar(div_main_id) {
    var len = getWeekNo(getLastDay(div_main_id));
    const main_div = document.querySelector(div_main_id);

    main_div.querySelectorAll('.tby_cal td').forEach(function(element) {
        // element.querySelector('.all_newst').innerHTML = '';
        // element.querySelector('.all_outst').innerHTML = '';
        // element.querySelector('.all_birthday').innerHTML = '';

        element.querySelector('.calnum').className = 'calnum';
        element.querySelector('.calnum').innerHTML = '';
        element.querySelector('.calnum').removeAttribute('title');
        element.querySelector('.calnum').removeAttribute('data-original-title');

        // element.querySelector('.cal_month').className = 'cal_month';
        // element.querySelector('.cal_month').innerHTML = '';

        element.querySelectorAll('.cal_indiv').forEach(function(child1) {
            child1.innerHTML = '';
            child1.hidden = true;
            child1.removeAttribute('title');
            child1.removeAttribute('data-original-title');
        });
    });


    var last_index = 0;
    var year = main_div.querySelector('.stp2_year').innerHTML;
    var month = main_div.querySelector('.stp2_month').innerHTML;

    for (var i = 1; i < len + 1; i++) {
        var num_date = get_SelWeesNoDate(div_main_id, i);
        var sel_date_str = num_date;
        var num_dateList = num_date.split('|');
        var indexDate = ["", "", "", "", "", "", ""];
        if (i == 1 && num_dateList.length - 7 != 0) {
            for (var ii = 0; ii < 7; ii++) {
                var sumnum = 7 - num_dateList.length;
                try {
                    main_div.querySelectorAll('.caltr_' + i + ' .day' + ((ii + 1) + sumnum) + ' .calnum').forEach(
                        function(element) {
                            element.innerHTML = num_dateList[ii].substr(8);
                            element.classList.add(num_dateList[ii].substr(8));
                        });

                    main_div.querySelectorAll('.caltr_' + i + ' .day' + ((ii + 1) + sumnum) + ' .cal_indiv')
                        .forEach(function(element) {
                            element.hidden = false;
                        });
                    var init_schedule = main_div.querySelectorAll('.caltr_' + i + ' .day' + ((ii + 1) + sumnum) +
                        ' .cal_schedule_list_wrapper > *');
                    for (let zz = 0; zz < init_schedule.length; zz++) {
                        if (init_schedule[zz].classList.contains('copy')) {
                            init_schedule[zz].remove();
                        }
                    }

                } catch (e) {
                    console.log(e.message);
                }
            }
        } else {
            for (var ii = 0; ii < 7; ii++) {
                try {
                    var num = '';
                    var dMonth = '';
                    num = num_dateList[ii].substr(8);
                    dMonth = num_dateList[ii].substr(5, 5);
                    main_div.querySelectorAll('.caltr_' + i + ' .day' + (ii + 1) + ' .cal_month').forEach(function(
                        element) {
                        element.innerHTML = dMonth;
                        element.classList.add(dMonth);
                        element.hidden = true;
                    });
                    main_div.querySelectorAll('.caltr_' + i + ' .day' + (ii + 1) + ' .calnum').forEach(function(
                        element) {
                        element.innerHTML = num;
                        element.classList.add(num);
                        element.style.color = '';
                        element.querySelectorAll('.cal_indiv').forEach(function(child) {
                            child.hidden = false;
                        });
                    });

                    var chk_holy_date = year + '-' + month + '-' + num;

                    if (month != num_dateList[ii].substr(5, 2)) {
                        main_div.querySelectorAll('.caltr_' + i + ' .day' + (ii + 1) + ' .calnum').forEach(function(
                            element) {
                            element.closest('td').classList.add('bg-light');
                            element.classList.add('text-secondary');
                        });
                    } else {
                        main_div.querySelectorAll('.caltr_' + i + ' .day' + (ii + 1) + ' .calnum').forEach(function(
                            element) {
                            element.closest('td').classList.remove('bg-light');
                            element.classList.remove('text-secondary');
                        });
                    }

                    var init_schedule = main_div.querySelectorAll('.caltr_' + i + ' .day' + (ii + 1) +
                        ' .cal_schedule_list_wrapper > *');
                    for (let z = 0; z < init_schedule.length; z++) {
                        if (init_schedule[z].classList.contains('copy')) {
                            init_schedule[z].remove();
                        }
                    }

                } catch (e) {}
            }
        }
        last_index = i
    }
    if (last_index != 6) {
        main_div.querySelectorAll('.caltr_6').forEach(function(element) {
            element.hidden = true;
        });
    } else {
        main_div.querySelectorAll('.caltr_6').forEach(function(element) {
            element.hidden = false;
        });
    }

    var now = new Date().format('yyyy-MM-dd');
    var nowyear = new Date().format('yyyy');
    var nowweek = getWeekNo(now);
    var nowmonth = new Date().format('MM');
    var nowday = new Date().getDay()+1;
    if (month == nowmonth && year == nowyear) {
        main_div.querySelectorAll('.tby_cal > tr:nth-child(' + nowweek + ') > td:nth-child(' + nowday + ') .calnum')
            .forEach(function(element) {
                // element.style.borderRadius = '50%';
                element.classList.add('text-success');
                element.classList.add('fw-bold');
            });
    } else {
        main_div.querySelectorAll('.tby_cal > tr:nth-child(' + nowweek + ') > td:nth-child(' + nowday + ') .calnum')
            .forEach(function(element) {
                element.style.border = '';
                element.style.backgroundColor = '';
                element.style.color = '';
                element.style.textAlign = '';
                element.style.padding = '';
                element.style.borderRadius = '';
                element.style.font = '';
                element.style.marginRight = '';
            });
    }
    if (selected_day.month != month || selected_day.year != year) {
        main_div.querySelectorAll('.tby_cal > tr').forEach(function(row) {
            if (selected_day.week - 1 > 0)
                row.children[selected_day.week - 1].children[selected_day.day - 1].querySelector(
                    '.cal_content').hidden = true;
        });
    } else {
        main_div.querySelectorAll('.tby_cal > tr').forEach(function(row) {
            if (selected_day.week - 1 > 0)
                row.children[selected_day.week - 1].children[selected_day.day - 1].querySelector(
                    '.cal_content').hidden = false;
        });
    }

    setTimeout(function() {
        td_Height(div_main_id);
        main_div.querySelector('#divStep2').style.display = '';
        main_div.querySelectorAll('#pc_main .divStep2_default').forEach(function(element) {
            element.style.display = '';
        });
    }, 100);
    bottomBorderNone(div_main_id);
}

function getWeekNo(v_date_str) {

    var now = new Date();
    var firstDay = v_date_str.substr(0, 8) + '01';
    var weeno = getYearWeekNo(v_date_str) - getYearWeekNo(firstDay);
    return weeno + 1;
}

function getLastDay(div_main_id) {
    const main_div = document.querySelector(div_main_id);
    var year = main_div.querySelector('.stp2_year').innerHTML;
    var month = main_div.querySelector('.stp2_month').innerHTML;
    var now = new Date(year + '-' + month + '-01');
    var lastday = new Date(now.getYear() + 1900, now.getMonth() + 1, 0).format('yyyy-MM-dd');
    return lastday;
}

function getYearWeekNo(v_date_str) {
    var day = v_date_str;
    var splitDay = day.split("-");
    var startYearDay = '1/1/' + splitDay[0];
    var today = splitDay[1] + '/' + splitDay[2] + '/' + splitDay[0];
    var dt = new Date(startYearDay);
    var tDt = new Date(today);
    var diffDay = (tDt - dt) / 86400000; // Date 함수 기준 하루를 뭔 초로 나눴는지 모르겠음.
    // 1월 1일부터 현재날자까지 차이에서 7을 나눠서 몇주가 지났는지 확인을 함
    var weekDay = parseInt(diffDay / 7) + 1;
    // 요일을 기준으로 1월 1일보다 이전 요일이라면 1주가 더 늘었으므로 +1 시켜줌.
    if (tDt.getDay() < dt.getDay())
        weekDay += 1;

    return weekDay;
}

function get_SelWeesNoDate(div_main_id, num) {
    const main_div = document.querySelector(div_main_id);
    var yearElement = main_div.querySelector('.stp2_year');
    var monthElement = main_div.querySelector('.stp2_month');
    var year = yearElement.innerHTML;
    var month = monthElement.innerHTML;
    var now = new Date(year + '-' + month + '-01');
    var beforStr = new Date(now.getYear() + 1900, now.getMonth() + 1, 0).format('yyyy-MM-');
    var lastday = new Date(now.getYear() + 1900, now.getMonth() + 1, 0).format('dd') * 1;
    var renStr = '';
    var reStr = [];

    for (var i = 1; i <= lastday; i++) {

        if (num == getWeekNo(beforStr + ((i + 100) + '').substr(1))) {
            renStr += beforStr + ((i + 100) + '').substr(1) + '|';
        }
    }

    if (num == 1 && renStr.split('|').length - 1 != 7) {
        reStr = renStr.split('|');
        var len = 7 - (renStr.split('|').length - 1);
        var conStr = '';
        for (let i = len - 1; i > -1; i--) {
            conStr += new Date(year, month - 1, -i).format('yyyy-MM-dd') + '|'
        }
        reStr.forEach(d => {
            if (d != '') {
                conStr += d + '|';
            }
        })
        renStr = conStr;
    } else if (renStr.split('|').length - 1 != 7) {
        reStr = renStr.split('|');
        var len = 7 - (renStr.split('|').length - 1);
        var conStr = '';
        reStr.forEach(d => {
            if (d != '') {
                conStr += d + '|';
            }
        })
        for (let i = 1; i < len + 1; i++) {
            conStr += new Date(year, month, i).format('yyyy-MM-dd') + '|'
        }

        renStr = conStr;
    }


    renStr = renStr.substr(0, renStr.length - 1);
    return renStr;
}

function td_Height(div_main_id) {
    // const main_div = document.querySelector(div_main_id);
    // var chr = 0;
    // var tbyCals = main_div.querySelectorAll('.tby_cal tr');
    // if (tbyCals[5].classList.contains('hidden')) {
    //     chr = 4;
    //     var tbCalendars = main_div.querySelectorAll('.tb_calendar');
    //     tbCalendars.forEach(function(tbCalendar) {
    //         var tbyCals = tbCalendar.querySelectorAll('.tby_cal tr');
    //         tbyCals[5].style.display = 'none';
    //     });
    // } else {
    //     var trs = main_div.querySelectorAll('.tby_cal tr');
    //     trs[5].style.display = '';
    // }

    // var tds = main_div.querySelectorAll('.tb_calendar .tby_cal tr td');
    // var td_h1 = 600 / ((tds.length - chr) / 4);

    // var tbyCals = main_div.querySelectorAll('.tby_cal tr');
    // if (tbyCals.length == 6) {
    //     td_h1 = 600 / (tbyCals.length - (chr / 4));
    // }


    // //tbyCals의 가로사이즈가 600px가 안되면 td_h1 = 50px
    // if (tbyCals[0].offsetWidth < 600) {
    //     td_h1 = 40;
    // }
    // var tds = main_div.querySelectorAll('.tb_calendar .tby_cal tr td');
    // tds.forEach(function(td) {
    //     td.style.height = td_h1 + 'px';
    // });
}

function click_NextMon(div_main_id, type1, type2) {
    const main_div = document.querySelector(div_main_id);
    var first_type = ''
    if (type1 != 'today_key') {
        event.stopPropagation();
        first_type = type1;
    } else {
        first_type = 'today';
    }

    var selected_year = main_div.querySelectorAll('.stp2_year')[0].innerHTML;
    var selected_month = main_div.querySelectorAll('.stp2_month')[0].innerHTML;
    var current_year = new Date().format('yyyy');
    var current_month = new Date().format('MM');

    switch (first_type) {
        case "year":
            var year = parseInt(main_div.querySelector('.stp2_year').innerHTML);
            if (type2 == 'next') {
                year++;
            } else if (type2 == 'prev') {
                year--;
            } else {
                // 그대로
            }
            main_div.querySelector('.stp2_year').innerHTML = year;
            break;
        case "month":
            var month = parseInt(main_div.querySelector('.stp2_month').innerHTML);
            if (type2 == 'next') {
                if (month == 12) {
                    main_div.querySelector('.stp2_month').innerHTML = '01';
                    click_NextMon(div_main_id, 'year', 'next');
                    return;
                }
                month++;
            } else {
                if (month == 1) {
                    main_div.querySelector('.stp2_month').innerHTML = '12';
                    click_NextMon(div_main_id, 'year', 'prev');
                    return;
                }
                month--;
            }
            month = (100 + month);
            main_div.querySelector('.stp2_month').innerHTML = (month + '').substr(1);
            break;
        case "today":
            var year = new Date().format('yyyy');
            var month = new Date().format('MM');

            main_div.querySelector('.stp2_year').innerHTML = year;
            main_div.querySelector('.stp2_month').innerHTML = month;
            break;
    }
    var weekno = main_div.querySelector('#tb_weeks .act .week_no')
    if (weekno != undefined)
        set_TimeTableDate(div_main_id, weekno.innerHTML);

    // selectTimeTable('all');
    // selectClass();
    var divClassSelActs = main_div.querySelectorAll('#divClassSel .act');
    if (divClassSelActs.lnegth > 0) {
        divClassSelActs.forEach(function(element) {
            element.classList.remove('act');
        });
    }
    var tbTimetableActs = main_div.querySelectorAll('#tb_timetableaca .act');
    if (tbTimetableActs.length > 0) {
        tbTimetableActs.forEach(function(element) {
            element.classList.remove('act');
        });
    }


    if (first_type == 'today' && selected_month != current_month) {
        set_Calendar(div_main_id);
        set_TbWeekCount(div_main_id);
        setTimeout(function() {
            main_div.querySelector('.tby_cal').style.display = '';
        }, 100);

        var year = main_div.querySelector('.stp2_year').innerHTML;
        var month = main_div.querySelector('.stp2_month').innerHTML;

        if (selected_day.week != '' && selected_day.day != '') {
            if (selected_day.month != month || selected_day.year != year) {
                var calIndivs = main_div.querySelectorAll('.tby_cal > div:nth-child(' + selected_day.week +
                    ') > div:nth-child(' + selected_day.day + ') .cal_indiv');
                calIndivs.forEach(function(calIndiv) {
                    calIndiv.hidden = false;
                });

                var ch = main_div.querySelectorAll('.tby_cal > div:nth-child(' + selected_day.week +
                    ') > div:nth-child(' + selected_day.day + ')');
                ch[0].style.border = '1px solid #e3e6f0';
            } else {
                var calIndivs = main_div.querySelectorAll('.tby_cal > div:nth-child(' + selected_day.week +
                    ') > div:nth-child(' + selected_day.day + ') .cal_indiv');
                calIndivs.forEach(function(calIndiv) {
                    calIndiv.hidden = true;
                });

                var ch = main_div.querySelectorAll('.tby_cal > div:nth-child(' + selected_day.week +
                    ') > div:nth-child(' + selected_day.day + ')');
                ch[0].querySelector('.cal_content').hidden = true;
                ch[0].style.border = '2px solid #4e73df';
            }
        }
        var date = new Date().format('dd');
        var now = new Date().format('yyyy-MM-dd');
        var nowweek = getWeekNo(now);

        var today_dom = main_div.querySelectorAll('.cal_month.' + month + '-' + date);
        var today_parent = today_dom[0].parentNode.parentNode;
        var days = today_parent.className.replace('text-center day', '');
        if (days.indexOf('dAct') !== -1) {
            days = days.replace('dAct', '');
        }
        today_parent.classList.remove('dAct');

    } else if (first_type == 'today' && (selected_month == current_month) && (selected_year == current_year)) {
        var date = new Date().format('dd');
        var now = new Date().format('yyyy-MM-dd');
        var nowweek = getWeekNo(now);

        var today_dom = main_div.querySelectorAll('.cal_month.' + month + '-' + date);
        var today_parent = today_dom[0].parentNode.parentNode;
        var days = today_parent.className.replace('text-center day', '');
        if (days.indexOf('dAct') !== -1) {
            days = days.replace('dAct', '');
        }
        today_parent.classList.remove('dAct');

    } else {
        set_Calendar(div_main_id);
        set_TbWeekCount(div_main_id);

        var year = main_div.querySelector('.stp2_year').innerHTML;
        var month = main_div.querySelector('.stp2_month').innerHTML;

        if (selected_day.week != '' && selected_day.day != '') {
            if (selected_day.month != month || selected_day.year != year) {
                var calIndivs = main_div.querySelectorAll('.tby_cal > div:nth-child(' + selected_day.week +
                    ') > div:nth-child(' + selected_day.day + ') .cal_indiv');
                calIndivs.forEach(function(calIndiv) {
                    calIndiv.hidden = false;
                });

                var ch = main_div.querySelectorAll('.tby_cal > div:nth-child(' + selected_day.week +
                    ') > div:nth-child(' + selected_day.day + ')');
                ch[0].style.border = '1px solid #e3e6f0';
            } else {
                var calIndivs = main_div.querySelectorAll('.tby_cal > div:nth-child(' + selected_day.week +
                    ') > div:nth-child(' + selected_day.day + ') .cal_indiv');
                calIndivs.forEach(function(calIndiv) {
                    calIndiv.hidden = true;
                });

                var ch = main_div.querySelectorAll('.tby_cal > div:nth-child(' + selected_day.week +
                    ') > div:nth-child(' + selected_day.day + ')');
                ch[0].querySelector('.cal_content').hidden = true;
                ch[0].style.border = '2px solid #2e59d9';
            }
        }
    }
}

function set_TbWeekCount(div_main_id) {
    const main_div = document.querySelector(div_main_id);
    var now = new Date().format('yyyy-MM-dd');
    var nowweek = getWeekNo(now);
    var is_act_len = main_div.querySelectorAll('#tb_weeks .act').length;

    var len = getWeekNo(getLastDay(div_main_id));
    for (var i = 1; i <= 6; i++) {
        if (i <= len) {
            var trElements = main_div.querySelectorAll('#tb_weeks .tr' + i);
            trElements.forEach(function(trElement) {
                trElement.hidden = false;
            });
        } else {
            var trElements = main_div.querySelectorAll('#tb_weeks .tr' + i);
            trElements.forEach(function(trElement) {
                trElement.hidden = true;
            });
        }

        if (nowweek == i && is_act_len == 0) {
            var trElements = main_div.querySelectorAll('#tb_weeks .tr' + i);
            trElements.forEach(function(trElement) {
                trElement.classList.add('act');
            });
            set_TimeTableDate(div_main_id, nowweek);
        }
    }
}

function set_TimeTableDate(div_main_id, weekno) {
    const main_div = document.querySelector(div_main_id);
    var num_date = get_SelWeesNoDate(div_main_id, weekno);
    var sel_date_str = num_date;
    var num_dateList = num_date.split('|');
    var indexDate = ["", "", "", "", "", "", ""];
    if (weekno == 1 && num_dateList.length - 7 != 0) {

        for (var ii = 0; ii < 7; ii++) {
            if ((ii + 1) + sumnum == 8)
                break;
            var otThElements = main_div.querySelectorAll('.ot_th' + (ii + 1));
            otThElements.forEach(function(otThElement) {
                otThElement.hidden = true;
            });
            var sumnum = 7 - num_dateList.length;
            try {
                var otThElements = main_div.querySelectorAll('.ot_th' + ((ii + 1) + sumnum));
                otThElements.forEach(function(otThElement) {
                    otThElement.hidden = false;
                });

                var thElements = main_div.querySelectorAll('.th' + ((ii + 1) + sumnum));
                thElements.forEach(function(thElement) {
                    thElement.innerHTML = num_dateList[ii].substr(8);
                });
            } catch (e) {
                console.log(e.message);
            }
        }
    } else {
        for (var ii = 0; ii < 7; ii++) {
            try {
                var num = '';
                num = num_dateList[ii].substr(8);
                var otThElements = main_div.querySelectorAll('.ot_th' + (ii + 1));
                otThElements.forEach(function(otThElement) {
                    otThElement.hidden = false;
                });

                var thElements = main_div.querySelectorAll('.th' + (ii + 1));
                thElements.forEach(function(thElement) {
                    thElement.innerHTML = num;
                });
            } catch (e) {
                var otThElements = main_div.querySelectorAll('.ot_th' + (ii + 1));
                otThElements.forEach(function(otThElement) {
                    otThElement.hidden = true;
                });
            }
        }
    }
}

// 달력 제일 하단 border none
function bottomBorderNone(id){
    // table > 마지막 tr > td
    let is_none_border = false;
    let last_tr = 0;
    for(let i = 3; i < 6; i++){
        document.querySelector(id).querySelectorAll('table tbody tr')[i].querySelectorAll('td').forEach(function(e, idx){
            if(idx == 0 && e.querySelector('.calnum').innerHTML == ''){
                is_none_border = true;
                last_tr = i-1;
            }
            if(is_none_border){
                e.classList.add('border-0');
                e.classList.add('bg-white');
            }else{
                e.classList.remove('border-0');
                e.classList.remove('bg-white');
            }
            if(i == 5){
                e.classList.add('border-0');
            }
        });
    }
    document.querySelector(id).querySelectorAll('table tbody tr')[last_tr].querySelectorAll('td').forEach(function(e, idx){
        e.classList.add('border-0');
    });

}