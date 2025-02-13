<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\StateService;
class StateServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // 서비스 컨테이너에 바인딩을 등록합니다.
        $this->app->singleton('state', function ($app) {
            return new StateService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // 세션에서 학생 ID 가져오기


            // 학생 정보 가져오기
            //$student = \App\Student::find();


            // 상태 관리 서비스 사용
            $stateService = app('state');
            //$stateService->setStudent($student);
            
            // 뷰 컴포저를 사용하여 모든 뷰에 상태 값을 공유합니다.
            view()->composer('*', function ($view) use ($stateService) {
            $view->with('state', $stateService);
        });
    }
}
