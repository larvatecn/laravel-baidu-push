<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 */

namespace Larva\Baidu\Push\Admin\Controllers;

use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Grid;
use Larva\Baidu\Push\Admin\Actions\BatchRetry;
use Larva\Baidu\Push\Admin\Actions\PushRetry;
use Larva\Baidu\Push\Models\BaiduPush;

/**
 * 百度推送
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class PushController extends AdminController
{
    protected function title()
    {
        return '百度推送';
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new BaiduPush(), function (Grid $grid) {
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
                $filter->equal('type', '推送类型')->select(BaiduPush::TYPES);
                $filter->equal('status', '推送状态')->select(BaiduPush::STATUS_MAPS);
                //顶部筛选
                $filter->scope('failure', '推送失败')->where('status', BaiduPush::STATUS_FAILURE);
                $filter->scope('pending', '待推送')->where('status', BaiduPush::STATUS_PENDING);
            });
            $grid->quickSearch(['id']);
            $grid->model()->orderBy('id', 'desc');

            $grid->column('id', 'ID')->sortable();
            $grid->column('type', '推送类型')->using(BaiduPush::TYPES);
            $grid->column('url', 'Url')->link();
            $grid->column('status', '状态')->using(BaiduPush::STATUS_MAPS)->dot([
                BaiduPush::STATUS_PENDING => 'info',
                BaiduPush::STATUS_SUCCESS => 'success',
                BaiduPush::STATUS_FAILURE => 'warning',
            ], 'info');
            $grid->column('msg', '');
            $grid->column('failures', '失败次数');
            $grid->column('included', '是否收录')->bool();
            $grid->column('pending', '重试')->action(PushRetry::make());
            $grid->column('push_at', '推送时间');
            $grid->column('created_at', '创建时间')->sortable();

            $grid->disableCreateButton();
            $grid->disableViewButton();
            $grid->disableEditButton();
            $grid->paginate(12);

            $grid->batchActions([
                new BatchRetry('重试'),
            ]);
        });
    }
}
