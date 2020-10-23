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
use Symfony\Component\OptionsResolver\OptionsResolver;
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

    protected $options = [];

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

        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve([
            'appKey' => $this->appKey,
            'masterSecret' => $this->masterSecret,
            'logFile' => $this->logFile,
            'retryTimes' => $this->retryTimes,
            'zone' => $this->zone,
        ]);

        $this->client = Yii::createObject(Client::class, [$this->appKey, $this->masterSecret, $this->logFile, $this->retryTimes, $this->zone]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'appKey' => $this->appKey,
            'masterSecret' => $this->masterSecret,
            'logFile' => $this->logFile,
            'retryTimes' => $this->retryTimes,
            'zone' => $this->zone,
        ]);
        $resolver->setRequired(['appKey', 'masterSecret']);
        $resolver->setAllowedTypes('retryTimes', 'int');
        $resolver->setAllowedTypes('appKey', 'string');
        $resolver->setAllowedTypes('masterSecret', 'string');
    }

    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
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
