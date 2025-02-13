<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class SchoolInfo extends Model
{
    // 테이블 이름을 명시적으로 지정
    protected $table = 'school_info';

    // 모든 정보를 불러오기 위해서는 추가적인 설정이 필요하지 않습니다.
    // 기본적으로 Eloquent는 모든 컬럼을 불러옵니다.
}

