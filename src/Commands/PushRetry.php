<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */

namespace Larva\Baidu\Push\Commands;

use Illuminate\Console\Command;
use Larva\Baidu\Push\Jobs\PushJob;
use Larva\Baidu\Push\Models\BaiduPush;

/**
 * PushRetry
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class PushRetry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:baidu-retry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Baidu push retry.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = BaiduPush::failure()->count();
        $bar = $this->output->createProgressBar($count);
        BaiduPush::failure()->orderBy('push_at', 'asc')->chunk(100, function ($results) use ($bar) {
            /** @var BaiduPush $push */
            foreach ($results as $push) {
                PushJob::dispatch($push);
                $bar->advance();
            }
        });
        $bar->finish();
    }
}
