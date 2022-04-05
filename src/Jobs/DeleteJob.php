<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 */

namespace Larva\Baidu\Push\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Larva\Baidu\Push\Models\BaiduPush;

/**
 * 删除推送
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class DeleteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 任务可以尝试的最大次数。
     *
     * @var int
     */
    public $tries = 2;

    /**
     * @var BaiduPush
     */
    protected $baiduPush;

    /**
     * @var string
     */
    protected $site;

    /**
     * @var string
     */
    protected $token;

    /**
     * Create a new job instance.
     *
     * @param BaiduPush $baiduPush
     */
    public function __construct(BaiduPush $baiduPush)
    {
        $this->baiduPush = $baiduPush;
        if (function_exists('settings')) {
            $this->site = config('app.url');
            $this->token = settings('system.baidu_site_token');
        } else {
            $this->site = config('services.baidu.site');
            $this->token = config('services.baidu.site_token');
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Http::acceptJson()
                ->withBody($this->baiduPush->url, 'text/plain')
                ->post("http://data.zz.baidu.com/del?site={$this->site}&token={$this->token}");
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
