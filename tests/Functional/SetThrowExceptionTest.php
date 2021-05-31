<?php

namespace Test\Functional;

class SetThrowExceptionTest extends AppTestCase
{
    public function testSetThrowExceptionSuccess()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->setPort(12205);
        $logger->setThrowExceptions(true);

        $this->setExpectedException('GrayLogger\GrayLoggerException');
        $logger->debug('test');
    }

    public function testSetThrowExceptionError()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->setPort(12205);

        $logger->setThrowExceptions(false);
        $logger->debug('test');

        $this->setExpectedException('RuntimeException');
        $this->checkGrayLoggerMessage();
    }
}
