<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
$is_debug = true;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
Route::get('/', function () {
  return view('layout.login');
});

// 통합 로그인화면
Route::get('/login', 'LoginController@login');
// ㄴ 통합 로그인 체크
Route::post('/login/check', 'LoginController@loginCheck');

// 늘본 회원가입
Route::get('/register/neulbom', 'LoginController@registerNeulbom');
Route::post('/register/neulbom', 'LoginController@insertNeulbom');
// 늘본 로그인
Route::get('/login/neulbom', 'LoginController@loginNeulbom');
// 인증
// ㄴ 메일로 인증 번호 보내기.
Route::post('/mail/auth/send/number', 'AuthController@sendMailNumber');
// ㄴ 메일 인증 번호 확인.
Route::post('/mail/auth/check/number', 'AuthController@checkMailNumber');
// ㄴ 휴대폰 인증 번호 보내기
Route::post('/phone/auth/send/number', 'AuthController@sendPhoneAuthNumber');
// ㄴ 휴대폰 인증 번호 확인
Route::post('/phone/auth/check/number', 'AuthController@checkPhoneAuthNumber');
// ㄴ 선생님 ID 체크
Route::post('/id/auth/teach/check', 'AuthController@checkTeachId');
// ㄴ 회원가입시 핸드폰 인증번호 확인
Route::post('/phone/auth/check/number/register', 'AuthController@checkPhoneAuthNumberForRegister');
// ㄴ 통합 비밀번호 변경.
Route::post('/change/password', 'AuthController@changePassword');

Route::get('/logout', 'LoginController@logout')->name('logout');
