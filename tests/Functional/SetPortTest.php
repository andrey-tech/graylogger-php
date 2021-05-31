<?php

namespace Test\Functional;

class SetPortTest extends AppTestCase
{
    public function testSetPortSuccess()
    {
        $this->server->setPort(12205);
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->setPort(12205);
        $logger->debug('test');
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage();
    }

    public function testSetPortError()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->setPort(12205);

        $this->setExpectedException('GrayLogger\GrayLoggerException');
        $logger->debug('test');
    }
}
