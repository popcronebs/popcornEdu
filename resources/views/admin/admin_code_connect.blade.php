@php
    // main_code 없는 메인 분류 이름가져오기.
    // main_code 있는 메인 분류 하위 메뉴 가져오기.
    // 비동도 추가 했으므로 수정시 확인 필요.
    $grade_codes = $codes_all->where('code_category', 'grade')->where('code_step', '=', 1)->sortBy('code_idx');
    $subject_codes = $codes_all->where('code_category', 'subject')->where('code_step', '!=', 0)->sortBy('code_idx');
    $series_codes = $codes_all->where('code_category', 'series')->where('code_step', '=', 1)->sortBy('code_idx');
    $series2_codes = $codes_all->where('code_category', 'series')->where('code_step', '=', 2)->sortBy('code_idx');
@endphp
{{-- btn 닫기 --}}
<div class="container" id="code_connect_div_main">
    <div class="row">
        <h4>선택</h4>
        <div class="col-12">
            {{-- 시리즈 --}}
            <div class="col-12 row">
                <div class="bg-light p-2 text-center col-2 border fw-bold d-flex align-items-center justify-content-center">시리즈</div>
                <div class="p-2 text-center col border series_codes">
                    @if(!empty($series_codes))
                    @foreach ($series_codes as $series_code)
                        <input type="radio" name="radio_code_connect" class="btn-check series" id="code_connect_radio_code_{{ $series_code->id }}"
                            autocomplete="off" code_seq="{{ $series_code->id }}" onclick="codeConnectLectBtn(this)">
                        <label class="btn btn-outline-primary hpx-40 rounded-0" for="code_connect_radio_code_{{ $series_code->id }}">
                            {{ $series_code->code_name }}
                        </label>
                    @endforeach
                    @endif
                </div>
            </div>
            {{-- 시리즈 항목 --}}
            <div class="col-12 row">
                <div class="bg-light p-2 text-center col-2 border fw-bold d-flex align-items-center justify-content-center">시리즈 항목</div>
                <div class="p-2 text-center col border series2_codes">
                    @if(!empty($series2_codes))
                    @foreach ($series2_codes as $series2_code)
                        <input type="radio" name="radio_code_connect" class="btn-check series2" id="code_connect_radio_code_{{ $series2_code->id }}"
                            autocomplete="off" code_seq="{{ $series2_code->id }}" code_pt="{{ $series2_code->code_pt }}" onclick="codeConnectSettingHidden(true);"> <label class="btn btn-outline-primary hpx-40 rounded-0" for="code_connect_radio_code_{{ $series2_code->id }}" hidden> {{ $series2_code->code_name }} </label> @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="text-center">
        <button class="btn btn-outline-secondary mt-2" onclick="codeConnectDownBoxShow(this);" id="code_btn_connect_setting">
            {{-- 로딩 --}}
            <div class="spinner-border spinner-border-sm" role="status" hidden></div>
            설정
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                <path d="M8 11L3 6h10z"/>
            </svg>
    </button>
    </div>

    {{-- 설정 --}}
    
    <div class="col-12" id="code_div_connect_setting" hidden>
        <h4>설정</h4>
        {{-- 학년 --}}
        <div class="col-12 row"> <div class="bg-light p-2 text-center col-2 border fw-bold d-flex align-items-center justify-content-center">학년</div>
            <div class="p-2 text-center col border grade_codes">
                @if(!empty($grade_codes))
                @foreach ($grade_codes as $grade_code)
                    <input type="checkbox" name="code_connect_chk_code_connect_setting" class="btn-check grade" id="code_connect_chk_code_set_{{ $grade_code->id }}"
                        autocomplete="off" code_seq="{{ $grade_code->id }}">
                    <label class="btn btn-outline-primary hpx-40 rounded-0" for="code_connect_chk_code_set_{{ $grade_code->id }}">
                        {{ $grade_code->code_name }}
                    </label>
                @endforeach
                @endif
            </div>
        </div>
        {{-- 과목 --}}
        <div class="col-12 row">
            <div class="bg-light p-2 text-center col-2 border fw-bold d-flex align-items-center justify-content-center">과목</div>
            <div class="p-2 text-center col border subject_codes">
                @if(!empty($subject_codes))
                @foreach ($subject_codes as $subject_code)
                    <input type="checkbox" name="code_connect_chk_code_connect_setting" class="btn-check subject" id="code_connect_chk_code_set_{{ $subject_code->id }}"
                        autocomplete="off" code_seq="{{ $subject_code->id }}">
                    <label class="btn btn-outline-primary hpx-40 rounded-0" for="code_connect_chk_code_set_{{ $subject_code->id }}">
                        {{ $subject_code->code_name }}
                    </label>
                @endforeach
                @endif
            </div>
        </div>
    </div>
    
    <div class="text-center mt-4">
        <button class="btn btn-outline-primary mt-2" onclick="codeConnectClose();">목록으로 돌아가기</button>
        <button class="btn btn-primary mt-2" onclick="codeConnectInsert();">저장</button>
    </div>



    {{-- copy_ --}}
    <div hidden>
        <input type="" name="" class="btn-check " id="copy_inp"
        autocomplete="off" code_seq="" onclick="">
        <label class="btn btn-outline-primary hpx-40 rounded-0 me-1" for="copy_inp"></label>
    </div>
</div>



<script>
    const div_connect_add = document.querySelector('#code_div_connect_add');
    const div_connect_setting = document.querySelector('#code_div_connect_setting');

    // 시리즈, 시리즈, 학년, 과목 
    // 비동기로 가져오기.
    function codeConnectInit(){
        document.querySelector('#code_div_connect_add').hidden = false;
        codeConnectCodeSelect('series', 1);
        codeConnectCodeSelect('series', 2);
        codeConnectCodeSelect('grade', 1);
        codeConnectCodeSelect('subject', 1);
    }
    function codeConnectCodeSelect(code_category, code_step){
        const page = '/manage/code/select';
        const parameter = {
            'code_step':code_step,
            'code_category':code_category,
        };
        queryFetch(page, parameter, function(result){
            if((result.resultCode||'') == 'success'){
                codeConnectSetInputBox(code_category, code_step, result.codes);
            }
        });
    }
    function codeConnectSetInputBox(code_category, code_step, codes){
        const main_div = document.querySelector('#code_div_connect_add');
        code_step = code_step == 1 ? '' : code_step;
        const bundle_div = main_div.querySelector('.'+code_category+code_step+'_codes');
        const tag_name = code_category == 'series' ? 'radio_code_connect' : 'code_connect_chk_code_connect_setting';
        const tag_id = code_category == 'series' ? 'code_connect_radio_code_' : 'code_connect_chk_code_set_';
        const tag_type = code_category == 'series' ? 'radio' : 'checkbox';
        bundle_div.innerHTML = '';

        const copy_inp = main_div.querySelector('#copy_inp').cloneNode(true);
        const copy_label = main_div.querySelector('#copy_inp').nextElementSibling.cloneNode(true);
        copy_inp.setAttribute('name', tag_name);
        for(let i = 0; i < codes.length; i++){
            const code = codes[i];
            const inp = copy_inp.cloneNode(true);
            const label = copy_label.cloneNode(true);
            inp.setAttribute('id', tag_id+code.id);
            inp.setAttribute('code_seq', code.id);
            inp.setAttribute('type', tag_type);
            if(code_category == 'series'){
                if(code_step == 2){
                    inp.setAttribute('onclick', 'codeConnectSettingHidden(this)');
                    inp.setAttribute('code_pt', code.code_pt);
                }else{
                    inp.setAttribute('onclick', 'codeConnectLectBtn(this)');
                }
            }
            
            label.setAttribute('for', tag_id+code.id);
            label.innerHTML = code.code_name;
            bundle_div.appendChild(inp);
            bundle_div.appendChild(label);
            if(code_category == 'series' && code_step == 2){
                label.hidden = true;
            }
        }
        
    }

    // 시리즈 선택시 하위 시리즈 보이게.
    function codeConnectLectBtn(vthis){

        // 시리즈 선택시 하위 시리즈 보이게 수정. 
        const code_pt = vthis.getAttribute('code_seq');
        const code_pt2 = div_connect_add.querySelectorAll('input[code_pt]');
        code_pt2.forEach(element => {
            if(element.getAttribute('code_pt') == code_pt)
                element.nextElementSibling.hidden = false;
            else
                element.nextElementSibling.hidden = true;
        });
        codeConnectSettingHidden(true);
    }

    //
    function codeConnectSettingHidden(after_click){
        const code_chk = div_connect_setting.querySelectorAll('input:checked');
        code_chk.forEach(element => {
            element.checked = false;
        });
        div_connect_setting.hidden = true;

        after_click = (after_click || false);
        if(after_click){
            const btn_connect_setting = document.querySelector('#code_btn_connect_setting');
            // 0.2 초 뒤에 클릭.
            setTimeout(function(){
                btn_connect_setting.click();
            }, 200);
        }
    }

    // 목록으로 돌아가기
    function codeConnectClose(){
        div_connect_add.hidden = true;
        div_connect_setting.hidden = true;
        //초기화 
        const code_pt2 = div_connect_add.querySelectorAll('input');
        code_pt2.forEach(element => {
            element.checked = false;
            if(element.getAttribute('code_pt') != null)
                element.nextElementSibling.hidden = true;
        });

        const code_chk = div_connect_setting.querySelectorAll('input:checked');
        code_chk.forEach(element => {
            element.checked = false;
        });
    }

    //
    function codeConnectDownBoxShow(){
        // 라디오 버튼이 선택되어있는지 확인.
        const code_radio = div_connect_add.querySelectorAll('input[name="radio_code_connect"]:checked');
        if(code_radio.length < 1){
            toast('선택된 항목이 없습니다.');
            return;
        }
        const sel_readio = code_radio[0];
        const code_seq = sel_readio.getAttribute('code_seq');
        codeConnectSelect(code_seq);
    }

    // 저장
    function codeConnectInsert(){
        const code_radio = div_connect_add.querySelectorAll('input[name="radio_code_connect"]:checked');
        if(code_radio.length < 1){
            toast('선택된 항목이 없습니다.');
            return;
        }
        const sel_readio = code_radio[0];
        const code_seq = sel_readio.getAttribute('code_seq');

        // 체크박스 버튼이 선택되어있는지 확인.
        const code_chk = div_connect_setting.querySelectorAll('input[name="code_connect_chk_code_connect_setting"]:checked');
        if(code_chk.length < 1){
            toast('선택된 항목이 없습니다.');
            return;
        }
        let code_pts = '';
        code_chk.forEach(element => {
            if(code_pts != '') code_pts += ',';
            code_pts += element.getAttribute('code_seq');
        });
        
        const page = '/manage/code/connect/insert';
        const parameter = {
            code_seq: code_seq,
            code_pts: code_pts,
        };
        queryFetch(page, parameter, function(result){
            if((result.resultCode||'') == 'success'){
                toast('저장되었습니다.');
            }
            else{
                toast('저장에 실패하였습니다.');
            }
        });
    }

    // 선택 분류의 다대다 연결된 분류 가져오기.
    function codeConnectSelect(code_seq){
        //로딩 시작
        const btn_connect_setting = document.querySelector('#code_btn_connect_setting');
        btn_connect_setting.querySelector('.spinner-border').hidden = false;
        
        const page = '/manage/code/connect/select';
        const parameter = {
            code_seq: code_seq,
        };
        queryFetch(page, parameter, function(result){
            //로딩 끝
            btn_connect_setting.querySelector('.spinner-border').hidden = true;

            if((result.resultCode||'') == 'success'){
                for(let i = 0; i < result.code_connects.length; i++){
                    const code_connect = result.code_connects[i];
                    const code_pt = code_connect.code_pt;
                    const tag = div_connect_add.querySelectorAll('#code_connect_chk_code_set_'+code_pt);
                    if(tag.length > 0) tag[0].nextElementSibling.click();
                }
                div_connect_setting.hidden = false;   
            }
            else{
                toast('데이터를 가져오는데 실패하였습니다.');
            }
        });
    }
</script>