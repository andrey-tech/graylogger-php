<?php

namespace Test\Functional;

use GrayLogger\GrayLogger;

class SetContextTest extends AppTestCase
{
    public function testSetContext()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->setContext([ 'param_1' => 12345 ]);
        $logger->debug('test');
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::DEBUG, null, [ 'param_1' => 12345 ]);
    }

    public function testSetContextAndAdd()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->setContext([ 'param_1' => 12345 ]);
        $logger->debug('test', [ 'param_2' => 67890 ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::DEBUG, null, [ 'param_1' => 12345, 'param_2' => 67890 ]);
    }

    public function testSetContextAndReplace()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->setContext([ 'param_1' => 12345 ]);
        $logger->debug('test', [ 'param_1' => 67890 ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::DEBUG, null, [ 'param_1' => 67890 ]);
    }

    public function testSetContextMultiple()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->setContext([ 'param_1' => 12345 ]);
        $logger->setContext([ 'param_1' => 67890 ]);
        self::assertNull($logger->getLastErrorMessage());

        $logger->debug('test');
        $this->checkGrayLoggerMessage('test', GrayLogger::DEBUG, null, [ 'param_1' => 67890 ]);
    }
}
