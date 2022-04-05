<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 */

namespace Larva\Baidu\Push\Models;

use Illuminate\Database\Eloquent\Builder;
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
 * @property-read boolean $failure 是否失败
 * @method static Builder failure()
 * @method static Builder pending()
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class BaiduPush extends Model
{
    public const UPDATED_AT = null;

    public const TYPE_SITE = 'site';//普通推送
    public const TYPE_DAILY = 'daily';//快速收录
    public const TYPES = [
        self::TYPE_SITE => '普通收录',
        self::TYPE_DAILY => '快速收录'
    ];

    public const STATUS_PENDING = 0b0;//待推送
    public const STATUS_SUCCESS = 0b1;//正常
    public const STATUS_FAILURE = 0b10;//失败
    public const STATUS_MAPS = [
        self::STATUS_PENDING => '待推送',
        self::STATUS_SUCCESS => '推送成功',
        self::STATUS_FAILURE => '推送失败'
    ];

    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'baidu_pushes';

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
        'status' => self::STATUS_PENDING
    ];

    /**
     * 为数组 / JSON 序列化准备日期。
     *
     * @param \DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }

    /**
     * 查询等待的推送
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', '=', static::STATUS_PENDING);
    }

    /**
     * 查询失败的推送
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeFailure(Builder $query): Builder
    {
        return $query->where('status', '=', static::STATUS_FAILURE);
    }

    /**
     * 是否已失败
     * @return bool
     */
    public function getFailureAttribute(): bool
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
     * 设置为已收录
     * @return bool
     */
    public function setIncluded(): bool
    {
        return $this->update(['included' => true]);
    }
}
