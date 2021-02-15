<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larvacent.com/
 * @license http://www.larvacent.com/license/
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
            if ($this->baiduPush->type == BaiduPush::TYPE_SITE) {
                $response = Http::contentType('text/plain')->post("http://data.zz.baidu.com/urls?site={$this->site}&token={$this->token}", [
                    'body' => $this->baiduPush->url
                ]);
                if (isset($response['error'])) {
                    $this->baiduPush->setFailure($response['error'] . ':' . $response['message']);
                } else {
                    $this->baiduPush->setSuccess();
                }
            } else if ($this->baiduPush->type == BaiduPush::TYPE_DAILY) {
                $response = Http::contentType('text/plain')->post("http://data.zz.baidu.com/urls?site={$this->site}&token={$this->token}&type=daily", [
                    'body' => $this->baiduPush->url
                ]);
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
