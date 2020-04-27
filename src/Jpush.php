<?php

/*
 * This file is part of the guanguans/yii-jpush.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Guanguans\YiiJpush;

use JPush\Client;
use JPush\Config;
use yii\base\Component;

/**
 * Class Jpush.
 */
class Jpush extends Component
{
    public $appKey;

    public $masterSecret;

    public $logFile = Config::DEFAULT_LOG_FILE;

    public $retryTimes = Config::DEFAULT_MAX_RETRY_TIMES;

    public $zone = null;

    protected $client;

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the given configuration.
     */
    public function init()
    {
        parent::init();
        $this->client = new Client($this->appKey, $this->masterSecret, $this->logFile, $this->retryTimes, $this->zone);
    }
}
