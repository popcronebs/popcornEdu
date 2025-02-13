<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\SendMsgMTController as sendMsg;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // 메일로 인증번호 보내기
    public function sendMailNumber(Request $request){
       $user_email = $request->input('user_email');
       $user_seq = $request->input('user_seq');
       $user_type = $request->input('user_type');
       $user_name = $request->input('user_name');

        //  authConfirm 테이블 저장.
        // "id","phone","email","user_seq","user_type","created_at","updated_at","ip", "auth"
        // user_seq user_type email 가 같은 데이터 가있으면 update 진행
        // 없으면 insert 진행
        $authConfirm = \App\authConfirm::where('user_seq', $user_seq)
        ->where('user_type', $user_type)
        ->where('email', $user_email)->first();

        if(!$authConfirm){
            $authConfirm = new \App\authConfirm;
            $authConfirm->user_seq = $user_seq;
            $authConfirm->user_type = $user_type;
            $authConfirm->email = $user_email;
        }

        $authConfirm->auth = rand(1000, 9999);
        $authConfirm->ip = $_SERVER['REMOTE_ADDR'];
        $authConfirm->save();

        // 추가 되었는지 확인
        $auth_id = $authConfirm->id;
        if($auth_id){
            // 이메일 보내기.
            $user = (object) [
                'name' => $user_name,
                'email' => $user_email,
                'auth' => $authConfirm->auth
            ];
            Mail::send('email.mail_auth', ['user' => $user], function ($m) use ($user) {
                $m->to($user->email, $user->name)->subject('인증번호입니다.');
            });

            return response()->json(['resultCode' => 'success']);
        }else{
            return response()->json(['resultCode' => 'fail']);
        }
    }

    // 인증번호 확인
    public function checkMailNumber(Request $request){
        $user_seq = $request->input('user_seq');
        $user_type = $request->input('user_type');
        $user_email = $request->input('user_email');
        $user_auth  = $request->input('user_auth');
        $is_join = $request->input('is_join');

        $authConfirm = \App\authConfirm::where('user_seq', $user_seq)
        ->where('user_type', $user_type)
        ->where('email', $user_email)
        ->where('auth', $user_auth)->first();

        if($authConfirm){
            // created_at, updated_at 모두 현재시간과 비교해서 하나라도 3분 안이면 성공 학생 내용 업데이트.
            $now = date('Y-m-d H:i:s');
            $created_at = $authConfirm->created_at;
            $updated_at = $authConfirm->updated_at;
            $diff_created = strtotime($now) - strtotime($created_at);
            $diff_updated = strtotime($now) - strtotime($updated_at);

            if($diff_created < 180 || $diff_updated < 180){
                $authConfirm->delete();
            }else{
                return response()->json(['resultCode' => 'timeover']);
            }

            // 가입전 인증번호 확인일경우 성공으로 리턴.
            if($is_join == 'Y'){
                return response()->json(['resultCode' => 'success']);
            }

            // 가입후 인증번호 확인일경우 성공으로 리턴.
            // 학생일 경우.
            if($user_type == 'student'){
                $student = \App\Student::find($user_seq);
                $student->is_auth_email = 'Y';
                $student->save();
            }
            // 학부모일 경우.
            if($user_type == 'parent'){
                $parent = \App\ParentTb::find($user_seq);
                $parent->is_auth_email = 'Y';
                $parent->save();
            }
            // 선생님일 경우.
            if($user_type == 'teacher'){
                $teacher = \App\Teacher::find($user_seq);
                $teacher->is_auth_email = 'Y';
                $teacher->save();
            }

            return response()->json(['resultCode' => 'success']);
        }else{
            return response()->json(['resultCode' => 'fail']);
        }

    }

    // 핸드폰 인증번호 보내기
    public function sendPhoneAuthNumber(Request $request){
        $user_phone = $request->input('user_phone');
        $user_phone = str_replace('-', '', $user_phone);
        $user_seq = $request->input('user_seq');
        $user_type = $request->input('user_type');
        $user_name = $request->input('user_name');
        $is_find_idpw = $request->input('is_find_idpw');
        $is_join = $request->input('is_join');
        $user_id = $request->input('user_id');

        if($is_find_idpw == 'Y'){
            if(!$user_id){
                $user_student = \App\Student::where('student_phone', $user_phone)->where('student_name', $user_name)->first();
                $user_parent = \App\ParentTb::where('parent_phone', $user_phone)->where('parent_name', $user_name)->first();
                $user_teacher = \App\Teacher::where('teach_phone', $user_phone)->where('teach_name', $user_name)->first();
            }else{
                $user_student = \App\Student::where('student_phone', $user_phone)->where('student_id', $user_id)->first();
                $user_parent = \App\ParentTb::where('parent_phone', $user_phone)->where('parent_id', $user_id)->first();
                $user_teacher = \App\Teacher::where('teach_phone', $user_phone)->where('teach_id', $user_id)->first();
            }
            if($user_student){
                $user_seq = $user_student->id;
                $user_type = 'student';
            } else if($user_parent){
                $user_seq = $user_parent->id;
                $user_type = 'parent';
            } else if($user_teacher){
                $user_seq = $user_teacher->id;
                $user_type = 'teacher';
            }
            if(!$user_seq){
                return response()->json(['resultCode' => 'fail']);
            }
        }
        //  authConfirm 테이블 저장.
        // "id","phone","email","user_seq","user_type","created_at","updated_at","ip", "auth"
        // user_seq user_type email 가 같은 데이터 가있으면 update 진행
        // 없으면 insert 진행
        $authConfirm = \App\authConfirm::
        where(function($query) use ($user_seq, $user_phone){
            $query->where('user_seq', $user_seq)
            ->where('phone', $user_phone);
        })
        ->where('user_type', $user_type)
        ->where('phone', $user_phone)->first();

        // 혹시라도 같은 유저 타입에 전화번호가 있으면 리턴
        $is_already = false;
        if($user_type == 'student'){
            $student = \App\Student::where('student_phone', $user_phone)->where('id','<>',$user_seq)->first();
            if($student){ $is_already = true; }
        }
        if($user_type == 'parent'){
            $parent = \App\ParentTb::where('parent_phone', $user_phone)->where('id','<>',$user_seq)->first();
            if($parent){ $is_already = true; }
        }
        if($user_type == 'teacher'){
            $teacher = \App\Teacher::where('teach_phone', $user_phone)->where('id', '<>', $user_seq)->first();
            if($teacher){ $is_already = true; }
        }

        // TODO: is_already 를 무조건 false처리. 중복이라더라고 넘어가도록. 추후 중복 체크하지 않으면, 이부분은 삭제.
        $is_already = false;

        // 아이디, 비밀번호 찾기가 아닐때만.
        if($is_already && $is_find_idpw != 'Y'){
            return response()->json(['resultCode' => 'already_phone']);
        }

        if(!$authConfirm){
            $authConfirm = new \App\authConfirm;
            if($user_seq) $authConfirm->user_seq = $user_seq;
            $authConfirm->user_type = $user_type;
            $authConfirm->phone = $user_phone;
        }else{
            // created_at, updated_at 모두 현재시간과 비교해서 하나라도 3분 안이면 리턴. 이미 보낸거라고 판단.
            $now = date('Y-m-d H:i:s');
            $created_at = $authConfirm->created_at;
            $updated_at = $authConfirm->updated_at;
            $diff_created = strtotime($now) - strtotime($created_at);
            $diff_updated = strtotime($now) - strtotime($updated_at);

            if($diff_created < 180 || $diff_updated < 180){
                return response()->json(['resultCode' => 'already']);
            }
        }

        $authConfirm->auth = rand(1000, 9999);
        $authConfirm->ip = $_SERVER['REMOTE_ADDR'];
        $authConfirm->save();

        // 추가 되었는지 확인
        $auth_id = $authConfirm->id;

        if($auth_id){
            // 전송 할 멤버 정보
            $members = array();
            $members[0]['member_name'] = $user_name;
            $members[0]['phone'] = $user_phone;
            $members[0]['send_type'] = 'other';

            // Rrequest 객체를 생성하여 전달.
            $req = new Request();
            $req->merge([
                'mform_title' => '인증번호입니다.',
                'mform_content' => '인증번호는 '.$authConfirm->auth.' 입니다.',
                'select_member' => $members,
                'sms_type' => 'sms',
                'send_length' => '1',
                'rev_date' => '',
                'img_data' => ''
            ]);

            // 문자 보내기
            $msg = new sendMsg();
            $msg_result = $msg->sms($req)->getData(true);
            //$msg_result['trn'] = "{\"resultCode\":\"success\"}"

            // 결과
            $result = array();
            $rtn = $msg_result['rtn'];

            // $rtn = json_decode($rtn, JSON_UNESCAPED_UNICODE);
            $result['resultCode'] = $rtn['resultCode'];
            $result['resultMsg'] = $rtn['resultMsg'];
            if($is_find_idpw == 'Y'){
                $result['userType'] = $user_type;
                $result['userSeq'] = $user_seq;
            }
            // $result['rtn'] = $rtn;
            // $result['data'] = $msg_result['data'];

            return response()->json($result);
        }
    }

    // 핸드폰 인증번호 확인
    public function checkPhoneAuthNumber(Request $request){
        $user_seq = $request->input('user_seq');
        $user_type = $request->input('user_type');
        $user_phone = $request->input('user_phone');
        $user_auth  = $request->input('user_auth');
        $is_find_idpw = $request->input('is_find_idpw');
        $user_id = $request->input('user_id');
        $is_join = $request->input('is_join');

        // user_seq가 없으면서, 아이디, 비밀번호 찾기 일경우.
        if($is_find_idpw == 'Y' && !$user_seq){
            if(!$user_id){
                $user_name = $request->input('user_name');
                $user_student = \App\Student::where('student_phone', $user_phone)->where('student_name', $user_name)->first();
                $user_parent = \App\ParentTb::where('parent_phone', $user_phone)->where('parent_name', $user_name)->first();
                $user_teacher = \App\Teacher::where('teach_phone', $user_phone)->where('teach_name', $user_name)->first();
            }else{
                $user_student = \App\Student::where('student_phone', $user_phone)->where('student_id', $user_id)->first();
                $user_parent = \App\ParentTb::where('parent_phone', $user_phone)->where('parent_id', $user_id)->first();
                $user_teacher = \App\Teacher::where('teach_phone', $user_phone)->where('teach_id', $user_id)->first();
            }
            if($user_student){
                $user_seq = $user_student->id;
                $user_type = 'student';
            } else if($user_parent){
                $user_seq = $user_parent->id;
                $user_type = 'parent';
            } else if($user_teacher){
                $user_seq = $user_teacher->id;
                $user_type = 'teacher';
            }
            if(!$user_seq){
                return response()->json(['resultCode' => 'fail']);
            }
        }
        // 선생님 타입이면서, 인증번호 chk pass를 원할 경우.
        $login_type = session()->get('login_type');
        $is_chk_pass = $request->input('is_chk_pass');

        $authConfirm = \App\authConfirm::where('user_seq', $user_seq)
        ->where('user_type', $user_type)
        ->where('phone', $user_phone)
        ->where('auth', $user_auth)->first();

        // 위에 인증번호 조건이 있으거나,
        // 선생님(or Admin) 타입이면서, 인증번호 chk pass를 원할 경우.
        if($authConfirm || ($is_chk_pass == 'Y' && ($login_type == 'teacher' || $login_type == 'admin'))){
            // created_at, updated_at 모두 현재시간과 비교해서 하나라도 3분 안이면 성공 학생 내용 업데이트.
            $now = date('Y-m-d H:i:s');
            if(($is_chk_pass??'') != 'Y'){
                $created_at = $authConfirm->created_at;
                $updated_at = $authConfirm->updated_at;
                $diff_created = strtotime($now) - strtotime($created_at);
                $diff_updated = strtotime($now) - strtotime($updated_at);
                if($diff_created < 180 || $diff_updated < 180){
                    $authConfirm->delete();
                }else{
                    return response()->json(['resultCode' => 'timeover']);
                }
            }

            // 가입전 인증번호 확인일경우 성공으로 리턴.
            if($is_join == 'Y'){
                return response()->json(['resultCode' => 'success']);
            }

            // 가입후 인증번호 확인일경우 성공으로 리턴.
            $user_id = '';
            // 학생일 경우.
            if($user_type == 'student'){
                $student = \App\Student::find($user_seq);
                $student->is_auth_phone = 'Y';
                $student->save();
                $user_id = $student->student_id;
            }
            else if($user_type == 'parent'){
                $parent = \App\ParentTb::find($user_seq);
                $parent->is_auth_phone = 'Y';
                $parent->save();
                $user_id = $parent->parent_id;
            }
            else if($user_type == 'teacher'){
                $teacher = \App\Teacher::find($user_seq);
                $teacher->is_auth_phone = 'Y';
                $teacher->save();
                $user_id = $teacher->teach_id;
            }

            return response()->json(['resultCode' => 'success', 'userId' => $user_id, 'userSeq' => $user_seq, 'userType' => $user_type]);
        }else{
            return response()->json(['resultCode' => 'fail']);
        }
    }
    // 회원가입시 핸드폰 인증번호 확인
    public function checkPhoneAuthNumberForRegister(Request $request){
        $user_seq = $request->input('user_seq');
        $user_type = $request->input('user_type');
        $user_phone = $request->input('user_phone');
        $user_auth  = $request->input('user_auth');

        $authConfirm = \App\authConfirm::where('user_seq', $user_seq)
        ->where('user_type', $user_type)
        ->where('phone', $user_phone)
        ->where('auth', $user_auth)->first();

        if($authConfirm){
            return response()->json(['resultCode' => 'success']);
        }else{
            return response()->json(['resultCode' => 'fail']);
        }

    }

        // 선생님 아이디 체크.
    public function checkTeachId(Request $request){
        $teach_id = $request->input('teach_id');
        $teacher = \App\Teacher::where('teach_id', $teach_id)->first();
        if($teacher){
            return response()->json(['resultCode' => 'fail']);
        }else{
            return response()->json(['resultCode' => 'success']);
        }
    }


    // 비밀번호 변경
    public function changePassword(Request $request){
        $password = $request->input('password');
        $password_check = $request->input('password_check');
        $user_seq = $request->input('user_seq');
        $user_id = $request->input('user_id');
        $user_type = $request->input('user_type');

        // 백단에서 한번더 체크.
        if($password != $password_check){
            return response()->json(['resultCode' => 'password_not_match']);
        }

        $password_hash = DB::select('SELECT SHA1(?) as hash', [$password])[0]->hash;
        if($user_type == 'student'){
            $student = \App\Student::where('id', $user_seq)->where('student_id', $user_id)->first();
            $student->student_pw = $password_hash;
            $student->save();
        }
        else if($user_type == 'teacher'){
            $teacher = \App\Teacher::where('id', $user_seq)->where('teach_id', $user_id)->first();
            $teacher->teach_pw = $password_hash;
            $teacher->save();
        }
        else if($user_type == 'parent'){
            $parent = \App\ParentTb::where('id', $user_seq)->where('parent_id', $user_id)->first();
            $parent->parent_pw = $password_hash;
            $parent->save();
        }else{
            return response()->json(['resultCode' => 'fail']);
        }

        return response()->json(['resultCode' => 'success']);
   }
}
