<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\LogMTController as LogMT;

class UserMTController extends Controller
{
    //List
    public function list(){
        $main_code = $_COOKIE['main_code'] ?? 'elementary';
        // $main_code = $_COOKIE['main_code'];
        //사용자 그룹 목록 가져오기.
        $result = $this->groupListCntSelect();
        $result['resultData'] = $result['resultData']->toArray();
        $address_sido = \App\Addresses::select('sido')->groupBy('sido')->orderByRaw("case when sido like '%특별시%' or sido like '부산광역시' then 1 when sido like '%광역시%' then 2 else 3 end, sido desc")->get();
        //배열로 만들어서 보내기.
        $address_sido = $address_sido->toArray();
        $region = \App\Region::orderBy('region_name')->get();
        $region = $region->toArray();

        $team = \App\Team::orderBy('team_code')->get();
        $team = $team->toArray();

        $goods = \App\Goods::select('id', 'goods_name', 'goods_period')->get();
        $godds = $goods->toArray();

        $grade_codes = \App\Code::where('code_category', 'grade')->where('code_step', '=', 1)->where('main_code', '=', $main_code)->get();
        $grade_codes = $grade_codes->toArray();

        return view('admin.admin_user_list', ['user_group' => $result['resultData'],
                                              'address_sido' => $address_sido,
                                              'allCnt' => $result['allCnt'],
                                              'region' => $region,
                                              'team'=>$team,
                                              'notGroupCnt'=>$result['notGroupCnt'],
                                              'goods'=>$goods,
                                              'grade_codes'=>$grade_codes]);
    }

    //선생님 정보 관리
    public function teacherList(){
        $teach_seq = session()->get('teach_seq');
        $team_code = session()->get('team_code');
        $group_type2 = session()->get('group_type2');

        //관리자나 총괄일경우
        //본부(리전) SELECT
        if($group_type2 == 'manage'){
            $regions = \App\Region::all();
        }
        else if($group_type2 == 'general'){
            $regions = \App\Region::where('general_teach_seq', $teach_seq)->get();
        }else{
            //그외 선생님
            $regions = \App\Region::whereIn('id', function ($query) use ($team_code) {
                $query->select('region_seq')
                    ->from('teams')
                    ->where('team_code', $team_code);
            })->get();
        }

        $team = \App\Team::where('team_code', $team_code)->first();

        return view('teacher.teacher_manage', [
            'group_type2' => $group_type2,
            'regions' => $regions,
            'team' => $team
        ]);
    }

    // 유저 일괄등록 엑셀
    public function addExcel(Request $request){
        $main_code = $_COOKIE['main_code'] ?? session()->get('main_code');
        $group_type2 = session()->get('group_type2');
        $user_type = $request->input('user_type');
        $region_seq = $request->input('region_seq');
        $team_code = $request->input('team_code');
        $region_name = $region_seq ? \App\Region::where('id', $region_seq)->first()->region_name:'';
        $team_name = $team_code ? \App\Team::where('team_code', $team_code)->first()->team_name:'';
        $team_type = $team_code ? \App\Team::where('team_code', $team_code)->first()->team_type:'';

        // select region_name,team_name, region_seq, team_code from teams left join regions on teams.region_seq = regions.id
        // select * from teams
        $temas = \App\Team::select(
            'regions.region_name',
            'teams.team_name',
            'teams.region_seq',
            'teams.team_code'
            )
            ->leftJoin('regions', 'teams.region_seq', '=', 'regions.id')
            ->where('teams.main_code', $main_code)
            ->get();
        //teams 를 teams.team_name을 키로 나머지를 배열로 만듬.
        $temas = $temas->keyBy('team_name')->toArray();
        $groups = \App\UserGroup::select('group_name', 'id as group_seq', 'group_type')
        ->where('main_code', $main_code)
        ->where('group_type','<>', 'admin');


        if($group_type2 == 'general'){
            $groups = $groups->where('group_type2','<>', 'general');
        }
        else if($group_type2 == 'leader'){
            $groups = $groups->where('group_type2', '<>','general');
            $groups = $groups->where('group_type2', '<>', 'leader');
        }
        else if($group_type2 == 'run'){
            $groups = $groups->where('group_type', '<>', 'teacher');
        }
        if($user_type){
            $groups = $groups->where('group_type', $user_type);
        }
        $groups = $groups->get();
        $groups = $groups->keyBy('group_name')->toArray();

        return view('teacher.teacher_user_add_excel', [
            'user_type' => $user_type,
            'region_seq' => $region_seq,
            'team_code' => $team_code,
            'region_name' => $region_name,
            'team_name' => $team_name,
            'temas' => $temas,
            'groups' => $groups,
            'team_type' => $team_type
        ]);
    }

    //add
    public function add(){
        $main_code = $_COOKIE['main_code'];
        //사용자 그룹 목록 가져오기.
        $result = $this->groupListCntSelect();

        $result['resultData'] = $result['resultData']->toArray();

        $address_sido = \App\Addresses::select('sido')->groupBy('sido')->orderByRaw("case when sido like '%특별시%' or sido like '부산광역시' then 1 when sido like '%광역시%' then 2 else 3 end, sido desc")->get();

        //배열로 만들어서 보내기.
        $address_sido = $address_sido->toArray();

        $region = \App\Region::orderBy('region_name')->get();
        $region = $region->toArray();

        $team = \App\Team::orderBy('team_code')->get();
        $team = $team->toArray();

        $goods = \App\Goods::select('id', 'goods_name', 'goods_period')->get();
        $godds = $goods->toArray();

        $grade_codes = \App\Code::where('code_category', 'grade')->where('code_step', '=', 1)->where('main_code', '=', $main_code)->get();
        $grade_codes = $grade_codes->toArray();

        return view('admin.admin_user_add', ['user_group' => $result['resultData'],
                                             'address_sido' => $address_sido,
                                             'region' => $region,
                                             'team'=>$team,
                                             'goods'=>$goods,
                                             'grade_codes'=>$grade_codes]);
    }

    //grouplist
    public function groupListCntSelect(){
        $main_code = $_COOKIE['main_code'];
        // select *from user_group order by sq 를 라라벨 형태로 변경.
        // $user_group = \App\UserGroup::orderBy('sq', 'asc')->get();
        $user_group = \App\UserGroup::select('user_groups.*', 't.t_cnt', 'p.p_cnt', 's.s_cnt')
            ->leftJoin(DB::raw("(SELECT group_seq, COUNT(group_seq) as t_cnt FROM teachers where main_code = '$main_code' GROUP BY group_seq) as t"), 'user_groups.id', '=', 't.group_seq')
            ->leftJoin(DB::raw("(SELECT group_seq, COUNT(group_seq) as p_cnt FROM parents GROUP BY group_seq) as p"), 'user_groups.id', '=', 'p.group_seq')
            ->leftJoin(DB::raw("(SELECT group_seq, COUNT(group_seq) as s_cnt FROM students where main_code = '$main_code' GROUP BY group_seq) as s"), 'user_groups.id', '=', 's.group_seq')
            ->where('group_type', '!=', 'admin')
            //->where('is_use', 'Y')
            //학부모일때는 메인코드 제외
            // ->where('main_code', $main_code)
            ->where(function($query) use ($main_code){
                $query->where('main_code', $main_code)
                    ->orWhere('group_type', 'parent');
            })
            ->orderBy('id', 'asc')
            ->get();
        //$user_group = \App\UserGroup::where('is_use', 'Y')->get();
        $result['resultData'] = $user_group;

        //전체 회원 리스트
        $teachCnt = \App\Teacher::where('main_code', $main_code)->count();
        $studentCnt = \App\Student::where('main_code', $main_code)->count();
        $parentCnt = \App\ParentTb::all()->count();

        $allCnt = $teachCnt + $studentCnt + $parentCnt;

        $result['allCnt'] = $allCnt;

        //그룹 없는 리스트 $main_code 조건 추가
        // $notteachCnt = \App\Teacher::where(DB::raw("ifnull(group_seq, '')"), '=', '')->count();
        $notteachCnt = \App\Teacher::where(DB::raw("ifnull(group_seq, '')"), '=', '')
                                    ->where('main_code', $main_code)
                                    ->count();
        // $notstudentCnt = \App\Student::where(DB::raw("ifnull(group_seq, '')"), '=', '')->count();
        $notstudentCnt = \App\Student::where(DB::raw("ifnull(group_seq, '')"), '=', '')
                                    ->where('main_code', $main_code)
                                    ->count();
        // $notparentCnt = \App\ParentTb::where(DB::raw("ifnull(group_seq, '')"), '=', '')->count();
        $notparentCnt = \App\ParentTb::where(DB::raw("ifnull(group_seq, '')"), '=', '')
                                    // ->where('main_code', $main_code)
                                    ->count();

        $notGroupCnt = $notteachCnt + $notstudentCnt + $notparentCnt;

        $result['notGroupCnt'] = $notGroupCnt;

        return $result;
    }
    //groupInsert
    public function groupInsert(Request $request){
        $main_code = $_COOKIE['main_code'];
        $teach_seq = session()->get('teach_seq');
        $data = $request->all();

        // $data = json_decode($data, true);
        $group_name = $data['group_name'];
        $remark = $data['remark'];
        $group_type = $data['group_type'];
        $is_use = $data['is_use'];
        $first_page = $data['first_page'] == null ? '' : $data['first_page'];
        $seq = $data['seq'];
        $is_general = $data['is_general'] ?? '';
        $sq = empty($data['sq']) ? '' : $data['sq'];

        if($sq == ''){
            $sq = \App\UserGroup::max('sq');
            $sq += 1;
        }

        //학부모일때는 main_code를 비워준다. 어디든 가져오기 위해서.
        if($group_type == 'parent'){
            $main_code = '';
        }

        //그룹타입2 생성.(타입2를 받아오지 않기 때문에 만들어줌.)
        $group_type2 = "";
        if($group_type == 'teacher'){
            $group_type2 = "run";
            //선생님이면서 총괄체크가 되어 있으면 general로 변경
            if($is_general == 'Y')
                $group_type2 = "general";
        }
        else if($group_type == 'student' || $group_type == 'parent'){
            $group_type2 = "normal";
        }

        // 입력 변수 설정
        $insert_group_data = [
            'group_name' => $group_name,
            'remark' => $remark,
            'group_type' => $group_type,
            'is_use' => $is_use,
            'first_page' => $first_page,
            'sq' => $sq,
            'main_code' => $main_code,
            'group_type2' => $group_type2,
        ];

        // 업데이트와, 생성구분
        if ($seq) {
            $insert_group_data['updated_id'] = $teach_seq;
        } else {
            $insert_group_data['created_id'] = $teach_seq;
        }

        $insert_group = \App\UserGroup::updateOrCreate(['id' => $seq], $insert_group_data);
        $insert_group->save();

        if($insert_group)
            $result['resultCode'] = 'success';
        else
            $result['resultCode'] = 'fail';

        return $result;
    }

    //useridcheck
    public function userIdCheck(Request $request){
        $result['resultCode'] = 'fail';
        $data = $request->all();
        // $grouptype = $data['grouptype'];
        $user_id = $data['user_id'];

        $chkid1 = "";
        $chkid2 = "";
        $chkid3 = "";
        // if(strpos($grouptype, 'student') !== false){
            $chkid1 = \App\Student::where('student_id', $user_id);
        // }
        // else if($grouptype == 'parent'){
            $chkid2 = \App\ParentTb::where('parent_id', $user_id);
        // }
        // else if($grouptype == 'teacher'){
            $chkid3 = \App\Teacher::where('teach_id', $user_id);
        // }

        $cnt = $chkid1->count()+$chkid2->count()+$chkid3->count();
        if($cnt != 0)
            $result['resultCode'] = 'fail';
        else
            $result['resultCode'] = 'success';

        return $result;

    }

    //userInsert 사용자 타입별로 등록
    public function userInsert(Request $request){
        $result['resultCode'] = 'fail';
        $main_code = $_COOKIE['main_code'] ?? '';
        $data = $request->all();
        $log_subject_str = $data['log_subject']??'';
        $user_id = empty($data['user_id']) ? '' : $data['user_id']; //아이디
        $group_seq = $data['group_seq'] ?? ''; //그룹코드
        $grouptype = $data['grouptype']; //그룹타입
        $sido = $data['sido'] ?? ''; //지역
        $region = $data['region'] ?? ''; //소속
        $team_code = $data['team_code'] ?? ''; //팀
        $school_name = $data['school_name'] ?? '';//학교명
        $grade = $data['grade'] ?? ''; //학년
        $goods_seq = empty($data['goods_seq']) ? '' : $data['goods_seq']; //이용권 키
        $goods_start_date = empty($data['goods_start_date']) ? '' : $data['goods_start_date']; //이용권 사용 시작일자
        $goods_end_date = empty($data['goods_end_date']) ? '' : $data['goods_end_date']; //이용권 사용 종료일자
        $user_pw = empty($data['user_pw']) ? '' : $data['user_pw']; //비밀번호
        $user_name = $data['user_name'] ?? ''; //사용자명
        $user_rrn = $data['user_rrn'] ?? ''; //사용자 주민등록번호
        $user_phone = $data['user_phone'] ?? ''; // 사용자 휴대전화
        $user_email = $data['user_email'] ?? ''; // 사용자 이메일
        $user_addr = $data['user_addr'] ?? ''; // 사용자 주소
        $user_key = empty($data['user_key']) ? '' : $data['user_key']; // 사용자 고유키
        $area = empty($data['area']) ? '' : $data['area']; //
        $num = empty($data['cnt']) ? '' : $data['cnt'];
        $conn_user_id = empty($data['conn_user_id']) ? '' : $data['conn_user_id'];

        //방과후 학생정보 상세보기에서 입력들어왔을때
        $user_phone2 = empty($data['user_phone2']) ? '' : $data['user_phone2'];
        $class_seq = empty($data['class_seq']) ? '' : $data['class_seq'];
        $st_class_name= empty($data['st_class_name']) ? '' : $data['student_class_name'];
        $change_teach_seq = $data['change_teach_seq'] ?? '';
        $change_team_code = $data['change_team_code'] ?? '';

        $user_insert = null;
        $rand_num = mt_rand(1000,5000);

        //수정이 아니면서 암호가 없으면 기본 암호로 설정
        if($user_pw == '' && strlen($user_key) < 1)
            $user_pw = '1234';

        //사용자 아이디 mysql password 사용하기
        //암호가 있으면 암호화
        if($user_pw != ''){
            $pw = DB::table('menus')
                    ->select(DB::raw('SHA1(?) as pw'))
                    ->setBindings([$user_pw])
                    ->first();
            $user_pw = $pw->pw;

            // $str_pw = 'SHA1("'.$user_pw.'") as pw';
            // $pw = DB::table('user_groups')
            //     ->select(DB::raw($str_pw))
            //     ->limit(1)
            //     ->get();

            // $user_pw =$pw[0]->pw;
        }

        //로그 데이터 생성
        $log = new LogMT();
        $req = new Request();
        $req->merge([
            'teach_seq' => 0,
            'student_seq' => 0,
            'parent_seq' => 0,
            'log_title' => '',
            'log_remark' => '',
            'log_subject' => '',
            'log_content' => '',
            'log_type' => '',
            'write_type' => 'teacher'
        ]);
        $log_content = "";

        //학생 등록 / 수정
        if(strpos($grouptype, 'student') !== false){
            if($user_id == '' || $user_id == null){
                $max_seq = \App\Student::max('id');
                $user_id = 's'.substr($user_phone, -4).$max_seq.$rand_num.$num;
            }
            if(strlen($conn_user_id) > 0){
                $parent = \App\ParentTb::where('parent_id', $conn_user_id)->first();
                $conn_user_id = $parent->id;
            }

            if(strlen($user_key) > 0){
                $user_insert = \App\Student::where('id', $user_key)->first();
                //수정일때만 로그 기록
                if($user_insert->student_name != $user_name && $user_name){
                    $log_content .= "이름 : ".$user_insert->student_name." -> ".$user_name."\n";
                }
                // if($user_insert->student_id != $user_id){
                //     $log_content .= "아이디 : ".$user_insert->student_id." -> ".$user_id."\n";
                // }
                if($user_insert->student_phone != $user_phone && $user_phone){
                    $log_content .= "휴대전화 : ".$user_insert->student_phone." -> ".$user_phone."\n";
                }
                if($user_insert->student_email != $user_email && $user_email){
                    $log_content .= "이메일 : ".$user_insert->student_email." -> ".$user_email."\n";
                }
                if($user_insert->rrn != $user_rrn && $user_rrn){
                    $log_content .= "주민등록번호 : ".$user_insert->rrn." -> ".$user_rrn."\n";
                }
                if($user_insert->school_name != $school_name && $school_name){
                    $log_content .= "학교명 : ".$user_insert->school_name." -> ".$school_name."\n";
                }
                if($user_insert->grade != $grade && $grade){
                    $log_content .= "학년 : ".$user_insert->grade." -> ".$grade."\n";
                }
                if($user_insert->group_seq != $group_seq && $group_seq){
                    $prev_group_name = \App\UserGroup::where('id', $user_insert->group_seq)->first()->group_name;
                    $after_group_name = \App\UserGroup::where('id', $group_seq)->first()->group_name;
                    $log_content .= "그룹 : ".$prev_group_name." -> ".$after_group_name."\n";
                }
                if($user_insert->student_address != $user_addr && $user_addr){
                    $log_content .= "주소 : ".$user_insert->student_address." -> ".$user_addr."\n";
                }
                if($user_insert->goods_detail_seq != $goods_seq && $goods_seq){
                    $log_content .= "이용권 : ".$user_insert->goods_detail_seq." -> ".$goods_seq."\n";
                }
                if($user_insert->parent_seq != $conn_user_id && $conn_user_id){
                    $prev_parent_name = \App\ParentTb::where('id', $user_insert->parent_seq)->first()->parent_name;
                    $after_parent_name = \App\ParentTb::where('id', $conn_user_id)->first()->parent_name;
                    $log_content .= "학부모 : ".$prev_parent_name." -> ".$after_parent_name."\n";
                }
                if($user_insert->area != $sido && $sido){
                    $log_content .= "지역 : ".$user_insert->area." -> ".$sido."\n";
                }
                if(strlen($user_pw) > 0 && $user_insert->student_pw != $user_pw){
                    // $log_content .= "비밀번호 : ".$user_insert->student_pw." -> ".$user_pw."\n";
                    // 비빌번호는 단순히 변경 되었다고 로그를 남김.
                    $log_content .= "비밀번호 변경\n";

                }
                if($user_insert->class_name && $user_insert->class_name != $st_class_name && $st_class_name){
                    $log_content .= "반 : ".$user_insert->class_name." -> ".$st_class_name."\n";
                }
                if($change_teach_seq && $user_insert->teach_seq != $change_teach_seq){
                    $log_content .= "담임 : ".$user_insert->teach_seq." -> ".$change_teach_seq."\n";
                }
                if($change_team_code && $user_insert->team_code != $change_team_code){
                    $log_content .= "팀 : ".$user_insert->team_code." -> ".$change_team_code."\n";
                }
                // 로그가 있으면 로그 기록
                if($log_content != ""){
                    $log_subject = "학생 상세페이지 수정";
                    if($log_subject_str) $log_subject = $log_subject_str;
                    $req->merge([
                        'student_seq' => $user_key,
                        'log_title' => 'user_detail_update',
                        'log_subject' => $log_subject,
                        'log_content' => $log_content,
                        'log_type' => 'user_update',
                    ]);
                    $log->insert($req);
                }
            }
            else
                $user_insert = new \App\Student;

            if($user_insert && $user_name) $user_insert->student_name = $user_name;
            if($user_insert && $main_code) $user_insert->main_code = $main_code;
            if($user_insert && $sido) $user_insert->area = $sido;
            if($user_insert && $user_id) $user_insert->student_id = $user_id;
            if($user_insert && $user_phone) $user_insert->student_phone = $user_phone;
            if($user_insert && $user_email) $user_insert->student_email = $user_email;
            if($user_insert && $user_rrn) $user_insert->rrn = $user_rrn;
            if($user_insert && $school_name) $user_insert->school_name = $school_name;
            if($user_insert && $grade) $user_insert->grade = $grade;
            if($user_insert && $group_seq) $user_insert->group_seq = $group_seq;
            if($user_insert && $conn_user_id) $user_insert->parent_seq = $conn_user_id;
            if($user_insert && $user_addr) $user_insert->student_address = $user_addr;
            if($user_insert && $change_teach_seq) $user_insert->teach_seq = $change_teach_seq;
            if($user_insert && $change_team_code) $user_insert->team_code = $change_team_code;
            if($st_class_name) $user_insert->class_name = $st_class_name;
            if($user_pw != '')
                $user_insert->student_pw = $user_pw;

            $user_insert->save();
            $student_seq = $user_insert->id;


            // 학생별 이용권 정보 저장
            if($goods_seq != ''){
                //goods에서 $goods_seq로 정보 가져오기.
                $goods = \App\Goods::find($goods_seq);

                $goods_detail_seq = $user_insert->goods_detail_seq;
                $is_goods_insert = false;
                //update 일때 insert일때 구분
                if(strlen($user_key) > 0 && strlen($goods_detail_seq) > 0){
                    $is_goods_insert = true;
                    $goods_details = \App\GoodsDetail::find($goods_detail_seq);
                }else{
                    $goods_details = new \App\GoodsDetail;
                }
                $goods_details->student_seq = $student_seq;
                $goods_details->goods_name = $goods->goods_name;
                $goods_details->goods_period = $goods->goods_period;
                $goods_details->goods_price = $goods->goods_price;
                $goods_details->goods_seq = $goods_seq;
                $goods_details->start_date = $goods_start_date;
                $goods_details->end_date = $goods_end_date;
                $goods_details->save();

                //상품 detail을 insert 했을때는 학생의 goods_detail_seq를 update 해준다.
                if($is_goods_insert){
                    $update_goods_detail_seq = $goods_details->id;
                    $user_insert->goods_detail_seq = $update_goods_detail_seq;
                    $user_insert->save();
                }
            }
        }
        //학부모 등록 / 수정
        else if($grouptype == 'parent'){
            if($user_id == '' || $user_id == null){
                $max_seq = \App\ParentTb::max('id');
                $user_id = 'p'.substr($user_phone, -4).$max_seq.$rand_num.$num;
            }

            if(strlen($user_key) > 0){
                $user_insert = \App\ParentTb::where('id', $user_key)->first();
                // 수정일때만 로그 기록
                if($user_insert->parent_name != $user_name && $user_name){
                    $log_content .= "이름 : ".$user_insert->parent_name." -> ".$user_name."\n";
                }
                // if($user_insert->parent_id != $user_id){
                //     $log_content .= "아이디 : ".$user_insert->parent_id." -> ".$user_id."\n";
                // }
                if($user_insert->parent_phone != $user_phone && $user_phone){
                    $log_content .= "휴대전화 : ".$user_insert->parent_phone." -> ".$user_phone."\n";
                }
                if($user_insert->parent_phone2 != $user_phone2 && $user_phone2){
                    $log_content .= "휴대전화2 : ".$user_insert->parent_phone2." -> ".$user_phone2."\n";
                }
                if($user_insert->parent_email != $user_email && $user_email){
                    $log_content .= "이메일 : ".$user_insert->parent_email." -> ".$user_email."\n";
                }
                if($user_insert->rrn != $user_rrn && $user_rrn){
                    $log_content .= "주민등록번호 : ".$user_insert->rrn." -> ".$user_rrn."\n";
                }
                if($user_insert->group_seq != $group_seq && $group_seq){
                    $prev_group_name = \App\UserGroup::where('id', $user_insert->group_seq)->first()->group_name;
                    $after_group_name = \App\UserGroup::where('id', $group_seq)->first()->group_name;
                    $log_content .= "그룹 : ".$prev_group_name." -> ".$after_group_name."\n";
                }
                if($user_insert->parent_address != $user_addr && $user_addr){
                    $log_content .= "주소 : ".$user_insert->parent_address." -> ".$user_addr."\n";
                }
                if($user_insert->area != $sido && $sido){
                    $log_content .= "지역 : ".$user_insert->area." -> ".$sido."\n";
                }
                if(strlen($user_pw) > 0 && $user_insert->parent_pw != $user_pw){
                    $log_content .= "비밀번호 : ".$user_insert->parent_pw." -> ".$user_pw."\n";
                }

                // 로그가 있으면 로그 기록
                if($log_content != ""){
                    $log_subject = "학부모 상세페이지 수정";
                    // $log_content = "학부모 상세페이지 수정\n".$log_content;
                    $req->merge([
                        'parent_seq' => $user_key,
                        'log_title' => 'user_detail_update',
                        'log_subject' => $log_subject,
                        'log_content' => $log_content,
                        'log_type' => 'user_update',
                    ]);
                    $log->insert($req);
                }

            }else
                $user_insert = new \App\ParentTb;

            if($user_insert && $user_name) $user_insert->parent_name = $user_name;
            if($user_insert && $main_code) $user_insert->main_code = $main_code;
            if($user_insert && $sido) $user_insert->area = $sido;
            if($user_insert && $user_id) $user_insert->parent_id = $user_id;
            if($user_insert && $user_phone) $user_insert->parent_phone = $user_phone;
            if($user_insert && $user_email) $user_insert->parent_email = $user_email;
            if($user_insert && $user_rrn) $user_insert->rrn = $user_rrn;
            if($user_insert && $group_seq) $user_insert->group_seq = $group_seq;
            if($user_insert && $team_code) $user_insert->team_code = $team_code;
            if($user_insert && $user_addr) $user_insert->parent_address = $user_addr;
            if($user_insert && $user_phone2) $user_insert->parent_phone2 = $user_phone2;
            if($user_phone2 != '') $user_insert->parent_phone2 = $user_phone2;
            if($user_pw != '')
                $user_insert->parent_pw = $user_pw;

            $user_insert->save();

            // 위에 했는데 왜 하는지몰라 주석처리.
            // if($user_insert){
            //     $par_update = \App\ParentTb::where('parent_id', $user_id)->update(['team_code'=>$team_code]);
            // }

            if($conn_user_id != ''){
                $stu_update = \App\Student::where('id', $conn_user_id)->update(['parent_seq'=>$user_insert->id]);
            }
        }
        //선생님 등록 / 수정
        else if($grouptype == 'teacher'){
            if($user_id == '' || $user_id == null){
                $max_seq = \App\Teacher::max('id');
                $user_id = 't'.substr($user_phone, -4).$max_seq.$rand_num.$num;
            }

            if(strlen($user_key) > 0){
                $user_insert = \App\Teacher::where('id', $user_key)->first();

                // 수정일때만 로그 기록
                if($user_insert->teach_name != $user_name){
                    $log_content .= "이름 : ".$user_insert->teach_name." -> ".$user_name."\n";
                }
                if($user_insert->teach_id != $user_id){
                    $log_content .= "아이디 : ".$user_insert->teach_id." -> ".$user_id."\n";
                }
                if($user_insert->teach_phone != $user_phone){
                    $log_content .= "휴대전화 : ".$user_insert->teach_phone." -> ".$user_phone."\n";
                }
                if($user_insert->teach_email != $user_email){
                    $log_content .= "이메일 : ".$user_insert->teach_email." -> ".$user_email."\n";
                }
                if($user_insert->rrn != $user_rrn){
                    $log_content .= "주민등록번호 : ".$user_insert->rrn." -> ".$user_rrn."\n";
                }
                if($user_insert->group_seq != $group_seq){
                    $prev_group_name = \App\UserGroup::where('id', $user_insert->group_seq)->first()->group_name;
                    $after_group_name = \App\UserGroup::where('id', $group_seq)->first()->group_name;
                    $log_content .= "그룹 : ".$prev_group_name." -> ".$after_group_name."\n";
                }
                if($user_insert->teach_address != $user_addr){
                    $log_content .= "주소 : ".$user_insert->teach_address." -> ".$user_addr."\n";
                }
                if($user_insert->area != $sido && $sido){
                    $log_content .= "지역 : ".$user_insert->area." -> ".$sido."\n";
                }
                if(strlen($user_pw) > 0 && $user_insert->teach_pw != $user_pw){
                    $log_content .= "비밀번호 : ".$user_insert->teach_pw." -> ".$user_pw."\n";
                }

                // 로그가 있으면 로그 기록
                if($log_content != ""){
                    $log_subject = "선생님 상세페이지 수정";
                    // $log_content = "선생님 상세페이지 수정\n".$log_content;
                    $req->merge([
                        'teach_seq' => $user_key,
                        'log_title' => 'user_detail_update',
                        'log_subject' => $log_subject,
                        'log_content' => $log_content,
                        'log_type' => 'user_update',
                    ]);
                    $log->insert($req);
                }
            }else
            // $region = \App\Region::where('id', $region)->first();

            $user_insert = new \App\Teacher;

            $user_insert->teach_name = $user_name;
            $user_insert->main_code = $main_code;
            $user_insert->teach_id = $user_id;  // 주석: teach_id를 항상 설정하도록 변경
            $user_insert->teach_phone = $user_phone;
            $user_insert->teach_email = $user_email;
            // $user_insert->team_code = $region->main_code; // 오류 main_code 와 team_code는 다른 필드임.
            $user_insert->team_code = $team_code;
            $user_insert->rrn = $user_rrn;
            $user_insert->area = $sido;
            $user_insert->group_seq = $group_seq;
            $user_insert->teach_address = $user_addr;
            $user_insert->region_seq = $region;

            if($user_pw != '')
                $user_insert->teach_pw = $user_pw;

            $user_insert->save();
        }
        else{
            return $result;
        }

        if($user_insert)
            $result['resultCode'] = 'success';
        return $result;
    }

    //userselect 등록 시 연동 학생-학부모 검색
    public function userSelect(Request $request){
        try {
            $result['resultCode'] = 'fail';

            $data = $request->all();
            $keyword = $data['keyword'];
            $searchtype = $data['searchtype'];

            $user_select = "";
            if($searchtype == 'parent'){
                $user_select = \App\ParentTb::select('*');
                if($keyword != "")
                    $user_select = $user_select->where('parent_name', $keyword);
            }
            else if(strpos($searchtype, 'student') !== false){
                $user_select = \App\Student::select('*');
                if($keyword != "")
                    $user_select = $user_select->where('student_name', $keyword);
            }

            $user = $user_select->get();

            $result['resultData'] = $user;
            $result['resultCode'] = 'success';

            return $result;

        } catch (\Exception $e) {
            return $result['resultCode'] = 'fail';

        }
    }
    //regionselect 소속 선택
    public function regionSelect(Request $request){
        $sido = $request->input('sido');
        $region = \App\Region::orderBy('region_name');
        if(strlen($sido) > 0){
            $region = $region->whereIn('id', function($query) use ($sido){
                $query->select('region_seq')->from('teams')->whereIn('team_code', function($query) use ($sido){
                    $query->select('team_code')->from('team_areas')->where('tarea_sido', $sido);
                });
            })
            ->orWhere('area', $sido);
        }
        $region = $region->get();

        $result['resultData'] = $region;
        $result['resultCode'] = 'success';

        return $result;
    }
    //groupselect 그룹 선택
    public function groupSelect(){
        $group = \App\UserGroup::orderBy('sq')->get();

        $result['resultData'] = $group;
        $result['resultCode'] = 'success';

        return $result;
    }
    //filedowonload
    public function fileDown(Request $request){
        $file = $request->input('file_name');
        $filePath = public_path("storage/".$file);
        return response()->download($filePath);
    }
    //userlist 모든 사용자 리스트 [추가 코드] > 포인트, 이용권 관련 추가 필요
    public function getUserList(Request $request) {
        $main_code = $_COOKIE['main_code'];
        $group_type = $request->input('group_type');
        $search_type = $request->input('search_type');
        $search_keyword = $request->input('search_keyword');

        // 학생 쿼리
        $students = DB::table('students as a')
            ->selectRaw("'student' as group_type, a.id as user_key,
                         CASE WHEN IFNULL(a.group_seq, '') = '' THEN '그룹없음(학생)' ELSE '학생' END as group_name,
                         a.student_name as user_name, a.student_id as user_id, a.student_phone as user_phone,
                         a.created_at, a.is_use, a.created_id, a.school_name, a.grade, a.school_name,
                         b.parent_name, c.teach_name, a.main_code, a.group_seq, a.area, a.student_email as email")
            ->leftJoin('parents as b', 'a.parent_seq', '=', 'b.id')
            ->leftJoin('teachers as c', 'a.teach_seq', '=', 'c.id')
            ->where('a.main_code', $main_code);

        if ($group_type == 'none_group_user') {
            $students->whereNull('a.group_seq');
        }

        // 학부모 쿼리
        $parents = DB::table('parents as a')
            ->selectRaw("'parent' as group_type, a.id as user_key,
                         CASE WHEN IFNULL(a.group_seq, '') = '' THEN '그룹없음(학부모)' ELSE '학부모' END as group_name,
                         a.parent_name as user_name, a.parent_id as user_id, a.parent_phone as user_phone,
                         a.created_at, a.is_use, a.created_id, '' as school_name, '' as grade, '' as student_name,
                         '' as parent_name, '' as teach_name, a.main_code, a.group_seq, a.area, a.parent_email as email")
            ->where('a.main_code', $main_code);

        if ($group_type == 'none_group_user') {
            $parents->whereNull('a.group_seq');
        }

        // 선생님 쿼리
        $teachers = DB::table('teachers as a')
            ->selectRaw("'teacher' as group_type, a.id as user_key,
                         IFNULL(b.group_name, '그룹없음(선생님)') as group_name,
                         a.teach_name as user_name, a.teach_id as user_id, a.teach_phone as user_phone,
                         a.created_at, a.is_use, a.created_id, '' as school_name, '' as grade, '' as student_name,
                         '' as parent_name, '' as teach_name, a.main_code, a.group_seq, a.area, a.teach_email as email")
            ->leftJoin('user_groups as b', 'a.group_seq', '=', 'b.id')
            ->where('a.main_code', $main_code);

        if ($group_type == 'none_group_user') {
            $teachers->whereNull('a.group_seq');
        }


        //검색어 타입에 따라 where절 추가
        if (!empty($search_keyword)) {
            switch ($search_type) {
                case 'id':
                    $teachers->having('user_id', 'like', '%' . $search_keyword . '%');
                    $parents->having('user_id', 'like', '%' . $search_keyword . '%');
                    $students->having('user_id', 'like', '%' . $search_keyword . '%');
                    break;
                case 'phone':
                    $teachers->having('user_phone', 'like', '%' . $search_keyword . '%');
                    $parents->having('user_phone', 'like', '%' . $search_keyword . '%');
                    $students->having('user_phone', 'like', '%' . $search_keyword . '%');
                    break;
                case 'name':
                    $teachers->having('user_name', 'like', '%' . $search_keyword . '%');
                    $parents->having('user_name', 'like', '%' . $search_keyword . '%');
                    $students->having('user_name', 'like', '%' . $search_keyword . '%');
                    break;
                case 'parent':
                    $teachers->having('parent_name', 'like', '%' . $search_keyword . '%');
                    $parents->having('parent_name', 'like', '%' . $search_keyword . '%');
                    $students->having('parent_name', 'like', '%' . $search_keyword . '%');
                    break;
                case 'teacher':
                    $teachers->having('teach_name', 'like', '%' . $search_keyword . '%');
                    $parents->having('teach_name', 'like', '%' . $search_keyword . '%');
                    $students->having('teach_name', 'like', '%' . $search_keyword . '%');
                    break;
                case 'school':
                    $teachers->having('school_name', 'like', '%' . $search_keyword . '%');
                    $parents->having('school_name', 'like', '%' . $search_keyword . '%');
                    $students->having('school_name', 'like', '%' . $search_keyword . '%');
                    break;
                case 'grade':
                    $teachers->having('grade', 'like', '%' . $search_keyword . '%');
                    $parents->having('grade', 'like', '%' . $search_keyword . '%');
                    $students->having('grade', 'like', '%' . $search_keyword . '%');
                    break;
                case 'group':
                    $teachers->having('group_name', 'like', '%' . $search_keyword . '%');
                    $parents->having('group_name', 'like', '%' . $search_keyword . '%');
                    $students->having('group_name', 'like', '%' . $search_keyword . '%');
                    break;
                default:
                    // 유효하지 않은 search_type 처리
                    break;
            }
        }

        // 학생, 학부모, 선생님 쿼리 결합
        $unionQuery = $teachers->unionAll($parents)->unionAll($students);

        $resultData = $unionQuery->orderBy('created_at', 'DESC')->get();

        return [
            'resultCode' => 'success',
            'resultData' => $resultData
        ];
    }
    //userlist 학생 리스트 [추가 코드] > 포인트, 이용권 관련 추가 필요
    public function studentSelect(Request $request){
        $main_code = $request->input('main_code') ?? $_COOKIE['main_code'] ?? session()->get('main_code');
        $group_type = $request->input('group_type');
        $group_seq = $request->input('group_seq');
        $search_type = $request->input('search_type');
        $search_keyword = $request->input('search_keyword');
        $search_region = $request->input('search_region');
        $search_team = $request->input('search_team');
        $id = $request->input('id');

        $sql = \App\Student:: select( 'students.*',
                DB::raw("'학생' as group_name"),
                'parents.parent_id',
                'parents.parent_name',
                'teachers.teach_name',
                'teachers.region_seq as teach_region_seq',
                'teachers.team_code as teach_team_code',
                'gd.goods_seq',
                'gd.start_date as goods_start_date',
                'gd.end_date as goods_end_date',
                'gd.goods_name',
                'gd.goods_period',
                'gd.stop_day_sum',
                'gd.stop_cnt',
                'gd.is_use as goods_is_use',
                'grade_codes.code_name as grade_name'
            );
        $sql = $sql->leftJoin('parents', 'parents.id', '=', 'students.parent_seq');
        $sql = $sql->leftJoin('teachers', 'teachers.id', '=', 'students.teach_seq');
        $sql = $sql->leftJoin('user_groups as u', 'u.id', '=', 'students.group_seq');
        $sql = $sql->leftJoin('goods_details as gd', 'gd.id', '=', 'students.goods_detail_seq');
        $sql = $sql->leftJoin('codes as grade_codes', 'grade_codes.id', '=', 'students.grade');

        $sql = $sql->where('students.main_code', $main_code);
        //그룹이 있으면
        if($group_seq != '')
            $sql = $sql->whereIn('students.group_seq', $group_seq);
        //ID 있으면
        if($id != '')
            $sql = $sql->where('students.id', $id);

        //검색어 타입에 따라 where절 추가
        if($search_keyword != '' && $search_type == 'id')
            $sql = $sql->where('students.student_id', 'like', '%'.$search_keyword.'%');
        else if($search_keyword != '' && $search_type == 'phone')
            $sql = $sql->where('students.student_phone', 'like', '%'.$search_keyword.'%');
        else if($search_keyword != '' && $search_type == 'name')
            $sql = $sql->where('students.student_name', 'like', '%'.$search_keyword.'%');
        else if($search_keyword != '' && $search_type == 'parent')
            $sql = $sql->where('parents.parent_name', 'like', '%'.$search_keyword.'%');
        else if($search_keyword != '' && $search_type == 'teacher')
            $sql = $sql->where('teachers.teach_name', 'like', '%'.$search_keyword.'%');
        else if($search_keyword != '' && $search_type == 'school')
            $sql = $sql->where('students.school_name', 'like', '%'.$search_keyword.'%');
        else if($search_keyword != '' && $search_type == 'grade')
            $sql = $sql->where('students.grade', 'like', '%'.$search_keyword.'%');

        $result['resultData'] = $sql->get();
        $result['resultCode'] = 'success';
        return $result;
    }
    //userlist 학부모 리스트 [추가 코드] > 포인트, 이용권 관련 추가 필요
    public function parentSelect(Request $request){
        $main_code = $_COOKIE['main_code'];
        $group_type = $request->input('group_type');
        $group_seq = $request->input('group_seq');
        $search_type = $request->input('search_type');
        $search_keyword = $request->input('search_keyword');
        $search_region = $request->input('search_region');
        $search_team = $request->input('search_team');
        $id = $request->input('id');

        $sql = \App\ParentTb::select( 'parents.*',
                                        DB::raw("'학부모' as group_name"),
                                        'students.id as student_seq',
                                        'students.point_now',
                                        'students.student_id',
                                        'students.student_name',
                                        'students.teach_seq',
                                        'teachers.teach_name',
                                        'teachers.region_seq as teach_region_seq',
                                        'teachers.team_code as teach_team_code',
                                        'gd.goods_seq',
                                        'gd.start_date as goods_start_date',
                                        'gd.end_date as goods_end_date',
                                        'gd.goods_name',
                                        'gd.goods_period'
                                    );
        $sql = $sql->leftJoin('students', 'students.parent_seq', '=', 'parents.id');
        $sql = $sql->leftJoin('teachers', 'teachers.id', '=', 'students.teach_seq');
        $sql = $sql->leftJoin('goods_details as gd', 'gd.id', '=', 'students.goods_detail_seq');


        // $sql = $sql->where('parents.main_code', $main_code); // 학부모는 main_code로 검색하지 않는다.
        //그룹이 있으면
        if($group_seq != '')
            $sql = $sql->whereIn('parents.group_seq', $group_seq);
        //ID 있으면
        if($id != '')
            $sql = $sql->where('parents.id', $id);

        //검색어 타입에 따라 where절 추가
        if($search_keyword != '' && $search_type == 'id')
            $sql = $sql->where('parents.parent_id', 'like', '%'.$search_keyword.'%');
        else if($search_keyword != '' && $search_type == 'phone')
            $sql = $sql->where('parents.parent_phone', 'like', '%'.$search_keyword.'%');
        else if($search_keyword != '' && $search_type == 'name')
            $sql = $sql->where('parents.parent_name', 'like', '%'.$search_keyword.'%');
        else if($search_keyword != '' && $search_type == 'teacher')
            $sql = $sql->where('teachers.teach_name', 'like', '%'.$search_keyword.'%');
        else if($search_keyword != '' && $search_type == 'school')
            $sql = $sql->where('students.school_name', 'like', '%'.$search_keyword.'%');
        else if($search_keyword != '' && $search_type == 'grade')
            $sql = $sql->where('students.grade', 'like', '%'.$search_keyword.'%');


        $sql = $sql->orderBy('parents.id', 'DESC')->orderBy('students.id', 'DESC');

        $result['resultData'] = $sql->get();
        $result['sql'] = $sql->toSql();
        $result['resultCode'] = 'success';
        return $result;
    }
    //userlist 선생님 리스트 [추가 코드] > 재직일자 관련 추가 필요
    public function teacherSelect(Request $request){
        $main_code = $_COOKIE['main_code'];
        if(strlen($main_code) < 1) $main_code = session()->get('main_code');

        $group_type = $request->input('group_type');
        $group_type2 = $request->input('group_type2');
        $group_seq = $request->input('group_seq');
        $search_type = $request->input('search_type');
        $search_keyword = $request->input('search_keyword');
        $search_region = $request->input('search_region');
        $search_team = $request->input('search_team');
        $id = $request->input('id');

        $page = $request->input('page');
        $page_max = $request->input('page_max');
        $page_max = $page_max ?? 10;
        // $sql->paginate($page_max, ['*'], 'page', $page);


        $sql = \App\Teacher::select('teachers.*',
                                    'user_groups.group_name',
                                    'user_groups.group_type2',
                                    'user_groups.group_type3',
                                    'rgion.region_name',
                                    'tm.team_name'
                                    );
        $sql = $sql->leftJoin('user_groups', 'user_groups.id', '=', 'teachers.group_seq');
        $sql = $sql->leftJoin('regions as rgion', 'rgion.id', '=', 'teachers.region_seq');
        $sql = $sql->leftJoin('teams as tm', 'tm.team_code', '=', 'teachers.team_code');

        $sql = $sql->where('teachers.main_code', $main_code);

        // 그룹이 있으면
        if($group_seq != '')
            $sql = $sql->whereIn('teachers.group_seq', $group_seq);
        // 그룹 타입이 있으면
        if($group_type != ''){
            $group_type = explode(',', $group_type);
            $sql = $sql->whereIn('user_groups.group_type', $group_type);
        }
        // 그룹 타입2가 있으면
        if($group_type2 != ''){
            $group_type2 = explode(',', $group_type2);
            $sql = $sql->whereIn('user_groups.group_type2', $group_type2);
        }
        // ID 있으면
        if($id != '')
            $sql = $sql->where('teachers.id', $id);

        //검색어 타입에 따라 where절 추가
        if($search_keyword != '' && $search_type == 'id')
            $sql = $sql->where('teachers.teach_id', 'like', '%'.$search_keyword.'%');
        else if($search_keyword != '' && $search_type == 'phone')
            $sql = $sql->where('teachers.teach_phone', 'like', '%'.$search_keyword.'%');
        else if($search_keyword != '' && $search_type == 'name')
            $sql = $sql->where('teachers.teach_name', 'like', '%'.$search_keyword.'%');
        else if($search_keyword != '' && $search_type == 'group')
            $sql = $sql->where('user_groups.group_name', 'like', '%'.$search_keyword.'%');

        //소속,팀
        if(strlen($search_region) > 0){
            $sql = $sql->where('teachers.region_seq', $search_region);
        }
        if(strlen($search_team) > 0){
            $sql = $sql->where('teachers.team_code', $search_team);
        }

        $result['sql'] = $sql->toSql();
        if(strlen($page) > 0){
            $sql = $sql->paginate($page_max, ['*'], 'page', $page);
            $result['resultData'] = $sql;
        }else
            $result['resultData'] = $sql->get();
        $result['resultCode'] = 'success';
        return $result;
    }
    //userlist 사용자 활성화/비활성화 업데이트
    public function userUseUpdate(Request $request){
        $user_key = $request->input('user_key');
        $group_type = $request->input('group_type');
        $chk_val = $request->input('chk_val');

        //로그 데이터 생성.
        $log = new LogMT();
        $req = new Request();
        $req->merge([
            'teach_seq' => 0,
            'student_seq' => 0,
            'parent_seq' => 0,
            'log_title' => '',
            'log_remark' => '',
            'log_subject' => '',
            'log_content' => '',
            'log_type' => '',
            'write_type' => 'teacher'
        ]);

        if(strpos($group_type, 'student') !== false){
            $update = \App\Student::where('id', $user_key);
            $prev_is_use = $update->first()->is_use;
            $update = $update->update(['is_use'=>$chk_val]);
            $req->merge([
                'student_seq' => $user_key,
                'log_title' => 'user_list_update',
                'log_subject' => '학생 활성화 변경',
                'log_content' => $prev_is_use.' -> '.$chk_val,
                'log_type' => 'user_update'
            ]);
            //다르면 로그 생성
            if($prev_is_use != $chk_val){
                $log->insert($req);
            }
        }
        else if($group_type == 'teacher'){
            $update = \App\Teacher::where('id', $user_key);
            $prev_is_use = $update->first()->is_use;
            $update = $update->update(['is_use'=>$chk_val]);
            $req->merge([
                'teach_seq' => $user_key,
                'log_title' => 'user_list_update',
                'log_subject' => '선생님 활성화 변경',
                'log_content' => $prev_is_use.' -> '.$chk_val,
                'log_type' => 'user_update'
            ]);
            //다르면 로그 생성
            if($prev_is_use != $chk_val){
                $log->insert($req);
            }
        }
        else if($group_type == 'parent'){
            $update = \App\ParentTb::where('id', $user_key);
            $prev_is_use = $update->first()->is_use;
            $update = $update->update(['is_use'=>$chk_val]);
            $req->merge([
                'parent_seq' => $user_key,
                'log_title' => 'user_list_update',
                'log_subject' => '학부모 활성화 변경',
                'log_content' => $prev_is_use.' -> '.$chk_val,
                'log_type' => 'user_update'
            ]);
            //다르면 로그 생성
            if($prev_is_use != $chk_val){
                $log->insert($req);
            }
        }

        $result['resultCode'] = $update;

        //update_history
        $hist = array();
        $hist['hist_type'] = 'is_use';
        $hist['hist_group'] = $group_type;
        $hist['user_key'] = $user_key;
        $hist['change_val'] = $chk_val;
        $hist = new \Illuminate\Http\Request($hist);

        $test = $this->userInfoUpdateHistoy($hist);

        return $result;
    }

    //userlist 사용자 수정 내역 남기기
    public function userInfoUpdateHistoy(Request $request){
        $hist_type = $request->input('hist_type');
        $hist_group = $request->input('hist_group');
        $user_key = $request->input('user_key');
        $change_val = $request->input('change_val');



        return true;
    }

    // 사용자 포인트 추가.
    public function pointInsert(Request $request){
        $main_code = $_COOKIE['main_code'];
        $point_seq = $request->input('point_seq');
        $user_keys = $request->input('user_keys'); // 1,2,3,4
        $point = $request->input('point');
        $remark = $request->input('remark');
        $point_type = $request->input('point_type');

        //입력자
        $teach_seq = session()->get('teach_seq');
        $no_insert_today_students = array();
        $no_insert_month_students = array();
        $point_nows = array();

        //$point_seq 있으면 update 없으면 insert
        if(strlen($point_seq) > 0){

            $update_history = \App\PointHistory::where('id', $point_seq)->update(['point'=>$point, 'remark'=>$remark, 'updated_id'=>$teach_seq]);

            // 포인트 히스토리의 student_seq 의 합산을 구해서 students 테이블 point_now에 업데이트
            $point_historys = \App\PointHistory::select(DB::raw('sum(point) as point_sum'))->where('student_seq', $user_keys)->get();
            $point_now = $point_historys[0]->point_sum;

            // 현재 합산을 포인트 히스토리에 업데이트
            $update_history->point_now = $point_now;
            $update_history->save();

            $update = \App\Student::where('id', $user_keys)->update(['point_now'=>$point_now]);
        }
        else{
            $user_keys = explode(',', $user_keys);
            foreach($user_keys as $key => $val){
                // 학생별 하루 포인트 합산
                $today_sum_point = \App\PointHistory::
                select(DB::raw('ifnull(sum(point), 0) as point_sum'))
                ->where('created_at', '>=', date('Y-m-d').' 00:00:00')
                ->where('created_at', '<', date('Y-m-d', strtotime('+1 day')).' 00:00:00')
                ->where('student_seq', $val)
                ->where('point_type', 'teacher_give')
                ->first();
                $today_sum_point = $today_sum_point->point_sum;
                $result['today_sum_point'] = $today_sum_point;

                // 학생별 지금을 기준으로 -한달 포인트 합산
                $month_sum_point = \App\PointHistory::
                select(DB::raw('ifnull(sum(point), 0) as point_sum'))
                ->where('created_at', '>=', date('Y-m-d', strtotime('-1 month')).' 00:00:00')
                ->where('created_at', '<', date('Y-m-d').' 00:00:00')
                ->where('student_seq', $val)
                ->where('point_type', 'teacher_give')
                ->first();
                $month_sum_point = $month_sum_point->point_sum;

                $is_continue = true;
                // 하루 50포인트, 한달 300 포인트 이상 지급 불가
                if(($today_sum_point + $point) > 50){
                    $no_insert_today_students[] = $val;
                    $is_continue = false;
                }
                if(($month_sum_point + $point) > 300){
                    $no_insert_month_students[] = $val;
                    $is_continue = false;
                }

                $insert = new \App\PointHistory;
                $insert->student_seq = $val;
                $insert->point = $point;
                $insert->remark = $remark;
                $insert->created_id = $teach_seq;
                $insert->main_code = $main_code;
                $insert->point_type = $point_type;
                if($is_continue){
                    $point_historys = \App\PointHistory::select(DB::raw('sum(point) as point_sum'))->where('student_seq', $val)->get();
                    $point_now = $point_historys[0]->point_sum;

                    //저장이 되야한다면 point_now를 업데이트
                    $insert->point_now = $point_now;
                    $insert->save();
                }
            }

            //각각의 포인트 히스토리의 student_seq 의 합산을 구해서 students 테이블 point_now에 업데이트
            foreach($user_keys as $key => $val){
                $point_historys = \App\PointHistory::select(DB::raw('sum(point) as point_sum'))->where('student_seq', $val)->get();
                $point_now = $point_historys[0]->point_sum;
                $update = \App\Student::where('id', $val)->update(['point_now'=>$point_now]);
                $point_nows[] = $point_now;
            }
        }

        $student_names_day = "";
        if(count($no_insert_today_students) > 0){
            $student_names_day = \App\Student::select('student_name')->whereIn('id', $no_insert_today_students)->get();
            $student_names_day = $student_names_day->pluck('student_name')->toArray();
        }
        $student_names_month = "";
        if(count($no_insert_month_students) > 0){
            $student_names_month = \App\Student::select('student_name')->whereIn('id', $no_insert_month_students)->get();
            $student_names_month = $student_names_month->pluck('student_name')->toArray();
        }

        $result = array();
        $result['resultCode'] = 'success';
        $result['no_insert_day_students'] = $student_names_day;
        $result['no_insert_month_students'] = $student_names_month;
        $result['point_nows'] = $point_nows;
        return response()->json($result);
    }

    // 사용자 포인트 내역 조회
    public function pointHistorySelect(Request $request){
        $main_code = $_COOKIE['main_code'];
        $user_key = $request->input('user_key');

        $page = $request->input('page');
        $page_max = $request->input('page_max');
        $page_max = $page_max ?? 6;
        $is_page = $request->input('is_page');

        $point_history = \App\PointHistory::select(
            'point_histories.*',
            'teachers.teach_name as created_name'
        )
            ->leftJoin('teachers', 'teachers.teach_id', '=', 'point_histories.created_id')
            ->where('student_seq', $user_key);
                        // ->orderBy('created_at', 'desc')


        if($is_page == 'Y'){
            $point_history = $point_history->paginate($page_max, ['*'], 'page', $page);
        }else{
            $point_history = $point_history->get();
        }

        $result = array();
        $result['resultCode'] = 'success';
        $result['resultData'] = $point_history;
        return response()->json($result);
    }

    // 소속에 따른 팀 가져오기.
    public function teamSelect(Request $request){
        $region_seq = $request->input('region_seq');
        // $sql = "SELECT * from teams where region_seq = 10"
        $teams = \App\Team::where('region_seq', $region_seq)->get();


        $result = array();
        $result['resultCode'] = 'success';
        $result['resultData'] = $teams;
        return response()->json($result);
    }

    // 학생 이용권 상세 내역 SELECT
    public function goodsDetailSelect(Request $request){
        $student_seq = $request->input('student_seq');

        $goods_details = \App\GoodsDetail::select(
            'goods_details.*'
         )
        ->where('student_seq', $student_seq)
        ->get();
        //추가 코드 결제 내역이 left join 되어야함.

        $result = array();
        $result['resultCode'] = 'success';
        $result['resultData'] = $goods_details;
        return response()->json($result);
    }

    // 담당 선생님 변경
    public function teacherChargeUpdate(Request $request){
        $student_seq = $request->input('student_seq');
        $teach_seq = $request->input('teach_seq');
        // $region_seq = $request->input('region_seq'); // 추후 쓸려면 추가. 현재는 students에 컬럼이 없음.
        $team_code = $request->input('team_code');

        //로그 데이터 생성.
        $log = new LogMT();
        $req = new Request();
        $req->merge([
            'teach_seq' => 0,
            'student_seq' => 0,
            'parent_seq' => 0,
            'log_title' => '',
            'log_remark' => '',
            'log_subject' => '',
            'log_content' => '',
            'log_type' => '',
            'write_type' => 'teacher'
        ]);

        // 추후 student_seq가 배열로 들어올 수도 있음.
        // 배열일때 아래 코드를 수정해야함 [추가 코드]

        //학생의 담임 키를 가져온다.
        $update = \App\Student::where('id', $student_seq)->first();

        $prev_str = "";
        $prev_teach_seq = $update->teach_seq;
        //이전과 이후 선생님이 다르면 저장 후  로그 생성
        if($prev_teach_seq != $teach_seq){

            // 없거나, 0이면 이전 담임정보를 가져오지 않는다.
            if($prev_teach_seq != null && $prev_teach_seq != 0){
                //저장 전 담당 선생님 이름 / 소속 이름 / 팀이름 가져오기.
                $prev_teacher = \App\Teacher::select('teachers.teach_name', 'teams.team_name', 'regions.region_name')
                                            ->leftJoin('teams', 'teams.team_code', '=', 'teachers.team_code')
                                            ->leftJoin('regions', 'regions.id', '=', 'teams.region_seq')
                                            ->where('teachers.id', $prev_teach_seq)
                                            ->first();
                $prev_teach_name = $prev_teacher->teach_name;
                $prev_region_name = $prev_teacher->region_name;
                $prev_team_name = $prev_teacher->team_name;
                $prev_str = $prev_region_name.' '.$prev_team_name.' '.$prev_teach_name;
            }

            // 저장 할 선생님 이름 / 소속 이름 / 팀이름 가져오기.
            $after_teacher = \App\Teacher::select('teachers.teach_name', 'teams.team_name', 'regions.region_name')
                                        ->leftJoin('teams', 'teams.team_code', '=', 'teachers.team_code')
                                        ->leftJoin('regions', 'regions.id', '=', 'teams.region_seq')
                                        ->where('teachers.id', $teach_seq)
                                        ->first();
            $teach_name = \App\Teacher::where('id', $teach_seq)->first()->teach_name;
            $region_name = $after_teacher->region_name;
            $team_name = $after_teacher->team_name;
            $after_str = $region_name.' '.$team_name.' '.$teach_name;

            // 담당 선생님 변경
            $update = $update->update(['teach_seq'=>$teach_seq, 'team_code'=>$team_code]);

            // 로그 저장
            $req->merge([
                'student_seq' => $student_seq,
                'log_title' => 'user_list_update',
                'log_subject' => '담당 선생님 변경',
                'log_content' => $prev_str.' -> '.$after_str,
                'log_type' => 'user_update'
            ]);
            $log->insert($req);
        }

        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    // 선택 선생님의 담당 학생수 조회
    public function teacherChargeStcntSelect(Request $request){
        $teach_seqs = $request->input('teach_seqs');
        $teach_seqs = explode(',', $teach_seqs);

        //select students.teach_seq, teach_name, count(*) as cnt from students left join teachers on students.teach_seq = teachers.teach_seq
        //where students.teach_seq is not null and students.teach_seq <> '0' group by students.teach_seq, teach_name

        $teacher_charge_stcnt = \App\Student::select('students.teach_seq', 'teachers.teach_name', DB::raw('count(*) as student_cnt'))
                                            ->leftJoin('teachers', 'students.teach_seq', '=', 'teachers.id')
                                            ->whereIn('students.teach_seq', $teach_seqs)
                                            ->where('students.teach_seq', '<>', '0')
                                            ->groupBy('students.teach_seq', 'teachers.teach_name')
                                            ->get();

        $result = array();
        $result['resultCode'] = 'success';
        $result['resultData'] = $teacher_charge_stcnt;
        return response()->json($result);
    }

    // 학생 이용권 끝날자 업데이트.
    public function dayUpdate(Request $request){
        $student_seqs = $request->input('student_seqs');
        $day_addnum = $request->input('day_addnum')*1;
        $log_remark = $request->input('log_remark');

        //$student_seqs = '1,2,3,' 이므로 배열로 만든다.
        $student_seqs = explode(',', $student_seqs);

        //로그 데이터 생성.
        $log = new LogMT();
        $req = new Request();
        $req->merge([
            'teach_seq' => 0,
            'student_seq' => 0,
            'parent_seq' => 0,
            'log_title' => '',
            'log_remark' => '',
            'log_subject' => '',
            'log_content' => '',
            'log_type' => '',
            'write_type' => 'teacher'
        ]);

        //로그를 $student_seqs 만큼 생성한다.
        // 수정하려는 학생의 정보를 불러오고
        // 날짜를 더하기 전 끝 날짜를 가저와 변수로 저장.
        // 날짜를 더한다.
        // 더한 후 끝 날짜를 로그에 남긴다.
        // 다음은 원래 코드이므로 확인후 새로 코드 생성.
        // $update = \App\GoodsDetail::whereIn('id', function($query) use ($student_seqs){
        //     $query->select('goods_detail_seq')->from('students')->whereIn('id', $student_seqs);
        // })->update(['end_date'=>DB::raw("date_add(end_date, interval '$day_addnum' day)")]);

        $teach_seq = session()->get('teach_seq');
        foreach($student_seqs as $key => $val){
            $goods_details = \App\GoodsDetail::select('goods_details.*')
                                                ->join('students', 'students.goods_detail_seq', '=', 'goods_details.id')
                                                ->where('students.id', $val)
                                                ->where('goods_details.student_seq', $val)
                                                ->first();
            $start_date = $goods_details->start_date;
            $prev_end_date = $goods_details->end_date;
            $goods_details->end_date = date('Y-m-d', strtotime($goods_details->end_date.' + '.$day_addnum.' days'));
            $goods_details->save();

            $goods_detail_seq = $goods_details->id;
            $student_seq = $val;


            // 이용권 연장 로그 INSERT
            $goods_detail_logs = new \App\GoodsDetailLog;
            $goods_detail_logs->type = 'plus';
            $goods_detail_logs->goods_detail_seq = $goods_detail_seq;
            $goods_detail_logs->student_seq = $student_seq;
            $goods_detail_logs->start_date = $start_date;
            $goods_detail_logs->end_date = $goods_details->end_date;
            // $goods_detail_logs->stop_start_date = $stop_start_date;
            // $goods_detail_logs->stop_end_date = $stop_end_date;
            $goods_detail_logs->day_cnt = $day_addnum;
            $goods_detail_logs->remark = $log_remark;
            $goods_detail_logs->created_id = $teach_seq;
            $goods_detail_logs->created_id_type = 'teacher';
            $goods_detail_logs->save();

            // $prev_end_date.' -> '.$goods_details->end_date.' ('.$day_addnum.'일 연장)',
            $req->merge([
                'student_seq' => $val,
                'log_title' => 'user_list_update',
                'log_subject' => '이용권 연장',
                'log_content' => $start_date.' ~ '.$prev_end_date.' -> '.$start_date.' ~ '.$goods_details->end_date.' ('.$day_addnum.'일 연장)',
                'log_type' => 'user_goods_plus_update',
                'log_remark' => $log_remark,
                'other1_seq' => $goods_detail_seq
            ]);
            $log->insert($req);
        }


        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    // (선생님) 소속/관할 변경
    public function teacherTeamUpdate(Request $request){
        $teach_seqs = $request->input('teach_seqs');
        $chg_region_seq = $request->input('chg_region_seq');
        $chg_team_code = $request->input('chg_team_code');

        // 로그 데이터 생성.
        $log = new LogMT();
        $req = new Request();
        $req->merge([
            'teach_seq' => 0,
            'student_seq' => 0,
            'parent_seq' => 0,
            'log_title' => '',
            'log_remark' => '',
            'log_subject' => '',
            'log_content' => '',
            'log_type' => '',
            'write_type' => 'teacher'
        ]);

        $teach_seqs = explode(',', $teach_seqs);
        //선생님의 region_seq, team_code(공백가능) 변경
        // $update = \App\Teacher::whereIn('id', $teach_seqs)->update(['region_seq'=>$chg_region_seq, 'team_code'=>$chg_team_code]);
        //각각의 prev_region_name , prev_team_name 가져와서 로그에 남긴후 update
        foreach($teach_seqs as $key => $val){
            $prev_teacher = \App\Teacher::select('teachers.teach_name', 'teams.team_name', 'regions.region_name')
                                    ->leftJoin('teams', 'teams.team_code', '=', 'teachers.team_code')
                                    ->leftJoin('regions', 'regions.id', '=', 'teachers.region_seq')
                                    ->where('teachers.id', $val)
                                    ->first();
            $prev_region_name = $prev_teacher->region_name ?? '';
            $prev_team_name = $prev_teacher->team_name ?? '미배정';
            $prev_str = $prev_region_name.' '.$prev_team_name;

            $update = \App\Teacher::where('id', $val)->update(['region_seq'=>$chg_region_seq, 'team_code'=>$chg_team_code]);

            $teacher = \App\Teacher::select('teachers.teach_name', 'teams.team_name', 'regions.region_name')
                                    ->leftJoin('teams', 'teams.team_code', '=', 'teachers.team_code')
                                    ->leftJoin('regions', 'regions.id', '=', 'teachers.region_seq')
                                    ->where('teachers.id', $val)
                                    ->first();
            $region_name = $teacher->region_name ?? '';
            $team_name = $teacher->team_name ?? '미배정';
            $after_str = $region_name.' '.$team_name;

            $req->merge([
                'teach_seq' => $val,
                'log_title' => 'user_list_update',
                'log_subject' => '소속/관할 변경',
                'log_content' => $prev_str.' -> '.$after_str,
                'log_type' => 'user_update'
            ]);
            $log->insert($req);
        }





        //해당 선생님이 담당하고 있던 학생들의 team_code와 teach_seq를 비워준다. NULL
        $update = \App\Student::whereIn('teach_seq', $teach_seqs)->update(['teach_seq'=>null]);
        $update2 = \App\Student::whereIn('counsel_teach_seq', $teach_seqs)->update(['counsel_teach_seq'=>null]);

        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    // (선생님) 재직 상태 변경
    public function teacherStatusUpdate(Request $request){
        $teach_seq = $request->input('teach_seq');
        $teach_status = $request->input('teach_status');
        $resignation_date = $request->input('resignation_date');

        $teach_status_str = ($teach_status == 'Y' ? '재직' : '퇴직');
        $resignation_date = ($teach_status == 'Y' ? null : $resignation_date);

        //선생님의 status 변경
        $update = \App\Teacher::where('id', $teach_seq)->first();
        // ->update(['teach_status'=>$teach_status, 'resignation_date'=>$resignation_date, 'teach_status_str'=>$teach_status_str]);
        $teach_status_str_prev = $update->teach_status_str == 'Y' ? '재직' : '퇴직';
        $update->teach_status = $teach_status;
        $update->resignation_date = $resignation_date;
        $update->teach_status_str = $teach_status_str;
        $update->save();

        $log = new LogMT();
        $req = new Request();
        $req->merge([
            'teach_seq' => $teach_seq,
            'student_seq' => 0,
            'parent_seq' => 0,
            'log_title' => 'user_list_update',
            'log_remark' => '',
            'log_subject' => '선생님 재직 상태 변경',
            'log_content' => $teach_status_str_prev.' -> '.$teach_status_str,
            'log_type' => 'user_update',
            'write_type' => 'teacher'
        ]);
        $log->insert($req);

        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    // 사용자 그룹 없는 회원 그룹 수정.
    public function userGroupUpdate(Request $request){
        $user_keys = $request->input('user_keys');
        $group_seq = $request->input('group_seq');
        $group_type = $request->input('group_type');

        $user_keys = explode(',', $user_keys);

        if($group_type == 'student'){
            $update = \App\Student::whereIn('id', $user_keys)->update(['group_seq'=>$group_seq]);
        }
        else if($group_type == 'teacher'){
            $update = \App\Teacher::whereIn('id', $user_keys)->update(['group_seq'=>$group_seq]);
        }
        else if($group_type == 'parent'){
            $update = \App\ParentTb::whereIn('id', $user_keys)->update(['group_seq'=>$group_seq]);
        }

        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    // 사용자 리스트에서 수정 후 저장
    public function userListUpdate(Request $request){
        $teach_seq = session()->get('teach_seq');
        $chg_student_is_use = $request->input('chg_student_is_use');
        $chg_student_seq = $request->input('chg_student_seq');
        $chg_student_name = $request->input('chg_student_name');
        $chg_parent_is_use = $request->input('chg_parent_is_use');
        $chg_parent_seq = $request->input('chg_parent_seq');
        $chg_parent_name = $request->input('chg_parent_name');
        $chg_teacher_is_use = $request->input('chg_teacher_is_use');
        $chg_teacher_seq = $request->input('chg_teacher_seq');
        $chg_teacher_name = $request->input('chg_teacher_name');


        //로그 데이터 생성.
        $log = new LogMT();
        $req = new Request();
        $req->merge([
            'teach_seq' => 0,
            'student_seq' => 0,
            'parent_seq' => 0,
            'log_title' => '',
            'log_remark' => '',
            'log_subject' => '',
            'log_content' => '',
            'log_type' => '',
            'write_type' => 'teacher'
        ]);

        //학생 수정
        if(strlen($chg_student_seq) > 0 && ($chg_student_name != '' || $chg_student_is_use != '')){
            $student = \App\Student::where('id', $chg_student_seq);
            if($chg_student_name != ''){
                $prev_student_name = $student->first()->student_name;
                $student = $student->update(['student_name'=>$chg_student_name]);

                $req->merge([
                    'student_seq' => $chg_student_seq,
                    'log_title' => 'user_list_update',
                    'log_subject' => '학생명 변경',
                    'log_content' => $prev_student_name.' -> '.$chg_student_name,
                    'log_type' => 'user_update'
                ]);
                $log->insert($req);
            }
            if($chg_student_is_use != ''){
                $prev_student_is_use = $student->first()->is_use;
                $student = $student->update(['is_use'=>$chg_student_is_use]);

                $req->merge([
                    'student_seq' => $chg_student_seq,
                    'log_title' => 'user_list_update',
                    'log_subject' => '학생 활성화 변경',
                    'log_content' => $prev_student_is_use.' -> '.$chg_student_is_use,
                    'log_type' => 'user_update'
                ]);
                $log->insert($req);
            }
        }

        //학부모 수정
        if(strlen($chg_parent_seq) > 0 && ( $chg_parent_name != '' || $chg_parent_is_use != '' )){
            $parent = \App\ParentTb::where('id', $chg_parent_seq);
            if($chg_parent_name != ''){
                $prev_parent_name = $parent->first()->parent_name;
                $parent = $parent->update(['parent_name'=>$chg_parent_name]);

                $req->merge([
                    'parent_seq' => $chg_parent_seq,
                    'log_title' => 'user_list_update',
                    'log_subject' => '학부모명 변경',
                    'log_content' => $prev_parent_name.' -> '.$chg_parent_name,
                    'log_type' => 'user_update'
                ]);
                $log->insert($req);
            }
            if($chg_parent_is_use != ''){
                $prev_parent_is_use = $parent->first()->is_use;
                $parent = $parent->update(['is_use'=>$chg_parent_is_use]);

                $req->merge([
                    'parent_seq' => $chg_parent_seq,
                    'log_title' => 'user_list_update',
                    'log_subject' => '학부모 활성화 변경',
                    'log_content' => $prev_parent_is_use.' -> '.$chg_parent_is_use,
                    'log_type' => 'user_update'
                ]);
                $log->insert($req);
            }
        }

        //선생님 수정
        if(strlen($chg_teacher_seq) > 0 && ($chg_teacher_name != '' || $chg_teacher_is_use != '')){
            $teacher = \App\Teacher::where('id', $chg_teacher_seq);
            if($chg_teacher_name != ''){
                $prev_teacher_name = $teacher->first()->teach_name;
                $teacher = $teacher->update(['teach_name'=>$chg_teacher_name]);

                $req->merge([
                    'teach_seq' => $chg_teacher_seq,
                    'log_title' => 'user_list_update',
                    'log_subject' => '선생님명 변경',
                    'log_content' => $prev_teacher_name.' -> '.$chg_teacher_name,
                    'log_type' => 'user_update'
                ]);
                $log->insert($req);
            }
            if($chg_teacher_is_use != ''){
                $prev_teacher_is_use = $teacher->first()->is_use;
                $teacher = $teacher->update(['is_use'=>$chg_teacher_is_use]);

                $req->merge([
                    'teach_seq' => $chg_teacher_seq,
                    'log_title' => 'user_list_update',
                    'log_subject' => '선생님 활성화 변경',
                    'log_content' => $prev_teacher_is_use.' -> '.$chg_teacher_is_use,
                    'log_type' => 'user_update'
                ]);
                $log->insert($req);
            }
        }
        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    // 사용자 이용권 및 이용권 로그 조회
    function goodsDaySelect(Request $request){
        $type = $request->input('type');
        $student_seq = $request->input('student_seq');
        $goods_detail_seq = $request->input('goods_detail_seq');

        //studnets 에서 goods_detail_seq 가져오기
        $student = \App\Student::select('students.goods_detail_seq', 'students.student_name', 'students.team_code')
                                ->where('students.id', $student_seq)
                                ->first();

        $student_name = $student->student_name;
        $team_code = $student->team_code;
        $goods_detail_seq = $goods_detail_seq ?? $student->goods_detail_seq;

        // 소속 정보 가져오기.
        $teams = \App\Team::select('teams.team_name', 'regions.region_name')
                        ->leftJoin('regions', 'regions.id', '=', 'teams.region_seq')
                        ->where('teams.team_code', $team_code)
                        ->first();
        // sql = select regions.region_name, teams.team_name from teams left join regions on regions.id = teams.region_seq where teams.team_code = 'A0000'

        $title1 = $student_name.'('.$teams->region_name.' '.$teams->team_name.')';


        //goods_details 조회
        $goods_detail = \App\GoodsDetail::select('goods_details.*')
                                            ->where('goods_details.id', $goods_detail_seq)
                                            ->where('goods_details.student_seq', $student_seq)
                                            ->first();
        $goods_name = $goods_detail->goods_name;
        $goods_period = $goods_detail->goods_period;
        $title2 = '('.$goods_name.'_'.$goods_period.')';

        //로그 조회
        $logs = \App\Log::select('logs.*', 'teachers.teach_name' )
                        ->leftJoin('teachers', 'teachers.id', '=', 'logs.created_id')
                        ->where('logs.student_seq', $student_seq)
                        ->where('logs.log_type', 'user_goods_'.$type.'_update')
                        ->where('other1_seq', $goods_detail_seq)
                        ->orderBy('logs.created_at', 'desc')
                        ->get();

        // 결과
        $result = array();
        $result['resultCode'] = 'success';
        $result['goods_detail'] = $goods_detail;
        $result['logs'] = $logs;
        $result['title1'] = $title1;
        $result['title2'] = $title2;
        return response()->json($result);
    }

    // 사용자 이용권 정지 저장
    function goodsDayStopInsert(Request $request){
        $result = array();
        $result['resultCode'] = 'fail';
        $result['cant_students'] = '';
        //트랜잭션 시작
        DB::transaction(function() use($request, &$result){

            $student_seqs = $request->input('student_seqs');
            $stop_day_cnt = $request->input('stop_day_cnt');
            $log_remark  = $request->input('log_remark');
            $stop_start_date = $request->input('stop_start_date');
            $stop_end_date = $request->input('stop_end_date');

            $student_seqs = explode(',', $student_seqs);

            //session teach_seq
            $teach_seq = session()->get('teach_seq');

            //학생의 seqs 만큼 foreach
            foreach($student_seqs as $key => $student_seq){
                //studnets 에서 goods_detail_seq 가져오기
                $student = \App\Student::select('students.goods_detail_seq', 'students.student_name')
                ->where('students.id', $student_seq)
                ->first();
                $student_name = $student->student_name;
                $goods_detail_seq = $student->goods_detail_seq;

                //로그 데이터 생성.
                $log = new LogMT();
                $req = new Request();
                $req->merge([
                    'teach_seq' => 0,
                    'student_seq' => 0,
                    'parent_seq' => 0,
                    'log_title' => '',
                    'log_remark' => '',
                    'log_subject' => '',
                    'log_content' => '',
                    'log_type' => '',
                    'write_type' => 'teacher'
                ]);

                //이용권 정지 업데이트.
                $goods_detil = \App\GoodsDetail::where('id', $goods_detail_seq)->first();
                //goods_period 가 6이면 stop_cnt는 1, 12이면 2를 넘어가면 안됨.
                $goods_period = $goods_detil->goods_period;
                $goods_stop_cnt = $goods_detil->stop_cnt ?? 0;
                $goods_stop_cnt = $goods_stop_cnt + 1;
                if($goods_period == 6 && $goods_stop_cnt > 1){
                    // $result['resultMsg'] = '6개월 이용권은 1회만 정지가 가능합니다.';
                    $result['cant_students'] .= $student_name.',';
                    continue;
                }
                else if($goods_period == 12 && $goods_stop_cnt > 2){
                    // $result['resultMsg'] = '12개월 이용권은 2회만 정지가 가능합니다.';
                    $result['cant_students'] .= $student_name.',';
                    continue;
                }

                $goods_detil->stop_cnt = ($goods_detil->stop_cnt ?? 0) + 1;
                $goods_detil->stop_day_sum = ($goods_detil->stop_day_sum ?? 0) + $stop_day_cnt;
                //end_date + stop_day_cnt = end_date
                $start_date = $goods_detil->start_date;
                $prev_end_date = $goods_detil->end_date;
                $after_end_date = date('Y-m-d', strtotime($goods_detil->end_date.' + '.$stop_day_cnt.' days'));
                $goods_detil->end_date = $after_end_date;
                $goods_detil->save();

                // 이용권 정지 로그 INSERT
                $goods_detail_logs = new \App\GoodsDetailLog;
                $goods_detail_logs->type = 'stop';
                $goods_detail_logs->goods_detail_seq = $goods_detail_seq;
                $goods_detail_logs->student_seq = $student_seq;
                $goods_detail_logs->start_date = $start_date;
                $goods_detail_logs->end_date = $after_end_date;
                $goods_detail_logs->stop_start_date = $stop_start_date;
                $goods_detail_logs->stop_end_date = $stop_end_date;
                $goods_detail_logs->day_cnt = $stop_day_cnt;
                $goods_detail_logs->remark = $log_remark;
                $goods_detail_logs->created_id = $teach_seq;
                $goods_detail_logs->created_id_type = 'teacher';
                $goods_detail_logs->save();

                // [ 추가 코드] 시간표 stop_start_date ~ stop_end_date 까지 뒤로 미루기

                //로그 저장
                $req->merge([
                    'student_seq' => $student_seq,
                    'log_title' => 'user_list_update',
                    'log_subject' => '이용권 정지',
                    'log_content' => $start_date.' ~ '.$prev_end_date.' -> '.$start_date.' ~ '.$after_end_date.' ('.$stop_day_cnt.'일 정지)',
                    'log_type' => 'user_goods_stop_update',
                    'log_remark' => $log_remark,
                    'other1_seq' => $goods_detail_seq
                ]);
                $log->insert($req);
            }
            $result['resultCode'] = 'success';
        });

        return response()->json($result);
    }

    // 사용자 이용권 상세내역 변경 로그 조회
    public function goodsDetailLogSelect(Request $request){
        $type = $request->input('type');
        $student_seq = $request->input('student_seq');
        $goods_detail_seq = $request->input('goods_detail_seq');

        $goods_detail_logs = \App\GoodsDetailLog::select('goods_detail_logs.*')
                                                    ->where('goods_detail_logs.student_seq', $student_seq)
                                                    ->where('goods_detail_logs.goods_detail_seq', $goods_detail_seq)
                                                    ->orderBy('goods_detail_logs.created_at', 'desc');

        if($type != 'all'){
            $goods_detail_logs = $goods_detail_logs->where('goods_detail_logs.type', $type);
        }

        // 결과
        $result = array();
        $result['resultCode'] = 'success';
        $result['goods_detail_logs'] = $goods_detail_logs->get();
        return response()->json($result);
    }

    // 선생님 그룹에 따른 카운트 가져오기.
    public function groupCountSelect(Request $request){
        $region_seq = $request->input('region_seq');
        $team_code = $request->input('team_code');
        $group_type2 = session()->get('group_type2');

        $group_counts = \App\Teacher::select('teachers.group_seq', 'groups.group_name', DB::raw('count(*) as cnt'))
                                    ->leftJoin('user_groups as groups', 'groups.id', '=', 'teachers.group_seq')
                                    ->groupBy('teachers.group_seq', 'groups.group_name');
        // 조건
        if(strlen($region_seq) > 0){
            $group_counts = $group_counts->where('teachers.region_seq', $region_seq);
        }
        if(strlen($team_code) > 0){
            $group_counts = $group_counts->where('teachers.team_code', $team_code);
        }
        // ㄴ 선생님 등급(그룹)에 따른 조건.
        if($group_type2 == 'leader'){
            $group_counts = $group_counts->where('groups.group_type2','<>', 'leader');
            $group_counts = $group_counts->where('groups.group_type2','<>', 'general');
        }
        else if($group_type2 == 'run'){
            $group_counts = $group_counts->where('groups.group_type2','<>', 'general');
            $group_counts = $group_counts->where('groups.group_type2','<>', 'leader');
            $group_counts = $group_counts->where('groups.group_type2','<>', 'run');
        }

        // 결과
        $group_counts = $group_counts->get();
        $result = array();
        $result['resultCode'] = 'success';
        $result['group_counts'] = $group_counts;
        return response()->json($result);
    }

    //userInsert 사용자 타입별로 등록
    public function userArrInsert(Request $request){
        $main_code =  $_COOKIE['main_code']??session()->get('main_code')??'elementary';
        $teach_seq = session()->get('teach_seq');
        $users = $request->input('users');
        if(strlen($teach_seq) < 1){
            $result = array();
            $result['resultCode'] = 'fail';
            return response()->json($result);
        }

        $cnt_already_phone_student = 0;
        $cnt_already_phone_parent = 0;
        $cnt_already_phone_teacher = 0;
        $arr_phone = array();
        // 트랜잭션 시작
        $is_transaction_suc = true;
        DB::beginTransaction();
        try {
            foreach($users as $key => $user){
                $password = sha1($user['user_pw']);
                if($user['user_type'] == 'student'){
                    //전화번호를 비교하는데 -를 제거하고 비교한다.
                    if( strlen($user['user_phone']) > 0 &&
                        \App\Student::where('student_phone', str_replace('-', '', $user['user_phone']))->count() > 0){
                        $cnt_already_phone_student++;
                        $arr_phone[] = $user['user_phone'];
                        continue;
                    }
                    $student = new \App\Student;
                    $student->team_code = $user['team_code'];
                    $student->group_seq = $user['group_seq'];
                    $student->student_id = $user['user_id'];
                    $student->student_pw = $password;
                    $student->student_name = $user['user_name'];
                    $student->area = $user['area']??'';
                    //전화번호는 무조건 -를 제거하고 저장한다.
                    $student->student_phone = str_replace('-', '', $user['user_phone']);
                    $student->is_temp_id = 'Y';
                    $student->main_code = $main_code;

                    if($user['grade_seq']??'') $student->grade = $user['grade_seq'];
                    if($user['st_class_name']??'') $student->class_name = $user['st_class_name'];
                    if($user['address']??'') $student->student_address = $user['address'];
                    if($user['school_name']??'') $student->school_name = $user['school_name'];
                    if($user['parent_phone']??'') $student->parent_phone = str_replace('-', '', $user['parent_phone']);
                    if($user['parent_id']??''){
                        $parent_seq = \App\ParentTb::where('parent_id', $user['parent_id'])->first()->id;
                        $student->parent_seq = $parent_seq;
                    }
                    $student->save();

                    // 방과후에서 학생 클래스(반) 등록 시 사용.
                    if($user['class_seq']??''){
                        $class_mate = new \App\ClassMate;
                        $class_mate->team_code = $user['team_code'];
                        $class_mate->class_seq = $user['class_seq'];
                        $class_mate->student_seq = $student->id;
                        $class_mate->start_date = $user['class_start_date'];
                        $class_mate->end_date = $user['class_end_date'];
                        $class_mate->save();
                    }
                }
                else if($user['user_type'] == 'parent'){
                    // TODO: 일단 있어도 계속 진행.
                    // if(\App\ParentTb::where('parent_phone', str_replace('-', '', $user['user_phone']))->count() > 0){
                    //     $cnt_already_phone_parent++;
                    //     $arr_phone[] = $user['user_phone'];
                    //     continue;
                    // }
                    $parent = new \App\ParentTb;
                    $parent->parent_id = $user['user_id'];
                    $parent->parent_pw = $password;
                    $parent->parent_name = $user['user_name'];
                    $parent->parent_phone = str_replace('-', '', $user['user_phone']);
                    $parent->is_temp_id = 'Y';

                    $parent->main_code = $main_code;
                    $parent->save();
                }
                else if($user['user_type'] == 'teacher'){
                    if(\App\Teacher::where('teach_phone', str_replace('-', '', $user['user_phone']))->count() > 0){
                        $cnt_already_phone_teacher++;
                        $arr_phone[] = $user['user_phone'];
                        continue;
                    }
                    $teacher = new \App\Teacher;
                    $teacher->region_seq = $user['region_seq'];
                    if($user['team_code']??'') $teacher->team_code = $user['team_code'];
                    if($user['group_seq']??'') $teacher->group_seq = $user['group_seq'];
                    $teacher->teach_id = $user['user_id'];
                    $teacher->teach_pw = $password;
                    $teacher->teach_name = $user['user_name'];
                    $teacher->teach_phone = str_replace('-', '', $user['user_phone']);
                    if($user['area']??'') $teacher->area = $user['area'];
                    if($user['is_auth']??'') $teacher->is_auth_phone = $user['is_auth'];

                    $teacher->main_code = $main_code;
                    $teacher->save();
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            $is_transaction_suc = false;
            $result['exception'] = $e->getMessage();
            DB::rollBack();
        }

        //결과
        // $result = array();
        if($is_transaction_suc) $result['resultCode'] = 'success';
        else $result['resultCode'] = 'fail';
        $result['cnt_already_phone_student'] = $cnt_already_phone_student;
        $result['cnt_already_phone_parent'] = $cnt_already_phone_parent;
        $result['cnt_already_phone_teacher'] = $cnt_already_phone_teacher;
        $result['arr_phone'] = $arr_phone;
        return response()->json($result);
    }

    // 선생님쪽에서 사용자 등록(개별)
    public function userAddList(Request $request){
        $main_code = $_COOKIE['main_code'] ?? session()->get('main_code');
        $teach_seq = session()->get('teach_seq');
        $group_type2 = session()->get('group_type2');
        $user_type = $request->input('user_type');
        $region_seq = $request->input('region_seq');
        $team_code = $request->input('team_code');

        $region_name = $region_seq ? \App\Region::where('id', $region_seq)->first()->region_name:'';
        // $team_name = $team_code ? \App\Team::where('team_code', $team_code)->first()->team_name:'';
        $team_name = \App\Team::where('team_code', $team_code)->first()->team_name;
        $team_group = \App\Region::where('main_code', $team_code)->first();

        // select * from regions where general_teach_seq = 1
        $regions = \App\Region::select('id', 'region_name')
        ->where('general_teach_seq', $teach_seq)
        ->get();

        $groups = \App\UserGroup::select('group_name', 'id', 'group_type')
        ->where('main_code', $main_code)
        ->where('group_type','<>', 'admin');

        if($group_type2 == 'general'){
            $groups = $groups->where('group_type2','<>', 'general');
        }
        else if($group_type2 == 'leader'){
            $groups = $groups->where('group_type2', '<>','general');
            $groups = $groups->where('group_type2', '<>', 'leader');
        }
        else if($group_type2 == 'run'){
            $groups = $groups->where('group_type', '<>', 'teacher');
        }
        if($user_type){
            $groups = $groups->where('group_type', $user_type);
        }

        $groups = $groups->get();

        $address_sido = \App\Addresses::select('sido')->groupBy('sido')->orderByRaw("case when sido like '%특별시%' or sido like '부산광역시' then 1 when sido like '%광역시%' then 2 else 3 end, sido desc")->get();

        $tarea_sido = '';
        if($region_seq){
            //select * from team_areas where team_code = 'A00006' limit 1
            $team_code_wh = \App\Team::select('team_code')->where('region_seq', $region_seq)->first()->team_code??'';
            $tarea_sido = \App\TeamArea::select('tarea_sido')->where('team_code', $team_code_wh)->first()->tarea_sido??'';
        }


        return view('teacher.teacher_user_add', [
            'region_seq' => $region_seq,
            'region_name' => $region_name,
            'team_code' => $team_code,
            'team_name' => $team_name,
            'team_group' => $team_group,
            'regions' => $regions,
            'groups' => $groups,
            'user_type' => $user_type,
            'group_type2' => $group_type2,
            'address_sido' => $address_sido,
            'tarea_sido' => $tarea_sido
        ]);
    }

    // 방과후 학생 개별 등록 페이지
    public function afterUserAddList(Request $request){
        $main_code = $_COOKIE['main_code'] ?? session()->get('main_code') ?? 'elementary';
        $teach_seq = $request->input('teach_seq');
        $ss_teach_seq = session()->get('teach_seq');
        if($teach_seq != $ss_teach_seq){
            $teach_seq = $ss_teach_seq;
        }

        $teacher = \App\Teacher::find($teach_seq);
        $team_code = $teacher->team_code;
        $team = \App\Team::where('team_code', $team_code)->first();

        // 학년
        $grade_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'grade')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();

        // 해당 선생님의 클래스 리스트 가져오기
        $classes = \App\ClassTb::
        where('teach_seq', $teach_seq)
        ->where('team_code', $team_code)
        ->get();

        // user_group를 가져오는데 student의 첫번째만 가져온다
        // 방과후는 그룹이 하나일것으로 판단하거나, 아예 없거나,
        $student_group = \App\UserGroup::where('main_code', $main_code)->where('group_type', 'student')->first();
        $parent_group = \App\UserGroup::where('main_code', $main_code)->where('group_type', 'parent')->first();

        return view('teacher.teacher_after_user_add',[
            'team' => $team,
            'teach_seq' => $teach_seq,
            'grade_codes' => $grade_codes,
            'classes' => $classes,
            'student_group' => $student_group,
            'parent_group' => $parent_group
        ]);
    }

    // 유저 삭제 기능.
    public function userDelete(Request $request){
        $grouptype = $request->input('grouptype');
        $user_key = $request->input('user_key');
        $team_code = $request->input('team_code');
        $login_type = session()->get('login_type');
        $is_delete_parent = $request->input('is_delete_parent');

        // 로그인 타입이 선생님, 관리자인지 확인.
        if(!($login_type == 'teacher' || $login_type == 'admin')){
            return response()->json(['resultCode'=>'fail']);
        }

        // 유저 삭제.
        if($grouptype == 'student'){
            $student = \App\Student::where('id', $user_key)->first();
            if($is_delete_parent == 'Y'){
                $parent_seq = $student->parent_seq;
                \App\ParentTb::where('id', $parent_seq)->delete();
            }
            $student->delete();
        }else if($grouptype == 'parent'){
            \App\ParentTb::where('id', $user_key)->delete();
        }else if($grouptype == 'teacher'){
           \App\Teacher::where('id', $user_key)->delete();
        }

        return response()->json(['resultCode'=>'success']);
    }

    public function idFind(){
        return view('find.user_find');
    }

    public function pwFind(){
        return view('find.pw_find');
    }

}
