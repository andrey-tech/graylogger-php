<?php

namespace Test\Functional;

class SetServerTest extends AppTestCase
{
    public function testSetServerSuccess()
    {
        $this->server->setHost('127.0.0.5');
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->setServer('127.0.0.5');
        $logger->debug('test');
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage();
    }

    public function testSetServerError()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->setServer('127.0.0.5');

        $this->setExpectedException('GrayLogger\GrayLoggerException');
        $logger->debug('test');
    }
}
