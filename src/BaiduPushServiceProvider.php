<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 */

namespace Larva\Baidu\Push;

use Illuminate\Support\ServiceProvider;
use Larva\Baidu\Push\Commands\Push;
use Larva\Baidu\Push\Commands\PushRetry;

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
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerCommand();
    }

    /**
     * Register the MNS queue command.
     * @return void
     */
    private function registerCommand(): void
    {
        $this->app->singleton('command.baidu.push', function () {
            return new Push();
        });

        $this->app->singleton('command.baidu.push.retry', function () {
            return new PushRetry();
        });
    }
}
