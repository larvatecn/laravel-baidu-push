<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 */

namespace Larva\Baidu\Push;

use Illuminate\Support\ServiceProvider;

/**
 * Class BaiduPushServiceProvider
 * @author Tongle Xu <xutongle@gmail.com>
 */
class BaiduPushServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands('command.baidu.push');
            $this->commands('command.baidu.push.retry');
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }

        \Larva\Baidu\Push\Models\BaiduPush::observe(\Larva\Baidu\Push\Observers\BaiduPushObserver::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommand();
    }

    /**
     * Register the MNS queue command.
     * @return void
     */
    private function registerCommand()
    {
        $this->app->singleton('command.baidu.push', function () {
            return new \Larva\Baidu\Push\Commands\Push();
        });

        $this->app->singleton('command.baidu.push.retry', function () {
            return new \Larva\Baidu\Push\Commands\PushRetry();
        });
    }
}
