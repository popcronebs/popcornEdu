@extends('layout.layout')

{{-- 타이틀 --}} @section('head_title') 대쉬보드 @endsection

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
    <style>
        .main-slider-card:hover .ht-btn-edit{
            color:white;
            background: #FFD43F;
        }
    </style>
    {{-- <link rel="stylesheet" href="{{ asset('css/reset.css') }}"> --}}
    <div class="main-wrap">

        <div class="sub-title">
            <h2 class="text-sb-42px">{{ session()->get('teach_name') }} 선생님
                <span class="ht-make-title on text-r-20px py-2 px-3 ms-1">
                    {{ session()->get('group_name') }}
                </span>
            </h2>
        </div>
        <div class="setion-block">
            <div
                class="sh-title-wrap align-items-sm-center justify-content-sm-between justify-content-start flex-column flex-sm-row">
                <div class="right-text">
                    <div>
                        <img src="{{ asset('images/calendar_chk_icon.svg') }}" alt="">
                    </div>
                    <p class="text-sb-28px">오늘의 현황 요약</p>
                </div>
                <div class="left-text">
                    <p id="maing_p_now_time" class="gray-color text-m-20px"></p>
                    <script>
                        // {{ date('y년 n월 d일') }} 월요일 오후 3시 35분 기준
                        const now_str = new Date().format('yy년 MsM월 dsd일 E a/p hsh시 msm분 기준')
                        document.getElementById('maing_p_now_time').innerText = now_str
                    </script>
                </div>
            </div>

            <div class="row content-block">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="card-box px-4 py-3 mb-2">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-row lh-base ">
                                <p class="text-b-24px gray-color">{{ date('y년 n월 d일') }}</p>
                                <p class="text-b-24px gray-color"><b class="black-color">신규 등록</b>입니다.</p>
                            </div>
                            <div>
                                <p class="gray-color">
                                    <span class="black-color text-sb-42px">{{ $new_cnt }}</span>명</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="card-box px-4 py-3 mb-2">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-row lh-base ">
                                <p class="text-b-24px gray-color">{{ date('y년 n월 d일') }}</p>
                                <p class="text-b-24px gray-color"><b class="black-color">재등록</b>입니다.</p>
                            </div>
                            <div>
                                <p class="gray-color">
                                    <span class="black-color text-sb-42px">{{ $readd_cnt }}</span>명</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="card-box px-4 py-3 mb-2">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-row lh-base gray-color">
                                <p class="text-b-24px gray-color">{{ date('y년 n월 d일') }}</p>
                                <p class="text-b-24px gray-color"><b class="black-color">신규상담</b>입니다.</p>
                            </div>
                            <div>
                                <p class="gray-color"><span class="black-color text-sb-42px">{{ $new_counsel_cnt }}</span>명</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3 mb-2">
                    <div class="card-box px-4 py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-row lh-base gray-color">
                                <p class="text-b-24px gray-color">{{ date('y년 n월 d일') }}</p>
                                <p class="text-b-24px gray-color"><b class="black-color">만료</b>입니다.</p>
                            </div>
                            <div>
                                <p class="gray-color"><span class="black-color text-sb-42px">{{ $expire_cnt }}</span>명</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="setion-block">
            <div class="sh-title-wrap justify-content-between">
                <div class="right-text">
                    <div>
                        <img src="{{ asset('images/calendar_chk_icon.svg') }}" alt="">
                    </div>
                    <p class="text-sb-28px">소속선택</p>
                </div>
                <div class="left-text">
                    <button class="btn p-0 d-inline-flex">
                        <img src="{{ asset('images/calendar_arrow_left.svg') }}" width="32">
                    </button>
                    <button class="btn p-0 d-inline-flex">
                        <img src="{{ asset('images/calendar_arrow_right.svg') }}" width="32">
                    </button>
                </div>
            </div>

            <div class="row div_regions">
                @if (!empty($regions))
                    @foreach ($regions as $region)
                        <div class="col-12 col-sm-6 col-md-3 main-slider-card mb-2 div_region">
                            <div class="card-box p-4 h-100">
                                <div class="">
                                    <p class="text-b-24px">
                                        <span class="ht-make-title text-r-18px py-1 px-3 me-1">소속 A</span>
                                        {{ $region['region_name'] }}
                                        <input type="hidden" class="region_seq" value="{{ $region['id'] }}">
                                    </p>
                                </div>
                                <div class="row mx-0 h-100 flex-column">
                                    <ul class="col-auto px-0 py-5">
                                        <li class="d-flex align-items-top justify-content-between py-4 border-gray-bottom">
                                            <p class="gray-color text-m-20px">관할지역 (대분류)</p>
                                            <div class="lh-sm overflow-auto" style="max-height:200px;">
                                                @foreach ($teams->where('region_seq', $region['id'])->pluck('tarea_sido')->unique() as $tarea_sido)
                                                    <p class="black-color text-m-20px">
                                                        {{ $tarea_sido }}
                                                    </p>
                                                @endforeach
                                            </div>

                                        </li>
                                        <li class="d-flex align-items-top justify-content-between py-4 border-gray-bottom">
                                            <p class="gray-color text-m-20px">관할지역 (중분류)</p>
                                            <div class="lh-sm overflow-auto" style="max-height:200px;">
                                                @foreach ($teams->where('region_seq', $region['id'])->pluck('tarea_gu')->unique() as $tarea_gu)
                                                    <p class="black-color text-m-20px">
                                                        {{ $tarea_gu }}
                                                    </p>
                                                @endforeach
                                            </div>
                                        </li>
                                        <li class="d-flex align-items-top justify-content-between py-4 border-gray-bottom">
                                            <p class="gray-color text-m-20px">관할지역 (소분류)</p>
                                            <div class="lh-sm overflow-auto" style="max-height:200px;">
                                                @foreach ($teams->where('region_seq', $region['id'])->pluck('tarea_dong')->unique() as $tarea_dong)
                                                    <p class="black-color text-m-20px">
                                                        {{ $tarea_dong }}
                                                    </p>
                                                @endforeach
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="col button-wrap d-flex flex-column justify-content-end">
                                        <button onclick="mainGLTeamEdit(this)" class="gray-color text-b-24px mb-12 primary-bg-mian-hover scale-text-white-hover">정보
                                            수정하기</button>
                                        <button onclick="" class="gray-color text-b-24px primary-bg-mian-hover scale-text-white-hover scale-bg-gray_01 mb-4" hidden>수업
                                            시작하기</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        {{-- 160px --}}
        <div>
            <div class="py-lg-5"></div>
            <div class="py-lg-4"></div>
            <div class="pt-lg-3"></div>
        </div>

        {{-- 팀 수준인줄 알았는데 수정하기 클릭시 본부임. --}}
        <div hidden data-move-info>
            <tr class="system_team text-m-20px h-104">
                <td class="scale-text-black region_name">부산북구지역본부</td>
                <td class="scale-text-black general_manager_name" hidden="">테스트총괄1<br>(teach1)</td>
                <td class="scale-text-black team_name">북구1팀</td>
                <td class="scale-text-gray_05 leader_name">#팀장</td>
                <td class="text-center align-middle sido">부산광역시,인천광역시</td>
                <td class="scale-text-black gu">강화군,북구</td>
                <td class="scale-text-black dong">강화읍,덕천동,만덕동,화명동,금곡동</td>
                <td class="scale-text-gray_05 team_tr_cnt">0</td>
                <td class="scale-text-gray_05 team_st_cnt">0</td>
                <td class="scale-text-gray_05 created_at">23.11.21</td>
                <td class="">
                    <button type="button" onclick="systemteamTeamAdd('edit', this)" class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">수정하기</button>
                </td>
                <input type="hidden" class="team_seq" value="6">
                <input type="hidden" class="team_code" value="A00006">
                <input type="hidden" class="region_seq" value="10">
            </tr>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            mainGLTodayCount();
        })

        function mainGLTodayCount(){

        }

        function mainGLTeamEdit(vthis){
            const region_seq = vthis.closest('.div_region').querySelector('.region_seq').value;
            const page = "/manage/systemteam?region_seq="+region_seq;

            // 이동 하시겠습니까? text-sb-28px
            const msg =
            `
                <div class="text-sb-28px">소속 및 팀 관리로 이동 하시겠습니까?</div>
            `;
            sAlert('', msg, 3, function(){
                window.location.href = page;
            });
        }
        //소속 팀 목록 불러오기.
        // function systemteamGroupList(team_code){
        //     const page = "/manage/systemteam/teamgroup/select"
        //     const parameter = {
        //         area_list : area_list,
        //         team_code:team_code,
        //         page:page_num
        //     };
        //     queryFetch(page, parameter, function(result) {

        //         const info_bundle = document.querySelector('[data-move-info]');

        //         if ((result.resultCode || '') == 'success') {
        //             teamTablePaging(result.resultData, 1);
        //             for(let i = 0; i < result.resultData.data.length; i++){
        //                 const r_data = result.resultData.data[i];
        //                 const clone_tr = info_bundle.querySelector('.system_team').cloneNode(true);
        //                 clone_tr.querySelector('.region_name').innerText = r_data.region_name;
        //                 clone_tr.querySelector('.general_manager_name').innerHTML = r_data.general_manager_name+'<br>('+r_data.general_manager_id+')';
        //                 clone_tr.querySelector('.team_name').innerText = r_data.team_name;
        //                 clone_tr.querySelector('.sido').innerText = r_data.sido;
        //                 clone_tr.querySelector('.gu').innerText = r_data.gu;
        //                 clone_tr.querySelector('.dong').innerText = r_data.dong;
        //                 clone_tr.querySelector('.team_tr_cnt').innerText = r_data.team_tr_cnt;
        //                 clone_tr.querySelector('.team_st_cnt').innerText = r_data.team_st_cnt;
        //                 clone_tr.querySelector('.created_at').innerText = r_data.created_at.substr(2,8).replace(/-/gi, '.');
        //                 clone_tr.querySelector('.team_seq').value = r_data.team_seq;
        //                 clone_tr.querySelector('.team_code').value = r_data.team_code;
        //                 clone_tr.querySelector('.region_seq').value = r_data.region_seq;
        //             }
        //         }
        //     });
        // }
    </script>

@endsection
