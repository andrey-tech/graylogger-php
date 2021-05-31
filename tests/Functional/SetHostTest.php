<?php

namespace Test\Functional;

use GrayLogger\GrayLogger;

class SetHostTest extends AppTestCase
{
    public function testSetHostSuccess()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->setHost('example.com');
        $logger->debug('test');
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::DEBUG, 'example.com');
    }
}
