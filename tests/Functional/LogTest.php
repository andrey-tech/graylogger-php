<?php

namespace Test\Functional;

use GrayLogger\GrayLogger;

class LogTest extends AppTestCase
{
    public function testDebug()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->debug('test', [ 'param_1' => 'value' ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::DEBUG, null, [ 'param_1' => 'value' ]);
    }

    public function testInfo()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->info('test', [ 'param_1' => 'value' ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::INFO, null, [ 'param_1' => 'value' ]);
    }

    public function testNotice()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->notice('test', [ 'param_1' => 'value' ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::NOTICE, null, [ 'param_1' => 'value' ]);
    }

    public function testWarning()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->warning('test', [ 'param_1' => 'value' ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::WARNING, null, [ 'param_1' => 'value' ]);
    }

    public function testError()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->error('test', [ 'param_1' => 'value' ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::ERROR, null, [ 'param_1' => 'value' ]);
    }

    public function testCriticalLog()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->critical('test', [ 'param_1' => 'value' ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::CRITICAL, null, [ 'param_1' => 'value' ]);
    }

    public function testAlert()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->alert('test', [ 'param_1' => 'value' ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::ALERT, null, [ 'param_1' => 'value' ]);
    }

    public function testEmergency()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->emergency('test', [ 'param_1' => 'value' ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::EMERGENCY, null, [ 'param_1' => 'value' ]);
    }

    public function testMultipleLog()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->info('test', [ 'param_1' => 'value' ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::INFO, null, [ 'param_1' => 'value' ]);

        $logger->error('test', [ 'param_2' => 'value' ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::ERROR, null, [ 'param_2' => 'value' ]);
    }
}
