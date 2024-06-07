<?php

namespace TaskEngine\SDK;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class TaskEngineServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function boot()
    {
        // 发布配置
        $this->publishes([
            __DIR__ . '/config/taskengine.php' => config_path('taskengine.php'),
        ]);
    }

    // 注册服务
    public function register()
    {
        $this->app->singleton(TaskEngineService::class, function ($app) {
            return new TaskEngineService($app);
        });
    }

    // 延迟加载, 在首次需要的时候才加载
    public function provides()
    {
        return ['taskengine'];
    }
}
