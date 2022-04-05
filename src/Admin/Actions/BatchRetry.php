<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */

namespace Larva\Baidu\Push\Admin\Actions;

use Dcat\Admin\Grid\BatchAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Larva\Baidu\Push\Jobs\PushJob;
use Larva\Baidu\Push\Models\BaiduPush;

/**
 * 批量重试
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class BatchRetry extends BatchAction
{
    /**
     * 确认弹窗信息
     * @return string|void
     */
    public function confirm()
    {
        return '您确定要重试已选中吗？';
    }

    // 处理请求
    public function handle(Request $request)
    {
        // 获取选中的文章ID数组
        $keys = $this->getKey();
        Cache::forget('BingPush:ErrorCode');
        foreach (BaiduPush::find($keys) as $item) {
            $item->update(['status' => BaiduPush::STATUS_PENDING, 'msg' => '']);
            PushJob::dispatch($item);
        }
        return $this->response()->success('委派队列成功！')->refresh();
    }
}