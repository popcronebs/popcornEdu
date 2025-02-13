<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckSessionExpired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // 로그인 관련 라우트는 체크하지 않음
        if ($this->isLoginRoute($request)) {
            return $next($request);
        }

        // 세션이 있었다가 만료된 경우에만 처리
        if (Session::has('user_id')) {
            \App\Sessions::where('session_id', $request->session()->getId())
                ->update(['user_id' => null]);
        }

        return $next($request);
    }

    /**
     * 로그인 관련 라우트인지 확인
     */
    private function isLoginRoute(Request $request): bool
    {
        $loginRoutes = [
            'manage/login',
            'teacher/login',
            'login',
            'logout',
            'manage/login/check',
            'teacher/login/check',
            'login/check',
            'student/main',
            'student/login',
            'student/login/check',
        ];

        $currentPath = $request->path();
        
        foreach ($loginRoutes as $route) {
            if (str_contains($currentPath, $route)) {
                return true;
            }
        }

        return false;
    }
}
