<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Notification\Pushbullet;

class MessageTest extends TestCase
{
    public function testSend()
    {
        $provider = $this->createMock(Pushbullet::class);
        $provider->expects($this->once())
            ->method('push')
            ->with($this->equalTo('title'), $this->equalTo('body'));

        $message = new \App\Services\Message($provider);
        $message->send('title', 'body');
    }
}
