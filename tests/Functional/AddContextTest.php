<?php

namespace Test\Functional;

use GrayLogger\GrayLogger;

class AddContextTest extends AppTestCase
{
    public function testAddContext()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->addContext([ 'param_1' => 12345 ]);
        $logger->debug('test');
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::DEBUG, null, [ 'param_1' => 12345 ]);
    }

    public function testAddContextAndAdd()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->addContext([ 'param_1' => 12345 ]);
        $logger->debug('test', [ 'param_2' => 67890 ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::DEBUG, null, [ 'param_1' => 12345, 'param_2' => 67890 ]);
    }

    public function testAddContextAndReplace()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->addContext([ 'param_1' => 12345 ]);
        $logger->debug('test', [ 'param_1' => 67890 ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::DEBUG, null, [ 'param_1' => 67890 ]);
    }

    public function testAddContextMultiple()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->addContext([ 'param_1' => 12345 ]);
        $logger->addContext([ 'param_2' => 67890 ]);
        $logger->debug('test');
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::DEBUG, null, [ 'param_1' => 12345, 'param_2' => 67890 ]);
    }
}
