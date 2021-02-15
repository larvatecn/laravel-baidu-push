<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larvacent.com/
 * @license http://www.larvacent.com/license/
 */

namespace Larva\Baidu\Push\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * 百度推送
 * @property int $id ID
 * @property string $type 推送类型
 * @property string $url 推送Url
 * @property int $status 推送状态
 * @property string $msg 返回消息
 * @property int $failures 失败次数
 * @property bool $included 是否已经收录
 * @property Carbon|null $push_at 推送时间
 *
 * @property-read boolean $failure
 * @method static \Illuminate\Database\Eloquent\Builder|BaiduPush failure()
 * @method static \Illuminate\Database\Eloquent\Builder|BaiduPush pending()
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class BaiduPush extends Model
{
    const UPDATED_AT = null;

    const TYPE_SITE = 'site';//普通推送
    const TYPE_DAILY = 'daily';//快速收录

    const STATUS_PENDING = 0b0;//待推送
    const STATUS_SUCCESS = 0b1;//正常
    const STATUS_FAILURE = 0b10;//失败

    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'baidu_push';

    /**
     * 可以批量赋值的属性
     *
     * @var array
     */
    protected $fillable = [
        'url', 'type', 'status', 'msg', 'failures', 'push_at', 'included'
    ];

    /**
     * 模型的默认属性值。
     *
     * @var array
     */
    protected $attributes = [
        'status' => 0b0
    ];

    /**
     * 为数组 / JSON 序列化准备日期。
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }

    /**
     * 查询等待的推送
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', '=', static::STATUS_PENDING);
    }

    /**
     * 查询失败的推送
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFailure($query)
    {
        return $query->where('status', '=', static::STATUS_FAILURE);
    }

    /**
     * 是否已失败
     * @return boolean
     */
    public function getFailureAttribute()
    {
        return $this->status == static::STATUS_FAILURE;
    }

    /**
     * 设置执行失败
     * @param string $msg
     * @return bool
     */
    public function setFailure(string $msg): bool
    {
        return $this->update(['status' => static::STATUS_FAILURE, 'msg' => $msg, 'failures' => $this->failures + 1, 'push_at' => $this->freshTimestamp()]);
    }

    /**
     * 设置推送成功
     * @return bool
     */
    public function setSuccess(): bool
    {
        return $this->update(['status' => static::STATUS_SUCCESS, 'msg' => 'ok', 'failures' => 0, 'push_at' => $this->freshTimestamp()]);
    }

    /**
     * 设置推送成功
     * @return bool
     */
    public function setIncluded(): bool
    {
        return $this->update(['included' => true]);
    }
}
