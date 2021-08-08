<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */

namespace Larva\Baidu\Push\Admin\Actions;

use Dcat\Admin\Grid\RowAction;
use Illuminate\Http\Request;
use Larva\Baidu\Push\Jobs\PushJob;
use Larva\Baidu\Push\Models\BaiduPush;

/**
 * 重试
 * @author Tongle Xu <xutongle@gmail.com>
 */
class PushRetry extends RowAction
{
    /**
     * 按钮标题
     *
     * @var string
     */
    protected $title = '重试';


    /**
     * 是否显示
     * @return bool|mixed
     */
    public function allowed()
    {
        return $this->row->status == BaiduPush::STATUS_FAILURE;
    }

    /**
     * 设置确认弹窗信息，如果返回空值，则不会弹出弹窗
     *
     * 允许返回字符串或数组类型
     *
     * @return array|string|void
     */
    public function confirm()
    {
        return [
            // 确认弹窗 title
            "您确定吗？"
        ];
    }

    /**
     * 处理请求
     *
     * @param Request $request
     *
     * @return \Dcat\Admin\Actions\Response
     */
    public function handle(Request $request)
    {
        $baiduPush = BaiduPush::find($this->getKey());
        $baiduPush->update(['status' => BaiduPush::STATUS_PENDING, 'msg' => '']);
        PushJob::dispatch($baiduPush);
        // 返回响应结果并刷新页面
        return $this->response()->success("已经委派到队列！")->refresh();
    }
}