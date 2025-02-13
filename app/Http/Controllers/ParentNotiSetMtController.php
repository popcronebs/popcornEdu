<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParentNotiSetMtController extends Controller
{
    // 학부모 알림 설정
    public function list(){
        $parent_seq = session()->get('parent_seq');
        $alarm_settings = \App\AlarmSetting::where('user_type', 'parent')->where('user_seq', $parent_seq)->get();

        return view('parent.parent_noti_set', [
            'alarm_settings' => $alarm_settings
        ]);
    }

    // 알림 설정 저장.
    public function alarmInsert(Request $request){
        $parent_seq = session()->get('parent_seq');

        $alarm_value = $request->input('alarm_value');
        $alarm_name = $request->input('alarm_name');
        $alarm_type = $request->input('alarm_type');
        $alarm_group = $request->input('alarm_group');

        $alarm_setting = \App\AlarmSetting::updateOrCreate(
            ['user_type' => 'parent', 'user_seq' => $parent_seq, 'alarm_type' => $alarm_type] ,
            [
                'user_type' => 'parent',
                'user_seq' => $parent_seq,
                'alarm_name' => $alarm_name,
                'alarm_type' => $alarm_type,
                'alarm_value' => $alarm_value,
                'alarm_group' => $alarm_group,
            ]
        );

        // 결과
        $result['resultCode'] = 'success';
        return response()->json($result, 200);
    }

}
