<?php

/*
 * This file is part of the guanguans/yii-jpush.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Guanguans\YiiJpush;

use Closure;
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

    /**
     * @var string
     */
    public $appKey;

    /**
     * @var string
     */
    public $masterSecret;

    /**
     * @var string
     */
    public $logFile = Config::DEFAULT_LOG_FILE;

    /**
     * @var int
     */
    public $retryTimes = Config::DEFAULT_MAX_RETRY_TIMES;

    /**
     * @var string|null
     */
    public $zone;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var \JPush\Client
     */
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

        $this->setOptions([
            'appKey' => $this->appKey,
            'masterSecret' => $this->masterSecret,
            'logFile' => $this->logFile,
            'retryTimes' => $this->retryTimes,
            'zone' => $this->zone,
        ]);

        $this->client = Yii::createObject(Client::class, [$this->appKey, $this->masterSecret, $this->logFile, $this->retryTimes, $this->zone]);
    }

    /**
     * Configuration options.
     *
     * @return array
     */
    protected function configureOptions(array $options, Closure $closure)
    {
        $resolver = new OptionsResolver();

        $closure($resolver);

        return $resolver->resolve($options);
    }

    public function setOptions(array $options)
    {
        $this->options = $this->configureOptions($options, function (OptionsResolver $resolver) {
            $resolver->setDefaults([
                'appKey' => $this->appKey,
                'masterSecret' => $this->masterSecret,
                'logFile' => $this->logFile,
                'retryTimes' => $this->retryTimes,
                'zone' => $this->zone,
            ]);
            $resolver->setRequired(['appKey', 'masterSecret']);
            $resolver->setAllowedTypes('retryTimes', 'int');
            $resolver->setAllowedTypes('logFile', 'int');
            $resolver->setAllowedTypes('appKey', 'string');
            $resolver->setAllowedTypes('masterSecret', 'string');
            $resolver->setAllowedValues('zone', [null, 'default', 'bj']);
        });
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
