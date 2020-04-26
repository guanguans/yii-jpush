<?php

/*
 * This file is part of the guanguans/yii-jpush.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

return [
    'id' => 'yii2-jpush-app',
    'basePath' => dirname(__DIR__),
    'components' => [
        'jpush' => [
            'class' => 'Guanguans\YiiJpush\Jpush',
            'appKey' => 'xxxxxxxxxxx',
            'masterSecret' => 'xxxxxxxxxxx',
        ],
    ],
];
