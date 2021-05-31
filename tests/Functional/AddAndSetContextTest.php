<?php

namespace Test\Functional;

use GrayLogger\GrayLogger;

class AddAndSetContextTest extends AppTestCase
{
    public function testSetAndAddContext()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->setContext([ 'param_1' => 12345 ]);
        $logger->addContext([ 'param_2' => 67890 ]);
        $logger->debug('test');
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::DEBUG, null, [ 'param_1' => 12345, 'param_2' => 67890 ]);
    }

    public function testAddAndSetContext()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->addContext([ 'param_1' => 12345 ]);
        $logger->setContext([ 'param_1' => 67890 ]);
        $logger->debug('test');
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::DEBUG, null, [ 'param_1' => 67890 ]);
    }

    public function testSetAndAddContextAndAdd()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->setContext([ 'param_1' => 12345 ]);
        $logger->addContext([ 'param_2' => 67890 ]);
        $logger->debug('test', [ 'param_3' => 'abcdef' ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage(
            'test',
            GrayLogger::DEBUG,
            null,
            [ 'param_1' => 12345, 'param_2' => 67890, 'param_3' => 'abcdef' ]
        );
    }
}
