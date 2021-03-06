<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 * @license http://www.larva.com.cn/license/
 */

namespace Larva\Baidu\Push;

/**
 * 使用百度推送
 * @property \Illuminate\Database\Eloquent\Model $this
 * @author Tongle Xu <xutongle@gmail.com>
 */
trait HasBaiduPush
{
    /**
     * Boot the trait.
     *
     * Listen for the deleting event of a model, then remove the relation between it and tags
     */
    protected static function bootHasBaiduPush(): void
    {
        static::created(function ($model) {
            BaiduPush::push($model->link);
        });
        static::updated(function ($model) {
            BaiduPush::update($model->link);
        });
        static::deleted(function ($model) {
            BaiduPush::delete($model->link);
        });
    }
}
