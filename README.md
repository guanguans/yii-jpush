<h1 align="center">yii-jpush</h1>

<p align="center">适配于 Yii 的极光推送扩展包</p>

<p align="center"><img src="./docs/usage.png"></p>

[![Build Status](https://travis-ci.org/guanguans/yii-jpush.svg?branch=master)](https://travis-ci.org/guanguans/yii-jpush)
[![Build Status](https://scrutinizer-ci.com/g/guanguans/yii-jpush/badges/build.png?b=master)](https://scrutinizer-ci.com/g/guanguans/yii-jpush/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/guanguans/yii-jpush/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/guanguans/yii-jpush/?branch=master)
[![codecov](https://codecov.io/gh/guanguans/yii-jpush/branch/master/graph/badge.svg)](https://codecov.io/gh/guanguans/yii-jpush)
[![StyleCI](https://github.styleci.io/repos/258963285/shield?branch=master)](https://github.styleci.io/repos/258963285)
[![Latest Stable Version](https://poser.pugx.org/guanguans/yii-jpush/v/stable)](https://packagist.org/packages/guanguans/yii-jpush)
[![Total Downloads](https://poser.pugx.org/guanguans/yii-jpush/downloads)](https://packagist.org/packages/guanguans/yii-jpush)
[![License](https://poser.pugx.org/guanguans/yii-jpush/license)](https://packagist.org/packages/guanguans/yii-jpush)

## 环境要求

* yii >= 2

## 安装

``` shell
$ composer require guanguans/yii-jpush -v
```

## 配置

Yii2 配置文件 `config/main.php` 的 components 中添加:

``` php
'components' => [
    // ...
    'jpush' => [
        'class' => 'Guanguans\YiiJpush\Jpush',
        'appKey' => 'xxxxxxxxxxx',
        'masterSecret' => 'xxxxxxxxxxx',
        'logFile' => './jpush.log', // 可选
        'retryTimes' => 3, // 可选
        'zone' => 'default', // 可选 [default, bj]
    ],
    // ...
]
```

## 使用，更多详细文档请参考 [jpush/jpush-api-php-client](https://github.com/jpush/jpush-api-php-client)

### 获取 `JPush\Client` 实例

``` php
<php
Yii::$app->jpush->client
```

### 简单使用

``` php
<?php
Yii::$app->jpush->client->push()
    ->setPlatform('all')
    ->addAllAudience()
    ->setNotificationAlert('Hello, JPush')
    ->send();
```

``` php
/**
 * @param  string  $content  推送内容
 * @param  array  $ids  推送的id
 * @param  string  $info  业务内容
 * @param  string  $title  推送标题 定向的简单推送 不填
 */
Yii::$app->jpush->client->push()
    ->setPlatform(['ios', 'android'])
    ->addRegistrationId($ids)
    ->iosNotification([
        "title" => $title,
        "body"  => $content
    ], [
        'sound'             => 'sound.caf',
        'badge'             => '+1',
        'content-available' => true,
        'mutable-content'   => true,
        'category'          => 'jiguang',
        'extras'            => [
            'info' => $info,
        ],
    ])
    ->androidNotification($content, [
        'title'  => $title,
        'extras' => [
            'info' => $info,
        ],
    ])
    ->options([
        // true 表示推送生产环境，false 表示要推送开发环境；如果不指定则默认为推送开发环境
        'apns_production' => false,
    ])
    ->send();
```

### 异常处理

``` php
<?php
$pusher = Yii::$app->jpush->client->push();
$pusher->setPlatform('all');
$pusher->addAllAudience();
$pusher->setNotificationAlert('Hello, JPush');
try {
    $pusher->send();
} catch (\Exception $e) {
    // try something else here
    echo $e;
}
```

## 测试

``` shell
$ composer test
```

## License

[MIT](LICENSE)
