<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

class StateService
{
    protected $student;

    /**
     * 세션에 상태 값 저장
     */
    public function setSessionState($key, $value)
    {
        Session::put($key, $value);
    }

    /**
     * 세션에서 상태 값 가져오기
     */
    public function getSessionState($key)
    {
        return Session::get($key);
    }

    /**
     * 세션에서 상태 값 삭제
     */
    public function forgetSessionState($key)
    {
        Session::forget($key);
    }

    /**
     * 캐시에 상태 값 저장
     */
    public function setCacheState($key, $value, $minutes)
    {
        Cache::put($key, $value, $minutes);
    }

    /**
     * 캐시에서 상태 값 가져오기
     */
    public function getCacheState($key)
    {
        return Cache::get($key);
    }

    /**
     * 캐시에서 상태 값 삭제
     */
    public function forgetCacheState($key)
    {
        Cache::forget($key);
    }

    /**
     * 학생 정보 설정
     */
    public function setStudent($student)
    {
        $this->student = $student;
    }

    /**
     * 학생 정보 가져오기
     */
    public function getStudent()
    {
      $student_seq = session()->get('student_seq');
      $student_type = session()->get('login_type');
        if ($student_type == 'student') {
          $student = \App\Student::find($student_seq)
          ->select('students.*')
          ->addSelect('grade.code_name as grade_name')
          ->addSelect('teachers.teach_name as teach_name')
          ->addSelect('teams.team_name as team_name')
          ->addSelect('regions.region_name as region_name')
          ->leftJoin('codes as grade', 'grade.id', '=', 'students.grade')
          ->leftJoin('teachers', 'teachers.id', '=', 'students.teach_seq')
          ->leftJoin('teams', 'teams.team_code', '=', 'students.team_code')
          ->leftJoin('regions', 'regions.id', '=', 'teams.region_seq')
          ->where('students.id', $student_seq)
          ->first();
          unset($student->student_pw);
          return $student;
        }
        return $this->student;
    }

}