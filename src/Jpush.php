<?php

/*
 * This file is part of the guanguans/yii-jpush.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Guanguans\YiiJpush;

use Guanguans\YiiJpush\Traits\Macroable;
use JPush\Client;
use JPush\Config;
use JPush\DevicePayload;
use JPush\PushPayload;
use JPush\ReportPayload;
use JPush\SchedulePayload;
use Yii;
use yii\base\Component;
use yii\base\UnknownMethodException;

/**
 * Class Jpush.
 */
class Jpush extends Component
{
    use Macroable;

    public $appKey;

    public $masterSecret;

    public $logFile = Config::DEFAULT_LOG_FILE;

    public $retryTimes = Config::DEFAULT_MAX_RETRY_TIMES;

    public $zone;

    protected $client;

    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the given configuration.
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        $this->client = $client = Yii::createObject(Client::class, [$this->appKey, $this->masterSecret, $this->logFile, $this->retryTimes, $this->zone]);

        self::macro('push', function () use ($client) {
            return new PushPayload($client);
        });

        self::macro('report', function () use ($client) {
            return new ReportPayload($client);
        });

        self::macro('device', function () use ($client) {
            return new DevicePayload($client);
        });

        self::macro('schedule', function () use ($client) {
            return new SchedulePayload($client);
        });
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if (!method_exists($this->client, $method)) {
            throw new UnknownMethodException(sprintf('Method does not exist. : %s', $method));
        }

        return call_user_func_array([$this->client, $method], $arguments);
    }
}
