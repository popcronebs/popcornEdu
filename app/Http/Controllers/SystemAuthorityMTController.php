<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SystemAuthorityMTController extends Controller
{
    //
    public function authority()
    {
        $result1 = $this->authoritylist(new Request());
        $user_groups = $result1->getData()->user_groups;

        return view('admin.admin_system_authority', compact('user_groups'));
    }

    //권한 / 그룹 가져오기.
    public function authoritylist(Request $request)
    {
        $search_type = $request->search_type;
        $search_str = $request->search_str;
        // User_group //left join creatd_id = teachers.id
        // $user_group = \App\UserGroup::select('user_groups.*');
        $user_group = \App\UserGroup::select('user_groups.*', 'ts1.teach_id as created_teach_id', 'ts2.teach_id as updated_teach_id')
            ->leftJoin('teachers as ts1', 'user_groups.created_id', '=', 'ts1.id')
            ->leftJoin('teachers as ts2', 'user_groups.updated_id', '=', 'ts2.id');


        if (strlen($search_str) > 0) {
            if ($search_type == 'group_name') {
                $user_group = $user_group->where('group_name', 'like', '%' . $search_str . '%');
            } else if ($search_type == 'created_id') {
                $user_group = $user_group->where('created_id', 'like', '%' . $search_str . '%');
            } else if ($search_type == 'is_use') {
                $user_group = $user_group->where('is_use', $search_str);
            }
        }


        // get
        $user_group = $user_group->get();
        //$user_group 가 잘 나오는지 확인


        $result = array();
        $result['user_groups'] = $user_group;
        $result['resultCode'] = 'success';
        return response()->json($result, 200);
    }

    //
    public function insert(Request $request)
    {
        $main_code = $_COOKIE['main_code'];
        $group_seq = $request->group_seq;
        $group_name = $request->group_name;
        $remark = $request->remark;
        $group_type2 = $request->group_type2;
        $group_type = $request->group_type;
        $is_use = $request->is_use;
        $first_page = $request->first_page;
        $teach_seq = session()->get('teach_seq');

        if ($group_type == 'admin' || $group_type == '')
            $main_code = '';
        //group_seq 가 없으면 insert, 있으면 update
        if ($group_seq == '') $user_group = new \App\UserGroup();
        else $user_group = \App\UserGroup::find($group_seq);
        $user_group->main_code = $main_code;
        $user_group->group_name = $group_name;
        $user_group->remark = $remark;
        $user_group->group_type2 = $group_type2;
        $user_group->group_type = $group_type;
        $user_group->is_use = $is_use;
        $user_group->first_page = $first_page;
        if ($group_seq == '') $user_group->created_id = $teach_seq;
        else $user_group->updated_id = $teach_seq;
        $user_group->save();

        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result, 200);
    }

    public function delete(Request $request)
    {
        $group_seq = $request->group_seq;
        $group_name = $request->group_name;

        $user_group = \App\UserGroup::find($group_seq);
        $user_group->delete();

        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result, 200);
    }

    public function teacher()
    {
        return view('admin.admin_system_authority_teacher');
    }

    public function teacherListCntSelect(Request $request)
    {
        $search_field = $request->input('search_field');
        $search_keyword = $request->input('search_keyword');

        $result = \App\Teacher::select(
            'teachers.*',
            'user_groups.*',
            'teachers.id as teach_seq',
            'teams.team_name'
        )
            ->leftJoin('user_groups', 'teachers.group_seq', '=', 'user_groups.id')
            ->leftJoin('teams', 'teachers.team_code', '=', 'teams.team_code');
        if($search_keyword){
            if($search_field == 'name') {
                $result = $result->where('teachers.teach_name', 'like', '%'.$search_keyword.'%');
            }else if($search_field == 'id'){
                $result = $result->where('teachers.teach_id', 'like', '%'.$search_keyword.'%');
            }else if($search_field == 'team'){
                $result = $result->where('teams.team_name', 'like', '%'.$search_keyword.'%');
            }
        }
            $result = $result->paginate(10);
        return response()->json($result, 200);
    }

    public function teacherLectureSeriesSelect(Request $request)
    {
        $lecture_codes = \App\Code::where('code_category', 'series')
            ->where('code_step', '1')
            ->get();

        foreach ($lecture_codes as $lecture_code) {
            // id가 null이 아닌 경우에만 하위 객체를 추가
            if (!is_null($lecture_code->id)) {
                $sub_objects = \App\Code::where('code_pt', $lecture_code->id)->get();
                $lecture_code->sub_objects = $sub_objects;
            }
        }

        return response()->json($lecture_codes, 200);
    }

    public function teacherLecturePermissionInsertUpdate(Request $request)
    {
        // try {
            $teacher_id = $request->teach_seq;
            // $code_seq = $request->input('code_seq');
            $lectures_permission = json_decode($request->lectures_permission, true);
            $code_seqs = $request->input('code_seqs');
            $is_one = $request->is_one;
            $is_one = $is_one == 'Y'? true : false;
            $isAllowed = $request->isAllowed;

            // TODO: 일단 team_code를 조건에 붙여야 하는데 우선은 선생에 해당하는 코드seq 모두 삭제.
            if(count($lectures_permission) > 0){

                // 트랜잭션 시작
                $is_transaction_suc = true;
                DB::beginTransaction();
                try {
                    // 삭제 1보다 크다는건 여러개이므로, 삭제처리후 다시 삽입.
                    if(!$is_one){
                        \App\TeacherLecturePermission:: where('teach_seq', $teacher_id)->delete();
                    }

                    // foreach ($lectures_permission as $key => $value) {
                    foreach ($code_seqs as $key) {

                        $codes = \App\Code::where('id', $key)->first();
                        if(!$codes || !$codes->code_pt) continue;
                        $pt_seq = $codes->code_pt;
                        // 1개일경우 1개만 삭제후 삽입.
                        if($is_one){
                            \App\TeacherLecturePermission:: where('teach_seq', $teacher_id)->where('code_seq', $pt_seq)->delete();
                            $ptrow_cnt = \App\TeacherLecturePermission:: where('teach_seq', $teacher_id)->where('code_pt', $pt_seq)->count();
                            if($isAllowed != 'true' && $ptrow_cnt == 0){
                                continue;
                            }
                        }
                        $codes2 = \App\Code::where('id', $pt_seq)->first();
                        $code_pt = $codes2->code_pt;
                        $code_step = $codes2->code_step;
                        $code_seq = $codes2->id;

                        $is_exist = \App\TeacherLecturePermission::where('teach_seq', $teacher_id)->where('code_seq', $code_seq)->first();
                        if($is_exist){ continue; }

                        $permission = new \App\TeacherLecturePermission;
                        $permission->teach_seq = $teacher_id;
                        $permission->code_seq = $code_seq;
                        $permission->code_pt = $code_pt;
                        $permission->code_step = $code_step;
                        $permission->save();
                    }
                    // 추가
                    // foreach ($lectures_permission as $key => $value) {
                    foreach ($code_seqs as $key) {
                        // 1개일경우 1개만 삭제후 삽입.
                        if($is_one){
                            \App\TeacherLecturePermission:: where('teach_seq', $teacher_id)->where('code_seq', $key)->delete();
                            if($isAllowed != 'true'){
                                continue;
                            }
                        }
                        $codes = \App\Code::where('id', $key)->first();
                        $code_pt = $codes->code_pt;
                        $code_step = $codes->code_step;

                        $permission = new \App\TeacherLecturePermission;
                        $permission->teach_seq = $teacher_id;
                        $permission->code_seq = $key;
                        $permission->code_pt = $code_pt;
                        $permission->code_step = $code_step;
                        $permission->save();
                    }
                    DB::commit();
                } catch (\Exception $e) {
                    $is_transaction_succ = false;
                    DB::rollback();
                    throw $e;
                }
            }


            // // 단일 권한 토글 업데이트
            // if (count($code_seq) === 1) {
            //     $code_id = $code_seq[0];
            //     // 기존 권한 조회
            //     $permission = \App\TeacherLecturePermission::where('teacher_id', $teacher_id)
            //         ->where('code_id', $code_id)
            //         ->first();
            //
            //     if ($permission) {
            //         // 기존 권한이 있는 경우 업데이트
            //         $current_permissions = json_decode($permission->lectures_permissions, true);
            //         foreach ($lectures_permission as $key => $value) {
            //             $current_permissions[$key] = $value;
            //         }
            //         $permission->lectures_permissions = json_encode($current_permissions);
            //     } else {
            //         // 기존 권한이 없는 경우 새로운 권한 생성
            //         $permission = new \App\TeacherLecturePermission();
            //         $permission->teacher_id = $teacher_id;
            //         $permission->code_id = $code_id;
            //         $permission->lectures_permissions = json_encode($lectures_permission);
            //     }
            //     $permission->save();
            //
            // }else{
            //     // 전체 권한 업데이트 (기존 로직)
            //     // 기존 권한 삭제
            //     \App\TeacherLecturePermission::where('teacher_id', $teacher_id)->delete();
            //
            //     foreach ($code_seq as $index => $code_id) {
            //         $permission = new \App\TeacherLecturePermission();
            //         $permission->teacher_id = $teacher_id;
            //         $permission->code_id = $code_id;
            //         $permission->lectures_permissions = json_encode($lectures_permission[$index]);
            //         $permission->save();
            //     }
            // }

            return response()->json([
                'status' => 'success',
                'message' => '권한이 저장되었습니다.'
            ]);
        // } catch (\Exception $e) {
        //     Log::error('권한 저장 오류: ' . $e->getMessage());
        //     Log::error('Request data: ' . json_encode($request->all()));
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => '권한 저장 중 오류가 발생했습니다.',
        //         'error' => $e->getMessage()
        //     ], 500);
        // }
    }

    public function teacherLecturePermissionSelect(Request $request)
    {
        $teach_seq = $request->teach_seq;
        // 시리즈 seq 가져오기.
        // select id from codes where code_category = 'series' and code_step=0
        $series_seq = \App\Code::where('code_category', 'series')->where('code_step', 0)->value('id');

        $permissions = \App\TeacherLecturePermission::leftJoin('codes', 'teacher_lectures_permissions.code_seq', '=', 'codes.id')
            ->select(
                'teacher_lectures_permissions.*',
                'codes.code_name'
            )
            ->where('teacher_lectures_permissions.teach_seq', $teach_seq)
            ->where('teacher_lectures_permissions.code_pt', $series_seq)
            ->orderBy('codes.code_name', 'asc')
            ->get();

        $permissions_details = \App\TeacherLecturePermission::leftJoin('codes', 'teacher_lectures_permissions.code_seq', '=', 'codes.id')
            ->select(
                'teacher_lectures_permissions.*',
                'codes.code_name'
            )
            ->where('teacher_lectures_permissions.teach_seq', $teach_seq)
            ->where('teacher_lectures_permissions.code_pt', '<>', $series_seq)
            ->orderBy('codes.code_name', 'asc')
            ->get()->groupBy('code_pt');


        foreach ($permissions as $permission) {
            // find()는 단일 레코드를 반환합니다
            $code = \App\Code::find($permission->code_seq);
            if ($code) {
                // 하위 코드들 조회
                $sub_codes = \App\Code::where('code_pt', $code->id)->get();
                $permission->code = $sub_codes;
            }
        }
        return response()->json([
            'teacher_lecture_permission' => $permissions,
            'series_seq' => $series_seq,
            'tlp__details' => $permissions_details
        ], 200);
    }

    public function teacherLecturePermissionDelete(Request $request)
    {
        try {
            $code_seqs = $request->code_seqs;
            $teach_seq = $request->teach_seq;
            // 단일 ID인 경우 배열로 변환
            if (!is_array($code_seqs)) {
                $code_seqs = explode(',', $code_seqs);
            }

                // 트랜잭션 시작
                DB::beginTransaction();
                try {

                    \App\TeacherLecturePermission::whereIn('code_seq', $code_seqs)
                        ->where('teach_seq', $teach_seq)
                    ->delete();


                    // 하위 권한도 같이 삭제.
                    \App\TeacherLecturePermission::whereIn('code_pt', $code_seqs)
                        ->where('teach_seq', $teach_seq)
                    ->delete();

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    throw $e;
                }

            return response()->json([
                'status' => 'success',
                'message' => '권한이 삭제되었습니다.'
            ]);
        } catch (\Exception $e) {
            \App\Log::error('권한 삭제 오류: ' . $e->getMessage());
            \App\Log::error('Request data: ' . json_encode($request->all()));

            return response()->json([
                'status' => 'error',
                'message' => '권한 삭제 중 오류가 발생했습니다.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
