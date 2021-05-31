<?php

namespace Test\Functional;

use GrayLogger\GrayLogger;

class InstanceTest extends AppTestCase
{
    public function testInstance()
    {
        $this->server->createSocket();

        $logger = GrayLogger::instance();
        $loggerHash = spl_object_hash($logger);
        $logger->setServer('localhost');
        $logger->setPort(12201);
        $logger->setConnectTimeout(1.0);
        $logger->setThrowExceptions(true);
        $logger->debug('test', [ 'param_1' => 12345 ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::DEBUG, null, [ 'param_1' => 12345 ]);

        $logger->debug('test2', [ 'param_2' => 67890 ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test2', GrayLogger::DEBUG, null, [ 'param_2' => 67890 ]);

        $logger2 = GrayLogger::instance();
        $logger2->setServer('localhost');
        $logger2->setPort(12201);
        $logger2->setConnectTimeout(1.0);
        $logger2->setThrowExceptions(true);
        $logger2->debug('test', [ 'param_3' => 'abba' ]);
        self::assertNull($logger2->getLastErrorMessage());

        $logger2Hash = spl_object_hash($logger);
        self::assertEquals($loggerHash, $logger2Hash);

        $this->checkGrayLoggerMessage('test', GrayLogger::DEBUG, null, [ 'param_3' => 'abba' ]);
    }
}
