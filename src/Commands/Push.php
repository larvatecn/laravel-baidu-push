<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larvacent.com/
 * @license http://www.larvacent.com/license/
 */

namespace Larva\Baidu\Push\Commands;

use Illuminate\Console\Command;
use Larva\Baidu\Push\Jobs\PushJob;
use Larva\Baidu\Push\Models\BaiduPush;

/**
 * 推送
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Push extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'baidu:push';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Baidu push';

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
        $count = BaiduPush::pending()->count();
        $bar = $this->output->createProgressBar($count);
        BaiduPush::pending()->orderBy('push_at', 'asc')->chunk(100, function ($results) use ($bar) {
            /** @var BaiduPush $push */
            foreach ($results as $push) {
                PushJob::dispatch($push);
                $bar->advance();
            }
        });
        $bar->finish();
    }
}
