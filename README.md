# laravel-baidu-ping

百度自动推送

<p align="center">
    <a href="https://packagist.org/packages/larva/laravel-baidu-push"><img src="https://poser.pugx.org/larva/laravel-baidu-push/v/stable" alt="Stable Version"></a>
    <a href="https://packagist.org/packages/larva/laravel-baidu-push"><img src="https://poser.pugx.org/larva/laravel-baidu-push/downloads" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/larva/laravel-baidu-push"><img src="https://poser.pugx.org/larva/laravel-baidu-push/license" alt="License"></a>
</p>


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