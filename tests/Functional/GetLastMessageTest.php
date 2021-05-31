<?php

namespace Test\Functional;

class GetLastMessageTest extends AppTestCase
{
    public function testGetLastMessage()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->debug('test', [ 'param_1' => 'test value' ]);
        self::assertNull($logger->getLastErrorMessage());

        $lastMessage = $logger->getLastMessage();
        $message = $this->getGrayLoggerMessage();

        self::assertEquals($lastMessage, $message);
    }
}
