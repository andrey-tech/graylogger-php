<?php

namespace Test\Functional;

class GetLastErrorMessageTest extends AppTestCase
{
    public function testGetLastErrorMessage()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->setServer('127.0.0.5');
        $logger->setPort(12201);
        $logger->setThrowExceptions(false);
        $logger->debug('test');
        $lastErrorMessage = $logger->getLastErrorMessage();

        self::assertContains("Can't stream_socket_client() for tcp://127.0.0.5:12201", $lastErrorMessage);
    }
}
