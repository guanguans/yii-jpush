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
use Yii;

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
}
