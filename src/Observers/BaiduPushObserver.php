<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 */

namespace Larva\Baidu\Push\Observers;

use Larva\Baidu\Push\Jobs\PushJob;
use Larva\Baidu\Push\Jobs\DeleteJob;
use Larva\Baidu\Push\Models\BaiduPush;

/**
 * 模型观察者
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class BaiduPushObserver
{
    /**
     * Handle "created" event.
     *
     * @param BaiduPush $baiduPush
     * @return void
     */
    public function created(BaiduPush $baiduPush)
    {
        PushJob::dispatch($baiduPush);
    }

    /**
     * 处理「删除」事件
     *
     * @param BaiduPush $baiduPush
     * @return void
     */
    public function deleted(BaiduPush $baiduPush)
    {
        DeleteJob::dispatch($baiduPush);
    }
}
