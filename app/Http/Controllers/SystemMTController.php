<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

class SystemMTController extends Controller
{
    //team
    public function team()
    {
        //전국 시도, 구, 동 가져오기.
        //select sido from address group by sido order by case when sido like '%특별시%' or sido like '부산광역시' then 1 when sido like '%광역시%' then 2 else 3 end
        $address_sido = \App\Addresses::select('sido')->groupBy('sido')->orderByRaw("case when sido like '%특별시%' or sido like '부산광역시' then 1 when sido like '%광역시%' then 2 else 3 end, sido desc")->get();
        $address_gu = \App\Addresses::select('sido', 'gu')->groupBy('sido', 'gu')->orderByRaw("sido")->get();
        $address_dong = \App\Addresses::select('sido', 'gu', 'dong')->groupBy('sido', 'gu', 'dong')->orderByRaw("sido, gu")->get();

        //배열로 만들어서 보내기.
        $address_sido = $address_sido->toArray();
        $address_gu = $address_gu->toArray();
        //$address_dong 은 json으로 보내기.

        //view로 변수 보내기.
        return view('admin.admin_system_team', ['address_sido' => $address_sido, 'address_gu' => $address_gu, 'address_dong' => $address_dong]);
    }

    //소속명 확인
    public function regionnamechk(Request $request)
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
    public function teamnamechk(Request $request)
    {
        //테이블 teams에 post > team_name 값이 있는지 확인.
        $team_name = $request->input('team_name');
        $team = \App\Team::where('team_name', $team_name)->get();
        $team = $team->toArray();
        $result = array();
        if (count($team) > 0) {
            $result = array('resultCode' => 'fail');
        } else {
            $result = array('resultCode' => 'success');
        }
        return $result;
    }

    //소속(resion) SELECT
    public function regionselect(Request $request)
    {
        $area = $request->input('area');
        $region_name = $request->input('region_name');

        $sql = \App\Region::select('*');
        //area 값이 있으면, 해당 지역의 총괄 매니저를 SELECT
        if ($area != '') {
            $sql = $sql->where('area', $area);
        }
        //region_name 값이 있으면, 해당 지역의 총괄 매니저를 SELECT
        if ($region_name != '') {
            $sql = $sql->where('region_name', $region_name);
        }
        $region = $sql->get();
        $result['resultData'] = $region;
        $result['resultCode'] = 'success';
        return $result;
    }

    //선생님(총괄 / 팀원 / 삼당 ) SELECT
    public function teacherselect(Request $request)
    {
        $area = $request->input('area');
        $region_seq = $request->input('region_seq');
        $group_seq = $request->input('group_seq');
        $group_key = $request->input('group_key');
        $team_code = $request->input('team_code');
        $is_not_gm = $request->input('is_not_gm');

        //코드 약속.
        switch ($group_key) {
            case 'general_manager':
                $group_seq = 5;
                break;
            case 'team_leader':
                $group_seq = 6;
                break;
            case 'teacher':
                $group_seq = 7;
                break;
            case 'counselor':
                $group_seq = 8;
                break;
        }

        $sql = \App\Teacher::select('teachers.*', 'user_groups.group_name');
        $sql = $sql->leftJoin('user_groups', 'teachers.group_seq', '=', 'user_groups.seq');

        if ($area != '') {
            $sql = $sql->where('area', $area);
        }
        if ($region_seq != '') {
            $sql = $sql->where('region_seq', $region_seq);
        }
        if ($group_seq != '') {
            $sql = $sql->where('group_seq', $group_seq);
        }
        if($team_code != ''){
            $sql = $sql->where('team_code', $team_code);
        }
        if($is_not_gm == 'Y'){
            $sql = $sql->where('group_seq', '!=', 5);
        }
        $teacher = $sql->get();
        $result['resultData'] = $teacher;
        $result['resultCode'] = 'success';
        return $result;
    }

    //소속 / 팀 저장
    public function teamgroupinsert(Request $request)
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
        //(이전 총괄매니저 소속을 없애는 작업) // team_code, region_seq
        \App\Teacher::where('group_seq', 5)->where('team_code', $team_code)->update(['team_code' => '', 'region_seq' => 0]);
        //(현재 총괄매니저 소속을 변경하는 작업)
        \App\Teacher::where('teach_seq', $general_manager)->update(['region_seq' => $region_seq]);

        //팀원 INSERT
        //총괄제외
        \App\Teacher::where('team_code', $team_code)->where('group_seq', '!=', 5)->update(['team_code' => '']);
        $team_list = explode('|', $team_list);
        foreach ($team_list as $key => $value) {
            \App\Teacher::where('teach_seq', $value)->update(['team_code' => $team_code]);
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
            $region_seq = $region->region_seq;
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
            $team_seq = $team->team_seq;
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
        \App\Team_area::where('team_code', $team_code)->delete();
        $area_list = explode('|', $area_list);
        foreach ($area_list as $key => $value) {
            $area_list[$key] = explode(',', $value);
            $team_area = new \App\Team_area;
            $team_area->team_code = $team_code;
            $team_area->tarea_sido = $area_list[$key][0];
            $team_area->tarea_gu = $area_list[$key][1];
            $team_area->tarea_dong = $area_list[$key][2];
            $team_area->save();
        }
    }

    //소속/팀 SELECT
    public function teamgrouplist(Request $request)
    {
        $area_list = $request->input('area_list');
        $search_type = $request->input('search_type');
        $search_name = $request->input('search_name');



        $sql =
            \App\Team::select(
                'teams.team_seq',
                'teams.team_code',
                'rs.region_name',
                'rs.region_seq',
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
        $sql = $sql->leftJoin('regions as rs', 'teams.region_seq', '=', 'rs.region_seq');
        $sql = $sql->leftJoin('teachers as ts', function ($join) {
            // $join->on('teams.team_code', '=', 'ts.team_code');
            $join->on('ts.group_seq', '=', DB::raw('5'));
        });
        $sql = $sql->groupBy(
            'teams.team_seq',
            'teams.team_code',
            'rs.region_name',
            'teams.team_name'
        );

        //area_list : 부산광역시,북구,구포동|부산광역시,북구,금곡동|부산광역시,북구,덕천동
        $area_list = explode('|', $area_list);
        foreach ($area_list as $key => $value) {
            $area_list[$key] = explode(',', $value);
            $tarea_sido = $area_list[$key][0];
            $tarea_gu = $area_list[$key][1];
            $tarea_dong = $area_list[$key][2];
            if ($tarea_dong == '전체') {
                $tarea_dong = '';
            }
            $sql = $sql->orWhere(function ($query) use ($tarea_sido, $tarea_gu, $tarea_dong) {
                $query->where('tarea_sido', $tarea_sido)->where('tarea_gu', $tarea_gu)->where('tarea_dong', 'like', '%' . $tarea_dong . '%');
            });
        }

        //검색조건이 있으면 추가.
        if ($search_type != '' && $search_name != '') {
            //총괄 매니저 이름 검색
            if ($search_type == 'general_manager_name') {
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
        DB::enableQueryLog();
        $team = $sql->get();
        $lastQuery = last(DB::getQueryLog());
        $result['resultData'] = $team;
        $result['resultCode'] = 'success';
        $result['sql'] = $lastQuery;
        return $result;
    }

    //소속 / 팀 지역 정보 SELECT
    public function teamareaselect(Request $request)
    {
        $team_code = $request->input('team_code');
        $team_area = \App\Team_area::where('team_code', $team_code)->get();
        $result['resultData'] = $team_area;
        $result['resultCode'] = 'success';
        return $result;
    }

    //소속 / 팀 삭제
    public function teamdelete(Request $request){
        $team_code = $request->input('team_code');
        //팀에 소속된 선생님들의 team_code 를 '' 로 변경.
        \App\Teacher::where('team_code', $team_code)->update(['team_code' => '']);
        //팀에 소속된 지역들을 삭제.
        \App\Team_area::where('team_code', $team_code)->delete();
        //팀을 삭제.
        \App\Team::where('team_code', $team_code)->delete();
        $result['resultCode'] = 'success';
        return $result;
    }

    //팀 통합
    public function teammergeinsert(Request $request){
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
            \App\Team_area::where('team_code', $value)->update(['team_code' => $team_new_code]);

            //직원 통합.
            \App\Teacher::where('team_code', $value)->update(['team_code' => $team_new_code]);

            //학생 통합
            \App\Student::where('team_code', $value)->update(['team_code' => $team_new_code]); 

            //학부모 통합
            \App\Parents_tb::where('team_code', $value)->update(['team_code' => $team_new_code]);

            //추후 통합 코드 추가.

            //팀 삭제.
            \App\Team::where('team_code', $value)->delete();
        }

        //팀 team_areas의 $team_new_code로 변경된 내용중 중복된 내용은 1개만 남기고 삭제.
        DB::transaction(function () use ($team_new_code) {
            $team_areas = \App\Team_area::where('team_code', $team_new_code)
                                    ->get()
                                    //tarea_sido, tarea_gu, tarea_dong 으로 그룹핑.
                                    ->groupBy(['tarea_sido', 'tarea_gu', 'tarea_dong']);
        
            foreach ($team_areas as $group) {
                // 각 그룹에서 첫 번째 레코드를 제외하고 모두 삭제
                foreach ($group->slice(1) as $duplicate) {
                    $duplicate->delete();
                }
            }
        });

        $result['resultCode'] = 'success';
        return $result;
    }
    
}
