<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 */

namespace Larva\Baidu\Push;

use Illuminate\Database\Eloquent\Model;

/**
 * 使用百度推送
 * @mixin Model
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
            BaiduPush::push($model->url);
        });
        static::updated(function ($model) {
            BaiduPush::update($model->url);
        });
        static::deleted(function ($model) {
            BaiduPush::delete($model->url);
        });
    }
}
