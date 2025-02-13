<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Session\TokenMismatchException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Throwable $exception)
    {
        // MethodNotAllowedHttpException 처리 (로그인 세션이 없을 경우)
        if ($exception instanceof MethodNotAllowedHttpException) {
            if (!Session::has('login_type')) {
                return redirect('/login');
            } else {
                return redirect('/');
            }
        }
        if ($exception instanceof TokenMismatchException) {
            if ($request->is('login/check') && $request->isMethod('POST')) {
                Session::flash('error', '세션이 만료되었습니다. 다시 시도해주세요.');
                // $request post 데이터중 is_neulbom 이 Y 인 경우 neulbom 로그인 페이지로 이동.
                if ($request->input('is_neulbom') == 'Y') {
                    return redirect('/login/neulbom');
                }
                return Redirect::to('/login');
            }
        }

        return parent::render($request, $exception);
    }
}
