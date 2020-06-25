<?php

/*
 * This file is part of the guanguans/yii-jpush.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Guanguans\Tests;

use JPush\Client;
use JPush\DevicePayload;
use JPush\PushPayload;
use JPush\ReportPayload;
use JPush\SchedulePayload;
use Yii;
use yii\base\UnknownMethodException;

class JpushTest extends TestCase
{
    protected $jpush;

    protected function setUp()
    {
        parent::setUp();
        $this->jpush = Yii::$app->jpush;
    }

    public function testGetClient()
    {
        $this->assertInstanceOf(Client::class, $this->jpush->client);
        $this->assertInstanceOf(Client::class, $this->jpush->getClient());
    }

    public function testInvalidMethod()
    {
        $method = 'mock_method';
        $this->expectException(UnknownMethodException::class);
        $this->expectExceptionMessage(sprintf('Method does not exist. : %s', $method));
        $this->jpush->$method();
        $this->fail('Faild to assert call not exist method throw exception with invalid mehtod.');
    }

    public function testCall()
    {
        $this->assertInstanceOf(PushPayload::class, $this->jpush->push());
        $this->assertInstanceOf(ReportPayload::class, $this->jpush->report());
        $this->assertInstanceOf(DevicePayload::class, $this->jpush->device());
        $this->assertInstanceOf(SchedulePayload::class, $this->jpush->schedule());

        $this->assertSame($this->jpush->getAuthStr(), $this->jpush->client->getAuthStr());
        $this->assertSame($this->jpush->getRetryTimes(), $this->jpush->client->getRetryTimes());
        $this->assertSame($this->jpush->getLogFile(), $this->jpush->client->getLogFile());

        $this->assertSame($this->jpush->is_group(), $this->jpush->client->is_group());
        $this->assertSame($this->jpush->makeURL('push'), $this->jpush->client->makeURL('push'));
    }
}
