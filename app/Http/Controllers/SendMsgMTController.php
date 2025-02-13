<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SendMsgMTController extends Controller
{
    // 문자 전송 기능.
    public function sms(Request $request){
        $mform_title = $request->input('mform_title');
        $mform_content = $request->input('mform_content');
        $img_data = $request->input('img_data');
        $select_member = $request->input('select_member');
        $rev_date = $request->input('rev_date');

        $img_url = '';
        $img_path = '';

        //$img_data가 200글자 이상이면
        if(strlen($img_data) > 200){
            $sip = $this->saveImgPath($img_data, $rev_date);
            $img_url = $sip['img_url'];
            $img_path = $sip['img_path'];
        }

        $post_memger = [];
        // 받는 곳에서 이렇게 받아야함.
        // $members[$i]['member_name'];
        // $members[$i]['phone'];
        foreach($select_member as $member){

                if($member['send_type'] == 'student'){
                    $post['phone'] = $member['student_phone'];
                    $post['member_name'] = $member['student_name'];
                    array_push($post_memger, $post);
                }
                else if($member['send_type'] == 'parent'){
                    $post['phone'] = $member['parent_phone'];
                    $post['member_name'] = $member['parent_name'];
                    array_push($post_memger, $post);
                }
                else if($member['send_type'] == 'all'){
                    $post['phone'] = $member['student_phone'];
                    $post['member_name'] = $member['student_name'];
                    array_push($post_memger, $post);
                    $post1['phone'] = $member['parent_phone'];
                    $post1['member_name'] = $member['parent_name'];
                    array_push($post_memger, $post1);
                }
                else{
                    $post['phone'] = $member['phone'];
                    $post['member_name'] = $member['member_name'];
                    array_push($post_memger, $post);
                }
        }

        // $select_member 의 첫번재 배열의 phone을 가져옴
        $data = array();
        $data['store_code'] = 'sdang_ebs';
        $data['send_id'] = session()->get('teach_seq');
        $data['rev_date'] = $rev_date;
        $data['content'] = $mform_content;
        $data['title']  = $mform_title;
        $data['img_url'] = $img_url;
        $data['members'] = $post_memger;
        $data['post_type'] = 'sms_send';
        $data['team_code'] = session()->get('team_code');

        $result = array();
        $rtn = $this->ajaxPost('sms', $data);
        $rtn1 = $rtn."";
        $rtn = $this->getRtn($rtn);
        $rtn2 = $rtn."";
        $rtn1 = json_decode($rtn1, JSON_UNESCAPED_UNICODE);
        $rtn2 = json_decode($rtn2, JSON_UNESCAPED_UNICODE);
        $rtn3 = json_decode($rtn, JSON_UNESCAPED_UNICODE);
        $rtn = $rtn1 ?? $rtn2 ?? $rtn3;

        $rtnCode = $rtn->resultCode ?? $rtn['resultCode'] ?? '';
        $sql = $rtn->sql ?? $trn['sql'] ?? '';
        $resultMsg = $rtn->resultMsg ?? $rtn['resultMsg'] ?? '';

        // $result = array();
        $result['resultCode'] = $rtnCode;
        $result['post_member'] = $post_memger;
        $result['sql'] = $sql;
        $result['resultMsg'] = $resultMsg;
        $result['rtn'] = $rtn;
        $result['data'] = $data;

        // 이미지 날짜가 1달이 지났거나 예약이 지난 이미지 삭제 처리
        $this->deleteImg($img_url);

        return response()->json($result);
    }

    // 문자 최근 발송 내역
    public function smsLastSelect(Request $request){
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $sms_type = $request->input('sms_type');
        $search_str = $request->input('search_str');
        $send_id = session()->get('teach_seq');
        $team_code = session()->get('team_code');

        $login_type = session()->get('login_type');
        $group_type2 = session()->get('group_type2');

        $page = $request->input('page');
        $page = $page ?? 1;
        $page_max = $request->input('page_max');
        $page_max = $page_max ?? 10;
        // $sql->paginate($page_max, ['*'], 'page', $page);


        // 문자 서버 전송.
        $data = array();
        $data['post_type'] = 'sms_last_select';
        $data['store_code'] = 'sdang_ebs';
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['sms_type'] = $sms_type;
        $data['search_str'] = $search_str ?? '';
        $data['team_code'] = $team_code;
        // 만약 선생님이면서, 관리자 및 팀장 총괄이 아니면,
        // 자기가 보낸 것 만 볼수 있게 처리.
        if($login_type == 'teacher' && $group_type2 == 'run'){
            $data['send_id'] = $send_id;
        }


        //결과
        $rtn = $this->ajaxPost('sms', $data);
        $rtn1 = $rtn."";
        $rtn = $this->getRtn($rtn);
        $rtn2 = $rtn."";
        $rtn1 = json_decode($rtn1, JSON_UNESCAPED_UNICODE);
        $rtn2 = json_decode($rtn2, JSON_UNESCAPED_UNICODE);
        $rtn3 = json_decode($rtn, JSON_UNESCAPED_UNICODE);
        $rtn = $rtn1 ?? $rtn2 ?? $rtn3;

        $rtnCode = $rtn->resultCode ?? $rtn['resultCode'] ?? '';
        $sql = $rtn->sql ?? $trn['sql'] ?? '';
        $resultMsg = $rtn->resultMsg ?? $rtn['resultMsg'] ?? '';
        $resultData = $rtn->resultData ?? $rtn['resultData'] ?? '';

        //재정립
        // $result = array();
        // $rtn

        $result['resultCode'] = $rtnCode;
        // $result['sql'] = $sql; // 결과값확인 후 주석처리
        $result['resultMsg'] = $resultMsg;
        $result['rtn'] = $rtn;

        $resultData = array_filter($resultData, function($message) {
            return strpos($message['title'], '인증번호') === false;
        });

        $result['messages'] = $resultData;

        $resultData = collect($resultData);
        $items = $resultData->slice(($page - 1) * $page_max, $page_max)->values(); // 현재 페이지의 항목들
        $resultData = new \Illuminate\Pagination\LengthAwarePaginator($items, $resultData->count(), $page_max, $page, [
            'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
        ]);

        $result['messages_info'] = $resultData;


        return response()->json($result);
    }

    // 문자 예약 발송 내역
    public function smsReservSelect(Request $request){
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $page = $request->input('page');
        $page = $page ?? 1;
        $page_max = $request->input('page_max');
        $page_max = $page_max ?? 10;
        // $sql->paginate($page_max, ['*'], 'page', $page);

        // 문자 서버 전송.
        $data = array();
        $data['post_type'] = 'sms_reserv_select';
        $data['store_code'] = 'sdang_ebs';
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        //결과
        $rtn = $this->ajaxPost('sms', $data);
        $rtn1 = $rtn."";
        $rtn = $this->getRtn($rtn);
        $rtn2 = $rtn."";
        $rtn1 = json_decode($rtn1, JSON_UNESCAPED_UNICODE);
        $rtn2 = json_decode($rtn2, JSON_UNESCAPED_UNICODE);
        $rtn3 = json_decode($rtn, JSON_UNESCAPED_UNICODE);
        $rtn = $rtn1 ?? $rtn2 ?? $rtn3;

        $rtnCode = $rtn->resultCode ?? $rtn['resultCode'] ?? '';
        $sql = $rtn->sql ?? $trn['sql'] ?? '';
        $resultMsg = $rtn->resultMsg ?? $rtn['resultMsg'] ?? '';
        $resultData = $rtn->resultData ?? $rtn['resultData'] ?? '';

        //order by rev_date
        if(count($resultData) > 1){
            //$resultData 배열 안에 rev_date 로 정렬
            usort($resultData, function($a, $b){
                return $a->rev_date < $b->rev_date ? -1 : 1;
            });
        }


        //재정립
        // $result = array();
        $result['resultCode'] = $rtnCode;
        // $result['sql'] = $sql; // 결과값확인 후 주석처리
        $result['resultMsg'] = $resultMsg;
        $result['reservs'] = $resultData;

        //배열을 페이징 처리
        $resultData = collect($resultData);
        $items = $resultData->slice(($page - 1) * $page_max, $page_max)->values(); // 현재 페이지의 항목들
        $resultData = new \Illuminate\Pagination\LengthAwarePaginator($items, $resultData->count(), $page_max, $page, [
            'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
        ]);
        $result['reserv_info'] = $resultData;

        return response()->json($result);
    }

    // 문자 예약 취소
    public function smsReservCancel(Request $request){
        $alarm_seq = $request->input('alarm_seq');
        $sms_type = $request->input('sms_type');

        // 문자 서버 전송.
        $data = array();
        $data['post_type'] = 'sms_delete';
        $data['store_code'] = 'sdang_ebs';
        $data['alarm_seq'] = $alarm_seq;
        $data['sms_type'] = $sms_type;

        //결과
        $rtn = $this->ajaxPost('sms', $data);
        $rtn1 = $rtn."";
        $rtn = $this->getRtn($rtn);
        $rtn2 = $rtn."";
        $rtn1 = json_decode($rtn1, JSON_UNESCAPED_UNICODE);
        $rtn2 = json_decode($rtn2, JSON_UNESCAPED_UNICODE);
        $rtn3 = json_decode($rtn, JSON_UNESCAPED_UNICODE);
        $rtn = $rtn1 ?? $rtn2 ?? $rtn3;

        $rtnCode = $rtn->resultCode ?? $rtn['resultCode'] ?? '';
        $sql = $rtn->sql ?? $trn['sql'] ?? '';
        $resultMsg = $rtn->resultMsg ?? $rtn['resultMsg'] ?? '';

        //재정립
        $result = array();
        $result['resultCode'] = $rtnCode;
        $result['sql'] = $sql; // 결과값확인 후 주석처리
        $result['resultMsg'] = $resultMsg;
        // $result['data'] = $data;

        return response()->json($result);

    }

    // 문자 예약 수정
    public function smsReservUpdate(Request $request){
        $alarm_seq = $request->input('alarm_seq');
        $type = $request->input('type');
        $title = $request->input('title');
        $content = $request->input('content');
        $rev_date = $request->input('rev_date');
        $img_data = $request->input('img_data');

        $img_url = '';
        $img_path = '';

        //이미지에 http 가 있으면 $img_url = $img_data
        if(strpos($img_data, 'http') !== false){
            $img_url = $img_data;
        }
        //$img_data가 200글자 이상이면
        else if(strlen($img_data) > 200){

                $sip = $this->saveImgPath($img_data, $rev_date);
                $img_url = $sip['img_url'];
                $img_path = $sip['img_path'];
        }

        // 문자 서버 전송.
        $data = array();
        $data['post_type'] = 'sms_rev_update';
        $data['store_code'] = 'sdang_ebs';
        $data['alarm_seq'] = $alarm_seq;
        $data['sms_type'] = $type;
        $data['title'] = $title;
        $data['content'] = $content;
        $data['img_data'] = $img_url; // data -> url

        // 결과
        $rtn = $this->ajaxPost('sms', $data);
        $rtn = $this->getRtn($rtn);
        $rtn = json_decode($rtn);
        $rtnCode = $rtn->resultCode ?? '';
        $sql = $rtn->sql ?? '';
        $resultMsg = $rtn->resultMsg ?? '';

        // 재정립
        $result = array();
        $result['resultCode'] = $rtnCode;
        $result['sql'] = $sql; // 결과값확인 후 주석처리
        $result['resultMsg'] = $resultMsg;
        // $result['data'] = $data;

        // 이미지 날짜가 1달이 지났거나 예약이 지난 이미지 삭제 처리
        $this->deleteImg($img_url);
        return response()->json($result);
    }

    // 문자 전체 발송 통계
    public function smsStatistics(Request $request){
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $status_code = $request->input('status_code');

        // 문자 서버 전송.
        $data = array();
        $data['post_type'] = 'sms_statistics';
        $data['store_code'] = 'sdang_ebs';
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['sms_status'] = $status_code;

        // 결과
        $rtn = $this->ajaxPost('sms', $data);
        // $result = array();
        $rtn = $this->getRtn($rtn);
        // $result['rtn'] = $rtn;
        $rtn = json_decode($rtn);
        $rtnCode = $rtn->resultCode ?? '';
        $sql = $rtn->sql ?? '';
        $resultMsg = $rtn->resultMsg ?? '';
        $resultData = $rtn->resultData ?? '';

        //$resultData가 배열이면 order by send_date
        if(is_array($resultData) && count($resultData) > 1){
            usort($resultData, function($a, $b){
                return $a->send_date < $b->send_date ? 1 : -1;
            });
        }

        // 재정립
        $result = array();
        $result['resultCode'] = $rtnCode;
        $result['sql'] = $sql; // 결과값확인 후 주석처리
        $result['resultMsg'] = $resultMsg;
        $result['statistics'] = $resultData;
        $result['data'] = $data;

        return response()->json($result);

    }

    // 문자 발송 상세 확인
    public function smsReportDetail(Request $request){
        $alarm_seq = $request->input('alarm_seq');
        $sms_type = $request->input('sms_type');

        // 문자 서버 전송.
        $data = array();
        $data['post_type'] = 'sms_report_detail';
        $data['store_code'] = 'sdang_ebs';
        $data['alarm_seq'] = $alarm_seq;
        $data['sms_type'] = $sms_type;

        // 결과
        $rtn = $this->ajaxPost('sms', $data);
        $rtn1 = $rtn."";
        $rtn = $this->getRtn($rtn);
        $rtn2 = $rtn."";
        $rtn1 = json_decode($rtn1, JSON_UNESCAPED_UNICODE);
        $rtn2 = json_decode($rtn2, JSON_UNESCAPED_UNICODE);
        $rtn3 = json_decode($rtn, JSON_UNESCAPED_UNICODE);
        $rtn = $rtn1 ?? $rtn2 ?? $rtn3;

        $rtnCode = $rtn->resultCode ?? $rtn['resultCode'] ?? '';
        $sql = $rtn->sql ?? $trn['sql'] ?? '';
        $resultMsg = $rtn->resultMsg ?? $rtn['resultMsg'] ?? '';
        $resultData = $rtn->resultData ?? $rtn['resultData'] ?? '';


        // 재정립
        // $result = array();
        $result['resultCode'] = $rtnCode;
        $result['sql'] = $sql; // 결과값확인 후 주석처리
        $result['resultMsg'] = $resultMsg;
        $result['reports'] = $resultData;
        $result['data'] = $data;

        return response()->json($result);
    }


    //
    public function kakao(Request $request){
        $mform_title = $request->input('mform_title');
        $mform_content = $request->input('mform_content');
        $img_data = $request->input('img_data');
        $select_member = $request->input('select_member');
        $kko_code = $request->input('kko_code');
        $url_str = $request->input('url_str');
        $rev_date = $request->input('rev_date');

        $post_memger = [];
        // 받는 곳에서 이렇게 받아야함.
        // $members[$i]['member_name'];
        // $members[$i]['phone'];
        foreach($select_member as $member){

                if($member['send_type'] == 'student'){
                    $post['phone'] = $member['student_phone'];
                    $post['member_name'] = $member['student_name'];
                    $post['student_name'] = $member['student_name'];
                    array_push($post_memger, $post);
                }
                else if($member['send_type'] == 'parent'){
                    $post['phone'] = $member['parent_phone'];
                    $post['member_name'] = $member['parent_name'];
                    $post['student_name'] = $member['student_name'];
                    array_push($post_memger, $post);
                }
                else if($member['send_type'] == 'all'){
                    $post['phone'] = $member['student_phone'];
                    $post['member_name'] = $member['student_name'];
                    $post['student_name'] = $member['student_name'];
                    array_push($post_memger, $post);
                    $post1['phone'] = $member['parent_phone'];
                    $post1['member_name'] = $member['parent_name'];
                    $post1['student_name'] = $member['student_name'];
                    array_push($post_memger, $post1);
                }
                else{
                    $post['phone'] = $member['phone'];
                    $post['member_name'] = $member['member_name'];
                    array_push($post_memger, $post);
                }
        }


        // $select_member 의 첫번재 배열의 phone을 가져옴
        $data = array();
        $data['store_code'] = 'sdang_ebs';
        $data['send_id'] = session()->get('teach_seq');
        $data['team_code'] = session()->get('team_code');
        $data['send_datetime'] = $rev_date;
        $data['content'] = $mform_content;
        $data['title']  = $mform_title;
        $data['members'] = $post_memger;
        $data['btn_info'] = $url_str;
        $data['tmpl_cd'] = $kko_code;
        $data['api_key'] = 'e51618848b8148b75c583ea594eb8ebf8c5b8ec3';
        if($url_str != ''){
            $data['post_type'] = 'spend_kkomsg_btn';
            $data['btn_info'] = $url_str;
            $data['btn_type'] = 'W';
        }
        else{
            $data['post_type'] = 'spend_kkomsg';
        }


        $result = array();
        $rtn = $this->ajaxPost('kakao', $data);
        $rtn1 = $rtn."";
        $rtn = $this->getRtn($rtn);
        $rtn2 = $rtn."";
        $rtn1 = json_decode($rtn1, JSON_UNESCAPED_UNICODE);
        $rtn2 = json_decode($rtn2, JSON_UNESCAPED_UNICODE);
        $rtn3 = json_decode($rtn, JSON_UNESCAPED_UNICODE);
        $rtn = $rtn1 ?? $rtn2 ?? $rtn3;

        $rtnCode = $rtn->resultCode ?? $rtn['resultCode'] ?? '';
        $sql = $rtn->sql ?? $trn['sql'] ?? '';
        $resultMsg = $rtn->resultMsg ?? $rtn['resultMsg'] ?? '';

        // $result = array();
        $result['resultCode'] = $rtnCode;
        $result['post_member'] = $post_memger;
        $result['sql'] = $sql;
        $result['resultMsg'] = $resultMsg;
        $result['rtn'] = $rtn;
        $result['data'] = $data;

        return response()->json($result);
    }

    // 알림톡 최근 발송내역
    public function kakaoLastSelect(Request $request){
    // $search_number = chgPostStr('search_number');
    // $search_content = chgPostStr('search_content');
    // $search_type = chgPostStr('send_type'); // 예약
    // $search_status = chgPostStr('search_status'); // 1 = 성공, 2 = 실패, '' = 모두
    //
        $search_str = $request->input('search_str');
        $request->merge([
            'search_number' => '',
            'search_content' => '',
            'send_type' => '',
            'search_status' => '',
            'search_name' => $search_str,
        ]);
        return $this->kakaoSelect($request);
    }
    // 알림톡 발송내역
    public function kakaoSelect(Request $request){
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $search_number = $request->input('search_number');
        $search_content = $request->input('search_content');
        $send_type = $request->input('send_type'); // 예약
        $search_status = $request->input('search_status'); // 1 = 성공, 2 = 실패, '' = 모두
        $search_name = $request->input('search_name');

        $page = $request->input('page');
        $page = $page ?? 1;
        $page_max = $request->input('page_max');
        $page_max = $page_max ?? 10;
        // $sql->paginate($page_max, ['*'], 'page', $page);


        // 문자 서버 전송.
        $data = array();
        $data['post_type'] = 'search_msg';
        $data['store_code'] = 'sdang_ebs';
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['search_number'] = $search_number;
        $data['search_content'] = $search_content;
        $data['send_type'] = $send_type;
        $data['search_status'] = $search_status;
        $data['search_name'] = $search_name;
        $data['api_key'] = 'e51618848b8148b75c583ea594eb8ebf8c5b8ec3';
        $data['team_code'] = session()->get('team_code');
        // TODO: 선생님 seq(id) 나중에 조건에 필요할수 있으므로 메모.

        //결과
        $rtn = $this->ajaxPost('kakao', $data);
        $rtn1 = $rtn."";
        $rtn = $this->getRtn($rtn);
        $rtn2 = $rtn."";
        $rtn1 = json_decode($rtn1, JSON_UNESCAPED_UNICODE);
        $rtn2 = json_decode($rtn2, JSON_UNESCAPED_UNICODE);
        $rtn3 = json_decode($rtn, JSON_UNESCAPED_UNICODE);
        $rtn = $rtn1 ?? $rtn2 ?? $rtn3;

        $rtnCode = $rtn->resultCode ?? $rtn['resultCode'] ?? '';
        $sql = $rtn->sql ?? $trn['sql'] ?? '';
        $resultMsg = $rtn->resultMsg ?? $rtn['resultMsg'] ?? '';
        $resultData = $rtn->resultData ?? $rtn['resultData'] ?? '';

        //재정립
        // $result = array();
        // $rtn

        $result['resultCode'] = $rtnCode;
        // $result['sql'] = $sql; // 결과값확인 후 주석처리
        $result['resultMsg'] = $resultMsg;

        // $resultData = array_filter($resultData, function($message) {
        //     return strpos($message['title'], '인증번호') === false;
        // });

        $result['rtn'] = $rtn;

        $resultData = collect($resultData);
        $items = $resultData->slice(($page - 1) * $page_max, $page_max)->values(); // 현재 페이지의 항목들
        $resultData = new \Illuminate\Pagination\LengthAwarePaginator($items, $resultData->count(), $page_max, $page, [
            'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
        ]);

        if($send_type == '예약'){
            $result['reserv_info'] = $resultData;
            $result['reservs'] = $resultData;
        }
        else{
            $result['messages_info'] = $resultData;
            $result['messages'] = $resultData;
        }


        return response()->json($result);
    }

    // 알림톡 예약 발송 내역
    public function kakaoReservSelect(Request $request){
    // $search_number = chgPostStr('search_number');
    // $search_content = chgPostStr('search_content');
    // $search_type = chgPostStr('send_type'); // 예약
    // $search_status = chgPostStr('search_status'); // 1 = 성공, 2 = 실패, '' = 모두
    //
        $search_str = $request->input('search_str');
        $request->merge([
            'search_number' => '',
            'search_content' => '',
            'send_type' => '예약',
            'search_status' => '',
            'search_name' => $search_str,
        ]);
        return $this->kakaoSelect($request);
    }

    //
    public function kakaoReservCancel(Request $request){
        $alarm_seq = $request->input('alarm_seq');
        $send_type = '예약';

        // 문자 서버 전송.
        $data = array();
        $data['post_type'] = 'delete_msg';
        $data['store_code'] = 'sdang_ebs';
        $data['idx'] = $alarm_seq;
        $data['send_type'] = $send_type;
        $data['api_key'] = 'e51618848b8148b75c583ea594eb8ebf8c5b8ec3';
        $data['team_code'] = session()->get('team_code');

        //결과
        $rtn = $this->ajaxPost('kakao', $data);
        $rtn1 = $rtn."";
        $rtn = $this->getRtn($rtn);
        $rtn2 = $rtn."";
        $rtn1 = json_decode($rtn1, JSON_UNESCAPED_UNICODE);
        $rtn2 = json_decode($rtn2, JSON_UNESCAPED_UNICODE);
        $rtn3 = json_decode($rtn, JSON_UNESCAPED_UNICODE);
        $rtn = $rtn1 ?? $rtn2 ?? $rtn3;

        $rtnCode = $rtn->resultCode ?? $rtn['resultCode'] ?? '';
        $sql = $rtn->sql ?? $trn['sql'] ?? '';
        $resultMsg = $rtn->resultMsg ?? $rtn['resultMsg'] ?? '';

        //재정립
        $result = array();
        $result['resultCode'] = $rtnCode;
        $result['sql'] = $sql; // 결과값확인 후 주석처리
        $result['resultMsg'] = $resultMsg;
        $result['rtn'] = $rtn;
        // $result['data'] = $data;

        return response()->json($result);

    }

    //
    public function push(){

    }

    //
    private function ajaxPost($type, $data){
        $protocol = '';
        $port = 80;
        if($type == 'sms'){
            $protocol = 'http://ts.edu-coding.co.kr/api/querySms.php';
        }else if($type == 'push'){
            $protocol = "http://ts.edu-coding.co.kr/api/queryPush.php";
        }else if($type == 'kakao'){
            $protocol = "http://ts.edu-coding.co.kr/api/queryKakao.php";
        }

        $host = $protocol;

        $data = http_build_query($data);

        // parse the given URL
        $url = parse_url($host);

        $host = $url['host'];
        $path = $url['path'];
        $res = '';


        // open a socket connection on port 80 - timeout: 300 sec
        if ($fp = fsockopen($host, $port, $errno, $errstr, 300)) {
            $reqBody = $data;
            $reqHeader = "POST $path HTTP/1.1\r\n" . "Host: $host\r\n";
            $reqHeader .= "Content-type: application/x-www-form-urlencoded\r\n"
            . "Content-length: " . strlen($reqBody) . "\r\n"
            . "Connection: close\r\n\r\n";

            /* send request */
            fwrite($fp, $reqHeader);
            fwrite($fp, $reqBody);

            while(!feof($fp)) {
                $res .= fgets($fp, 1024);
            }

            fclose($fp);
        } else {
            return "Error:Cannot Connect!";
        }

        $result = explode("\r\n\r\n", $res, 2);
        // $result = explode("\n\n", $res, 2);
        $header = isset($result[0]) ? $result[0] : '';
        $content = isset($result[1]) ? $result[1] : '';
        return $content;
    }

    //중간에 불필요한 문자열 제거
    private function getRtn($rtn){
        if(strpos($rtn, ');') !== false){
            $rtn = substr($rtn, strpos($rtn, '{'));
            $rtn = substr($rtn, 0, strpos($rtn, '});') + 1);
        }else{
            //\r\n 가 있으면 분기로 나눔
            if(strpos($rtn, "\r\n") !== false){
                $rtn = explode("\r\n", $rtn);
                $rtn = $rtn[1];
            }else
                $rtn = substr($rtn, 1, -2);
        }
        return $rtn;
    }

    // 이미지 저장 함수
    private function saveImgPath($img_data, $rev_date){
        if(strlen($img_data) > 200){
            //라라벨 코드로 이미지 저장 base64를 이미지로 변환후 storage/app/public/uploads/sms_img 저장.
            $img_data = base64_decode($img_data);
            //예약$rev_date 이 비었을경우 뒤에 현재 +1 시간 _yyyyMMddHHmmss 채움 / 예약 $rev_date 이 있을경우 $rev_date+1시간 넣음

            //앞에 랜덤 값을 4자리를 한번더 넣어서 중복이 안되게 함.
            $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
            $random_string = implode('', array_rand(array_flip($characters), 4));
            if($rev_date == ''){
                $img_name = $random_string.time().'_'.date('YmdHis', strtotime('+1 hours')).'.jpg';
            }else{
                $img_name = $random_string.time().'_'.date('YmdHis', strtotime($rev_date.'+1 hours')).'.jpg';
            }
            $img_path = storage_path('app/public/uploads/sms_img/'.$img_name);
            file_put_contents($img_path, $img_data);
            //권한 777 설정
            chmod($img_path, 0777);
            //[추가 코드] https인지 http인지 확인.
            $img_url = 'https://'.$_SERVER['HTTP_HOST'].'/storage/uploads/sms_img/'.$img_name;
        }
        $result = array();
        $result['img_url'] = $img_url ?? '';
        $result['img_path'] = $img_path ?? '';
        return $result;
    }

    // 이미지 삭제 함수
    private function deleteImg($img_url){
        //폴더 경로중 이미지 이름이 $random_string/time()_yyyyMMddHHmmss.jpg 인데
        //여기서 yyyyMMddHHmmss만 잘라서 현재 시간보다 작은경우 삭제
        if($img_url != ''){
            $img_path = storage_path('app/public/uploads/sms_img/');
            $files = scandir($img_path);
            foreach($files as $file){
                if($file == '.' || $file == '..') continue;
                $file_time = substr($file, strpos($file, '_')+1, 14);
                if($file_time < date('YmdHis')){
                    unlink($img_path.$file);
                }
            }
        }
    }
}
