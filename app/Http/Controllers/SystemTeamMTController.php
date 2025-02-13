<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

class SystemTeamMTController extends Controller
{
    //team
    public function team(Request $request)
    {
        $login_type = session()->get('login_type');
        $post_region_seq = $request->input('region_seq');
        if($post_region_seq){
            // session()->put('region_seq', $post_region_seq);
        }
        //전국 시도, 구, 동 가져오기.
        //select sido from address group by sido order by case when sido like '%특별시%' or sido like '부산광역시' then 1 when sido like '%광역시%' then 2 else 3 end
        $address_sido = \App\Addresses::select('sido')->groupBy('sido')->orderByRaw("case when sido like '%특별시%' or sido like '부산광역시' then 1 when sido like '%광역시%' then 2 else 3 end, sido desc")->get();
        $address_gu = \App\Addresses::select('sido', 'gu')->groupBy('sido', 'gu')->orderByRaw("sido")->get();
        $address_dong = \App\Addresses::select('sido', 'gu', 'dong')->groupBy('sido', 'gu', 'dong')->orderByRaw("sido, gu")->get();

        //배열로 만들어서 보내기.
        $address_sido = $address_sido->toArray();
        $address_gu = $address_gu->toArray();
        //$address_dong 은 json으로 보내기.

        // user_groups 가져오기.'group_type2', 'general' 제외, group_type 은 teacher만 가져오기.
        $user_groups = \App\UserGroup::where('group_type2', '!=', 'general')->where('group_type', 'teacher')
            ->select('id', 'group_name', 'group_type')
            // ->orderBy('group_type2', 'desc')
            ->get();

        // 현재 로그인된 유저가 관리자가 아닐경우 소속명, 총괄매니저 고정이므로
        // 총괄매니저 정보 가져오기.
        // 소속정보 가져오기.
        $region_seq = session()->get('region_seq');
        $region = \App\Region::select('regions.*', 'teachers.teach_name', 'teachers.id as teach_seq')
                ->leftJoin('teachers', 'regions.general_teach_seq', '=', 'teachers.id')
                ->where('regions.id', $region_seq)
                ->where('regions.id', '<>', '')
                ->first();

        // $group_type2 = session()->get('group_type2');
        // if($group_type2){
        //     if($group_type2 == 'manage'){
        //         $region_seq = '';
        //     }
        // }

        if($login_type == 'admin'){
            // 원래 관리자용으로 만들었으나,
            // 학생, 학부모, 선생님 모두 화면을 공통으로 사용하게 되어 분기.
            return view('admin.admin_system_team_alayout', [
                'address_sido' => $address_sido,
                'address_gu' => $address_gu,
                'address_dong' => $address_dong,
                'user_groups' => $user_groups,
                'region' => $region,
                'post_region_seq' => $post_region_seq
            ]);
        }else{
        //view로 변수 보내기.
            return view('admin.admin_system_team', [
                'address_sido' => $address_sido,
                'address_gu' => $address_gu,
                'address_dong' => $address_dong,
                'user_groups' => $user_groups,
                'region' => $region,
                'post_region_seq' => $post_region_seq
            ]);
        }
    }

    //소속명 확인
    public function regionNameChk(Request $request)
    {
        //테이블 regions에 post > region_name 값이 있는지 확인.
        $region_name = $request->input('region_name');
        $resion = \App\Region::where('region_name', $region_name)->get();
        $resion = $resion->toArray();
        $result = array();
        if (count($resion) > 0) {
            $result = array('resultCode' => 'fail');
        } else {
            $result = array('resultCode' => 'success');
        }
        return $result;
    }

    //팀명 확인
    public function teamNameChk(Request $request)
    {
        //테이블 teams에 post > team_name 값이 있는지 확인.
        $team_name = $request->input('team_name');
        $team = \App\Team::where('team_name', $team_name)->get();
        $team = $team->toArray();
        $result = array();
        if (count($team) > 0) {
            $result = array('resultCode' => 'fail');
            $team_code = $team[0]['team_code'];
            $region_seq = $team[0]['region_seq'];

            $region = \App\Region::select('regions.*', 'teachers.teach_name')
                ->leftJoin('teachers', 'regions.general_teach_seq', '=', 'teachers.id')
                ->where('regions.id', $region_seq)
                ->first();

            $team_area = \App\TeamArea::select(
                DB::raw('group_concat(DISTINCT tarea_sido separator "/") as sido'),
                DB::raw('group_concat(DISTINCT tarea_gu separator "/") as gu')
            )
                ->where('team_code', $team_code)
                ->first();

            $result['region'] = $region;
            $result['team_area'] = $team_area;

        } else {
            $result = array('resultCode' => 'success');
        }

        return $result;
    }

    //소속(resion) SELECT
    public function regionSelect(Request $request)
    {
        $area = $request->input('area');
        $region_name = $request->input('region_name');
        $teach_seq = $request->input('general_teach_seq');
        $team_code = $request->input('team_code');

        $sql = \App\Region::select('*');

        // area 값이 있으면, 해당 지역의 총괄 매니저를 SELECT
        if ($area != '') {
            // $sql = $sql->where('area', $area);
        }

        //region_name 값이 있으면, 해당 지역의 총괄 매니저를 SELECT
        if ($region_name != '') {
            $sql = $sql->where('region_name', $region_name);
        }
        if($teach_seq){
            // (general_teach_seq = $teach_seq or id = select region_seq form teams where team_code = $team_code)
            $sql = $sql->where(function($query) use ($teach_seq, $team_code){
                $query->where('general_teach_seq', $teach_seq)
                ->orWhere('id', function($query) use ($team_code){
                    $query->select('region_seq')
                    ->from('teams')
                    ->where('team_code', $team_code);
                });
            });
        }
        $region = $sql->get();
        $result['resultData'] = $region;
        $result['resultCode'] = 'success';
        return $result;
    }

    //선생님(총괄 / 팀원 / 삼당 ) SELECT
    public function teacherSelect(Request $request)
    {
        $main_code = $_COOKIE['main_code'];
        $area = $request->input('area');
        $region_seq = $request->input('region_seq');
        $group_seq = $request->input('group_seq');
        $group_key = $request->input('group_key');
        $team_code = $request->input('team_code');
        $is_not_gm = $request->input('is_not_gm');
        $is_unassigned = $request->input('is_unassigned');
        $region_id = $request->input('region_id');

        //검색
        $search_type = $request->input('search_type');
        $search_str = $request->input('search_str');


        $group_type2 = '';
        //코드 약속.
        switch ($group_key) {
            case 'general_manager':
                 $group_type2 = 'general';
                break;
            case 'teacher':
                $is_not_gm = 'Y';
                break;
            // 통합으로 아래는 주석처리.
            // case 'team_leader':
            //     $group_seq = 6;
            //     break;
            // case 'teacher':
            //     $group_seq = 7;
            //     break;
            // case 'counselor':
            //     $group_seq = 8;
            //     break;
        }

        $sql = \App\Teacher::select('teachers.*', 'user_groups.group_name', 'user_groups.group_type2', 'teams.team_name', 'regions.region_name', 'gregions.id as g_region_seq');
        $sql = $sql->leftJoin('user_groups', 'teachers.group_seq', '=', 'user_groups.id');
        $sql = $sql->leftJoin('teams', 'teachers.team_code', '=', 'teams.team_code');
        $sql = $sql->leftJoin('regions', 'teams.region_seq', '=', 'regions.id');
        $sql = $sql->leftJoin('regions as gregions', function($join) use ($region_id){
// 'gregions.general_teach_seq', '=', 'teachers.id'
            $join->on('gregions.general_teach_seq', '=', 'teachers.id')->where('gregions.id', $region_id);
        });
        $sql = $sql->where('teachers.main_code', $main_code);

        // 조건 검색1
        if ($area != '') { $sql = $sql->where('area', $area); }
        if ($region_seq != '') { $sql = $sql->where('region_seq', $region_seq); }
        if ($group_seq != '') { $sql = $sql->where('group_seq', $group_seq); }
        if($team_code != ''){ $sql = $sql->where('teachers.team_code', $team_code); }
        if($is_not_gm == 'Y'){ $sql = $sql->where('group_type2', '!=', 'general'); }
        if($group_type2 != ''){ $sql = $sql->where('group_type2', $group_type2); }
        if($is_unassigned == 'Y'){ $sql = $sql->where('teachers.team_code', ''); }

        // 조건 검색2
        if(strlen($search_str) > 0){
            if($search_type == 'teach_name') $sql = $sql->where('teach_name', 'like', '%'.$search_str.'%');
        }

        $teacher = $sql->get();
        $result['resultData'] = $teacher;
        $result['resultCode'] = 'success';
        return $result;
    }

    //소속 / 팀 저장
    public function teamGroupInsert(Request $request)
    {
        // area, area_list, region_seq, region_name, team_name, general_manager, team_list
        $area = $request->input('area');
        $area_list = $request->input('area_list');
        $region_seq = $request->input('region_seq');
        $region_name = $request->input('region_name');
        $team_name = $request->input('team_name');
        $general_manager = $request->input('general_manager');
        $team_list = $request->input('team_list');
        $team_code = $request->input('team_code');

        //소속이 없으면 INSERT / region_seq 가져옴.
        if ($region_seq == '') {
            $region_seq = $this->regioninsert($request)['region_seq'];
            //$region_seq를 request에 삽입.
            $request->merge(array('region_seq' => $region_seq));
        }


        //팀이 없으면 INSERT
        if ($team_code == '') {
            $team_result = $this->teaminsert($request);
            $team_code = $team_result['team_code'];
            $request->merge(array('team_code' => $team_code));
        }
        //팀이 팀명 있으면 UPDATE
        else{
            $team = \App\Team::where('team_code', $team_code)->first();
            $team->team_name = $team_name;
            $team->save();
        }

        //팀에 지역(area_list) INSERT
        $this->teamareainsert($request);

        //촐괄매니저 INSERT
        if($region_seq && $general_manager){
            //(이전 총괄매니저 소속을 없애는 작업) // team_code, region_seq
            \App\Teacher::leftJoin('user_groups', 'teachers.group_seq', '=', 'user_groups.id')
                ->where('user_groups.group_type2', 'general')
                ->where('region_seq', $region_seq)
                ->update(['team_code' => '', 'region_seq' => 0]);
            //(현재 총괄매니저 소속을 변경하는 작업)
            $gm = \App\Teacher::where('id', $general_manager)->first();
            // ->update(['region_seq' => $region_seq]);
            $gm->region_seq = $region_seq;
            $gm->save();

            // 리전에도 넣어줘야한다. 중복으로 될수 있게 요청.
            \App\Region::where('id', $region_seq)->update(['general_teach_seq' => $general_manager, 'general_group_seq' => $gm->group_seq]);
        }

        //팀원 INSERT
        //총괄제외
            \App\Teacher::leftJoin('user_groups', 'teachers.group_seq', '=', 'user_groups.id')
            ->where('team_code', $team_code)
            ->where('user_groups.group_type2', '!=', 'general')
            ->update(['team_code' => '']);
        $team_list = explode('|', $team_list);
        foreach ($team_list as $key => $value) {
            \App\Teacher::where('id', $value)->update(['team_code' => $team_code, 'region_seq' => $region_seq]);
        }

        $result = array('resultCode' => 'success');
        return $result;
    }

    //소속 INSRT
    public function regioninsert(Request $request)
    {
        $area = $request->input('area');
        $region_name = $request->input('region_name');
        $region_seq = $request->input('region_seq');

        //$region_seq 가 비어있으면, region_name 으로 region 테이블에 새로운 저장.
        if ($region_seq == '') {
            $region = new \App\Region;
            $region->area = $area;
            $region->region_name = $region_name;
            $region->save();
            $region_seq = $region->id;
        }
        //region_seq 를 리턴.
        $result = array('region_seq' => $region_seq);
        return $result;
    }

    //팀 INSERT
    public function teaminsert(Request $request)
    {
        $region_seq = $request->input('region_seq');
        $team_code = $request->input('team_code');
        $team_name = $request->input('team_name');

        //$team_seq 가 비어있으면, team_name 으로 team 테이블에 새로운 저장.
        if ($team_code == '') {
            $team = new \App\Team;
            //team_code 는 가장 큰값을 가져와서 A00001 부터 Z99999 까지 순차적으로 증가. 없으면 A00001 부터 시작.
            $team_code = \App\Team::select(DB::raw('max(team_code) as team_code'))->first();
            $team_code = $team_code->team_code;
            if ($team_code == '') {
                $team_code = 'A00000';
            }
            $team_code = substr($team_code, 0, 1) . substr('00000' . (substr($team_code, 1, 5) + 1), -5);

            $team->team_code = $team_code;
            $team->region_seq = $region_seq;
            $team->team_name = $team_name;
            $team->save();
            $team_seq = $team->id;
        }
        //team_code , team_seq 를 모두 리턴.
        $result = array('team_code' => $team_code, 'team_seq' => $team_seq);
        return $result;
    }

    //팀에 지역(area_list) INSERT
    public function teamareainsert(Request $request)
    {
        $area_list = $request->input('area_list');
        $team_code = $request->input('team_code');

        //area_list : 부산광역시,북구,구포동|부산광역시,북구,금곡동|부산광역시,북구,덕천동
        //area_list 는 team_area에 분리해서 저장.
        //먼저 팀코드가 $team_code 인 데이터를 삭제 후
        //area_list 를 | 로 분리해서 저장.
        \App\TeamArea::where('team_code', $team_code)->delete();
        $area_list = explode('|', $area_list);
        foreach ($area_list as $key => $value) {
            $area_list[$key] = explode(',', $value);
            $team_area = new \App\TeamArea;
            $team_area->team_code = $team_code;
            $team_area->tarea_sido = $area_list[$key][0];
            $team_area->tarea_gu = $area_list[$key][1];
            $team_area->tarea_dong = $area_list[$key][2];
            $team_area->save();
        }
    }

    //소속/팀 SELECT
    public function teamGroupSelect(Request $request)
    {
        $main_code = $_COOKIE['main_code'];
        $area_list = $request->input('area_list');
        $search_type = $request->input('search_type');
        $search_name = $request->input('search_name');

        $page = $request->input('page');
        $page = $page ?? 1;
        $page_max = $request->input('page_max');
        $page_max = $page_max ?? 10;
        $team_code = $request->input('team_code');
        $region_seq = $request->input('region_seq');


        // $sql = $sql->paginate($page_max, ['*'], 'page', $page);

        //user_gropus 에서 group_type2 가 general 면서 main_code = $main-code 인 group_seq 가져오기.
        $groups = \App\UserGroup::where('group_type2', 'general')->where('main_code', $main_code)->first();
        $group_seq = $groups->id;

        $sql =
            \App\Team::select(
                'teams.id as team_seq',
                'teams.team_code',
                'rs.region_name',
                'rs.id as region_seq',
                'teams.team_name',
                'teams.team_tr_cnt',
                'teams.team_st_cnt',
                'teams.team_pt_cnt',
                'teams.created_at',
                DB::raw('min(ts.teach_name) as general_manager_name'),
                DB::raw('min(ts.teach_id) as general_manager_id'),
                DB::raw('group_concat(DISTINCT ta.tarea_sido) sido'),
                DB::raw('group_concat(DISTINCT ta.tarea_gu) gu'),
                DB::raw('group_concat(ta.tarea_dong) dong')
            );

        $sql = $sql->leftJoin('team_areas as ta', 'teams.team_code', '=', 'ta.team_code');
        $sql = $sql->leftJoin('regions as rs', 'teams.region_seq', '=', 'rs.id');
        $sql = $sql->leftJoin('teachers as ts', function ($join) use ($group_seq) {
            // $join->on('teams.team_code', '=', 'ts.team_code');
            $join->on('ts.group_seq', '=',DB::raw($group_seq));
        });
        $sql = $sql->groupBy(
            'teams.id',
            'teams.team_code',
            'rs.region_name',
            'teams.team_name'
        );

        //area_list : 부산광역시,북구,구포동|부산광역시,북구,금곡동|부산광역시,북구,덕천동
        $area_list = explode('|', $area_list);
        if(!$region_seq){

            foreach ($area_list as $key => $value) {
                $area_list[$key] = explode(',', $value);
                $tarea_sido = $area_list[$key][0];
                $tarea_gu = $area_list[$key][1];
                $tarea_dong = $area_list[$key][2];
                if ($tarea_dong == '전체') {
                    $tarea_dong = '';
                }

                // where `teams`.`team_code` in (select `team_code` from `team_areas` where `tarea_sido` = '?' and `tarea_gu` = '?' and `tarea_dong` = '?)
                // or ...
                if($key == 0){
                    $sql = $sql->whereIn('teams.team_code', function ($query) use ($tarea_sido, $tarea_gu, $tarea_dong) {
                        $query->select('team_code')
                        ->from('team_areas')
                        ->where('tarea_sido', $tarea_sido)
                        ->where('tarea_gu', 'like', '%' . $tarea_gu . '%')
                        ->where('tarea_dong', 'like', '%' . $tarea_dong . '%' );
                    });
                }else{
                    $sql = $sql->orWhereIn('teams.team_code', function ($query) use ($tarea_sido, $tarea_gu, $tarea_dong) {
                        $query->select('team_code')
                        ->from('team_areas')
                        ->where('tarea_sido', $tarea_sido)
                        ->where('tarea_gu', 'like', '%' . $tarea_gu . '%')
                        ->where('tarea_dong', 'like', '%' . $tarea_dong . '%' );
                    });
                }
                // () or () 형태로
                // 추가 코드 변형 필요할 수도. 속도면에서.
            }
        }

        //검색조건이 있으면 추가.
        if ($search_type != '' && $search_name != '') {
            //총괄 매니저 이름 검색
            if ($search_type == 'general_manager_name')  {
                $sql = $sql->where('ts.teach_name', 'like', '%' . $search_name . '%');
            }
            //소속명
            else if ($search_type == 'region_name') {
                $sql = $sql->where('rs.region_name', 'like', '%' . $search_name . '%');
            }
            //팀명
            else if ($search_type == 'team_name') {
                $sql = $sql->where('teams.team_name', 'like', '%' . $search_name . '%');
            }
        }

        //검색 조건 $team_code
        if($team_code){
            $sql = $sql->where('teams.team_code', $team_code);
        }

        if($region_seq){
            $sql = $sql->where('rs.id', $region_seq);
        }

        DB::enableQueryLog();
        // $team = $sql->get();
        $team = $sql->paginate($page_max, ['*'], 'page', $page);
        $lastQuery = last(DB::getQueryLog());
        $result['resultData'] = $team;
        $result['resultCode'] = 'success';
        // $result['sql'] = $lastQuery;
        return $result;
    }

    //소속 / 팀 지역 정보 SELECT
    public function teamAreaSelect(Request $request)
    {
        $team_code = $request->input('team_code');
        $team_area = \App\TeamArea::where('team_code', $team_code)->get();
        $result['resultData'] = $team_area;
        $result['resultCode'] = 'success';
        return $result;
    }

    //소속 / 팀 삭제
    public function teamDelete(Request $request){
        $team_code = $request->input('team_code');
        //팀에 소속된 선생님들의 team_code 를 '' 로 변경.
        \App\Teacher::where('team_code', $team_code)->update(['team_code' => '']);
        //팀에 소속된 지역들을 삭제.
        \App\TeamArea::where('team_code', $team_code)->delete();
        //팀을 삭제.
        \App\Team::where('team_code', $team_code)->delete();
        $result['resultCode'] = 'success';
        return $result;
    }

    //팀 통합
    public function teamMergeInsert(Request $request){
        $team_name = $request->input('team_name');
        $team_code = $request->input('team_code');
        //구분자 배열로 변경.
        $team_code = explode(',', $team_code);

        //팀코드($team_code[0])로 region_seq를 불러오기.
        $region_seq = \App\Team::select('region_seq')->whereIn('team_code', $team_code)->first();
        $region_seq = $region_seq->region_seq;

        //팀 team_name으로 팀명 INSERT
        $post1 = array();
        $post1['region_seq'] = $region_seq;
        $post1['team_name'] = $team_name;
        $post1['team_code'] = '';

        $post1 = new \Illuminate\Http\Request($post1);
        $team_result = $this->teaminsert($post1);
        $team_new_code = $team_result['team_code'];

        //팀 통합 작업
        foreach($team_code as $key => $value){
            //팀 지역 통함.
            \App\TeamArea::where('team_code', $value)->update(['team_code' => $team_new_code]);

            //직원 통합.
            \App\Teacher::where('team_code', $value)->update(['team_code' => $team_new_code]);

            //학생 통합
            \App\Student::where('team_code', $value)->update(['team_code' => $team_new_code]);

            //학부모 통합
            \App\ParentTb::where('team_code', $value)->update(['team_code' => $team_new_code]);

            //추후 통합 코드 추가.
            //---

            //팀 삭제.
            \App\Team::where('team_code', $value)->delete();
        }

        //팀 team_areas의 $team_new_code로 변경된 내용중 중복된 내용은 1개만 남기고 삭제.
        DB::transaction(function () use ($team_new_code) {
            // 고유한 조합을 식별하기 위해 집계 함수 (예: MIN 또는 MAX)를 사용합니다.
            $uniqueIds = \App\TeamArea::where('team_code', $team_new_code)
                                        ->selectRaw('MIN(tarea_seq) as tarea_seq')
                                        ->groupBy('team_code', 'tarea_sido', 'tarea_gu', 'tarea_dong')
                                        ->pluck('tarea_seq');

            // 고유한 ID를 제외하고 나머지 레코드를 삭제합니다.
            \App\TeamArea::where('team_code', $team_new_code)
                          ->whereNotIn('tarea_seq', $uniqueIds)
                          ->delete();
        });

        $result['resultCode'] = 'success';
    }

    public function schoolRegion(Request $request){
        $post_region_seq = $request->input('region_seq');
        //전국 시도, 구, 동 가져오기.
        $address_sido = \App\Addresses::select('sido')->groupBy('sido')->orderByRaw("case when sido like '%특별시%' or sido like '부산광역시' then 1 when sido like '%광역시%' then 2 else 3 end, sido desc")->get();
        $address_gu = \App\Addresses::select('sido', 'gu')->groupBy('sido', 'gu')->orderByRaw("sido")->get();
        $address_dong = \App\Addresses::select('sido', 'gu', 'dong')->groupBy('sido', 'gu', 'dong')->orderByRaw("sido, gu")->get();
        //배열로 만들어서 보내기.
        $address_sido = $address_sido->toArray();
        $address_gu = $address_gu->toArray();
        //$address_dong 은 json으로 보내기.
        //user_groups 가져오기.'group_type2', 'general' 제외, group_type 은 teacher만 가져오기.
        $user_groups = \App\UserGroup::where('group_type2', '!=', 'general')
            ->where('group_type', 'teacher')
            ->select('id', 'group_name', 'group_type')
            ->get();

        // 현재 로그인된 유저가 관리자가 아닐경우 소속명, 총괄매니저 고정이므로
        // 총괄매니저 정보 가져오기.
        // 소속정보 가져오기.
        $region_seq = session()->get('region_seq');
        $region = \App\Region::select('regions.*', 'teachers.teach_name', 'teachers.id as teach_seq')
                ->leftJoin('teachers', 'regions.general_teach_seq', '=', 'teachers.id')
                ->where('regions.id', $region_seq)
                ->where('regions.id', '<>', '')
                ->first();

        return view('admin.admin_system_school', [
            'address_sido' => $address_sido,
            'address_gu' => $address_gu,
            'address_dong' => $address_dong,
            'user_groups' => $user_groups,
            'region' => $region,
            'post_region_seq' => $post_region_seq
        ]);
    }

    public function schoolSearch(Request $request){
        $search_name = $request->input('search_name');

        if (empty($search_name)) {
            return response()->json([]);
        }

        $schools = \App\SchoolInfo::where('SCHUL_KND_SC_NM', '초등학교')
            ->where('SCHUL_NM', 'like', '%' . $search_name . '%')
            ->orWhere('SCHUL_KND_SC_NM', 'like', '%' . $search_name . '%')
            ->get();

        if ($schools->isNotEmpty()) {
            return response()->json(['resultCode' => 'success', 'data' => $schools]);
        } else {
            return response()->json(['resultCode' => 'fail', 'data' => []]);
        }
    }

    public function schoolTeam(Request $request)
    {
        $post_region_seq = $request->input('region_seq');
        if($post_region_seq){
            // session()->put('region_seq', $post_region_seq);
        }
        //전국 시도, 구, 동 가져오기.
        //select sido from address group by sido order by case when sido like '%특별시%' or sido like '부산광역시' then 1 when sido like '%광역시%' then 2 else 3 end
        $address_sido = \App\Addresses::select('sido')->groupBy('sido')->orderByRaw("case when sido like '%특별시%' or sido like '부산광역시' then 1 when sido like '%광역시%' then 2 else 3 end, sido desc")->get();
        $address_gu = \App\Addresses::select('sido', 'gu')->groupBy('sido', 'gu')->orderByRaw("sido")->get();
        $address_dong = \App\Addresses::select('sido', 'gu', 'dong')->groupBy('sido', 'gu', 'dong')->orderByRaw("sido, gu")->get();

        //배열로 만들어서 보내기.
        $address_sido = $address_sido->toArray();
        $address_gu = $address_gu->toArray();
        //$address_dong 은 json으로 보내기.

        // user_groups 가져오기.'group_type2', 'general' 제외, group_type 은 teacher만 가져오기.
        $user_groups = \App\UserGroup::where('group_type2', '!=', 'general')->where('group_type', 'teacher')
            ->select('id', 'group_name', 'group_type')
            // ->orderBy('group_type2', 'desc')
            ->get();

        // 현재 로그인된 유저가 관리자가 아닐경우 소속명, 총괄매니저 고정이므로
        // 총괄매니저 정보 가져오기.
        // 소속정보 가져오기.
        $region_seq = session()->get('region_seq');
        $region = \App\Region::select('regions.*', 'teachers.teach_name', 'teachers.id as teach_seq')
                ->leftJoin('teachers', 'regions.general_teach_seq', '=', 'teachers.id')
                ->where('regions.id', $region_seq)
                ->where('regions.id', '<>', '')
                ->first();

        // $group_type2 = session()->get('group_type2');
        // if($group_type2){
        //     if($group_type2 == 'manage'){
        //         $region_seq = '';
        //     }
        // }

        //view로 변수 보내기.
        return view('admin.admin_system_team', [
            'address_sido' => $address_sido,
            'address_gu' => $address_gu,
            'address_dong' => $address_dong,
            'user_groups' => $user_groups,
            'region' => $region,
            'post_region_seq' => $post_region_seq
        ]);
    }
}


