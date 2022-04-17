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
use Larva\Baidu\Push\Models\BaiduPush;

/**
 * 推送 Url 给百度
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class PushJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 任务可以尝试的最大次数。
     *
     * @var int
     */
    public int $tries = 2;

    /**
     * @var BaiduPush
     */
    protected BaiduPush $baiduPush;

    /**
     * @var string
     */
    protected string $token;

    /**
     * Create a new job instance.
     *
     * @param BaiduPush $baiduPush
     */
    public function __construct(BaiduPush $baiduPush)
    {
        $this->baiduPush = $baiduPush;
        if (function_exists('settings')) {
            $this->onQueue(settings('baidu.queue', 'default'));
            $this->token = settings('baidu.site_token');
        } else {
            $this->onQueue(config('services.baidu.queue', 'default'));
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
            if ($this->baiduPush->type == BaiduPush::TYPE_SITE) {
                $response = Http::acceptJson()
                    ->withBody($this->baiduPush->url, 'text/plain')
                    ->post("http://data.zz.baidu.com/urls?site={$this->baiduPush->site}&token={$this->token}");
                if (isset($response['error'])) {
                    $this->baiduPush->setFailure($response['error'] . ':' . $response['message']);
                } else {
                    $this->baiduPush->setSuccess();
                }
            } elseif ($this->baiduPush->type == BaiduPush::TYPE_DAILY) {
                $response = Http::acceptJson()
                    ->withBody($this->baiduPush->url, 'text/plain')
                    ->post("http://data.zz.baidu.com/urls?site={$this->baiduPush->site}&token={$this->token}&type=daily");
                if (isset($response['error'])) {
                    $this->baiduPush->setFailure($response['error'] . ':' . $response['message']);
                } else {
                    $this->baiduPush->setSuccess();
                }
            }
        } catch (\Exception $e) {
            $this->baiduPush->setFailure($e->getMessage());
        }
    }
}
