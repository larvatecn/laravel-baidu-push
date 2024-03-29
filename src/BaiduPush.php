<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 */

namespace Larva\Baidu\Push;

use Larva\Baidu\Push\Jobs\UpdateJob;

/**
 * 百度推送快捷方法
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class BaiduPush
{
    /**
     * 推送 Url 给百度
     * @param string $url
     * @return Models\BaiduPush
     */
    public static function push(string $url)
    {
        return Models\BaiduPush::firstOrCreate(['url' => $url, 'type' => Models\BaiduPush::TYPE_SITE]);
    }

    /**
     * 推送 Url 给百度
     * @param string $url
     * @return Models\BaiduPush
     */
    public static function dailyPush(string $url)
    {
        return Models\BaiduPush::firstOrCreate(['url' => $url, 'type' => Models\BaiduPush::TYPE_DAILY]);
    }

    /**
     * 推送 Url 给百度
     * @param string $url
     */
    public static function update(string $url): void
    {
        if (($ping = Models\BaiduPush::query()->where('url', '=', $url)->first()) != null) {
            $ping->update(['status' => Models\BaiduPush::STATUS_PENDING]);
            UpdateJob::dispatch($ping);
        } else {
            static::push($url);
        }
    }

    /**
     * 推送 Url 给百度
     * @param string $url
     * @throws \Exception
     */
    public static function delete(string $url): void
    {
        if (($ping = Models\BaiduPush::query()->where('url', '=', $url)->first()) != null) {
            $ping->delete();
        }
    }
}
