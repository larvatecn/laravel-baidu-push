# laravel-baidu-ping

百度自动推送

[![Packagist](https://img.shields.io/packagist/l/larva/laravel-baidu-push.svg?maxAge=2592000)](https://packagist.org/packages/larva/laravel-baidu-push)
[![Total Downloads](https://img.shields.io/packagist/dt/larva/laravel-baidu-push.svg?style=flat-square)](https://packagist.org/packages/larva/laravel-baidu-push)

## Installation

```bash
composer require larva/laravel-baidu-push -vv
```

## Config

```php
//add services.php
    'baidu'=>[
        //百度站长平台
        'queue' => '',//异步处理的队列名称
        'site_token' => '',//网站Token
    ]
```

## 使用

```php
\Larva\Baidu\Push\BaiduPush::push('https://www.aa.com/sss.html');
```