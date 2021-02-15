<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larvacent.com/
 * @license http://www.larvacent.com/license/
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
     * @return \Larva\Baidu\Push\Models\BaiduPush
     */
    public static function push(string $url)
    {
        return \Larva\Baidu\Push\Models\BaiduPush::firstOrCreate(['url' => $url, 'type' => \Larva\Baidu\Push\Models\BaiduPush::TYPE_SITE]);
    }

    /**
     * 推送 Url 给百度
     * @param string $url
     * @return \Larva\Baidu\Push\Models\BaiduPush
     */
    public static function dailyPush(string $url)
    {
        return \Larva\Baidu\Push\Models\BaiduPush::firstOrCreate(['url' => $url, 'type' => \Larva\Baidu\Push\Models\BaiduPush::TYPE_DAILY]);
    }

    /**
     * 推送 Url 给百度
     * @param string $url
     */
    public static function update(string $url)
    {
        if (($ping = \Larva\Baidu\Push\Models\BaiduPush::query()->where('url', '=', $url)->first()) != null) {
            $ping->update(['status' => \Larva\Baidu\Push\Models\BaiduPush::STATUS_PENDING]);
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
    public static function delete(string $url)
    {
        if (($ping = \Larva\Baidu\Push\Models\BaiduPush::query()->where('url', '=', $url)->first()) != null) {
            $ping->delete();
        }
    }
}
