<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Str::endsWith($request->url(), '/manage/login') ||
            Str::endsWith($request->url(), '/manage') ||
            Str::endsWith($request->url(), '/teacher/login') ||
            Str::endsWith($request->url(), '/teacher') ||
            Str::endsWith($request->url(), '/student/login') ||
            Str::endsWith($request->url(), '/parent/login') ||
            Str::endsWith($request->url(), '/parent/register') ||
            Str::endsWith($request->url(), '/parent/register/username/check') ||
            Str::endsWith($request->url(), '/parent/register/school/list') ||
            Str::endsWith($request->url(), '/parent/register/insert') ||
            Str::endsWith($request->url(), '/phone/auth/send/number') ||
            Str::endsWith($request->url(), '/phone/auth/check/number/register') ||
            Str::endsWith($request->url(), '/parent/register/someMethod') ||
            Str::endsWith($request->url(), '/popcron/pd/math') ||
            Str::endsWith($request->url(), '/popcron/pd/hanja') ||
            Str::endsWith($request->url(), '/popcron/pd/english') ||
            Str::endsWith($request->url(), '/popcron/pd') ||
            Str::endsWith($request->url(), '/login' ) ||
            Str::endsWith($request->url(), '/user/id/find' ) ||
            Str::endsWith($request->url(), '/user/pw/find' ) ||
            Str::endsWith($request->url(), '/register/neulbom' ) ||
            Str::endsWith($request->url(), '/login/neulbom' )) {
            return $next($request);
        }
        //통합 로그인이 생겼으므로, 통합 로그인 페이지로 이동.
        //login_type의 쿠키값에 따라 로그인페이지가 달라짐
        if(!Session::has('login_type')){
            return redirect('/login');
            // if(!empty($_COOKIE['login_type'])){
            //     if($_COOKIE['login_type'] == 'admin'){
            //         return redirect('/manage/login');
            //     }else if($_COOKIE['login_type'] == 'teacher'){
            //         return redirect('/teacher/login');
            //     }else if($_COOKIE['login_type'] == 'student'){
            //         return redirect('/student/login');
            //     }else if($_COOKIE['login_type'] == 'parent'){
            //         return redirect('/parent/login');
            //     }
            //     else{
            //         return redirect('/teacher/login');
            //     }
            // }else{
            //     // /manage/가 url에 있으면 관리자 로그인페이지로 이동
            //     if (Str::contains($request->url(), '/manage')) {
            //         return redirect('/manage/login');
            //     }else if (Str::contains($request->url(), '/teacher')) {
            //         return redirect('/teacher/login');
            //     }else if(Str::contains($request->url(), '/student')){
            //         return redirect('/student/login');
            //     }else if(Str::contains($request->url(), '/parent')){
            //         return redirect('/parent/login');
            //     }
            //     else{
            //         return redirect('/teacher/login');
            //     }
            // }
        }
        return $next($request);
    }
}
