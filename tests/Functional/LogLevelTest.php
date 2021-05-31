<?php

namespace Test\Functional;

use GrayLogger\GrayLogger;

class LogLevelTest extends AppTestCase
{
    public function testDebugLog()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->log(GrayLogger::DEBUG, 'test', [ 'param_1' => 'value' ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::DEBUG, null, [ 'param_1' => 'value' ]);
    }

    public function testInfoLog()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->log(GrayLogger::INFO, 'test', [ 'param_1' => 'value' ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::INFO, null, [ 'param_1' => 'value' ]);
    }

    public function testNoticeLog()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->log(GrayLogger::NOTICE, 'test', [ 'param_1' => 'value' ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::NOTICE, null, [ 'param_1' => 'value' ]);
    }

    public function testWarningLog()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->log(GrayLogger::WARNING, 'test', [ 'param_1' => 'value' ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::WARNING, null, [ 'param_1' => 'value' ]);
    }

    public function testErrorLog()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->log(GrayLogger::ERROR, 'test', [ 'param_1' => 'value' ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::ERROR, null, [ 'param_1' => 'value' ]);
    }

    public function testCriticalLog()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->log(GrayLogger::CRITICAL, 'test', [ 'param_1' => 'value' ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::CRITICAL, null, [ 'param_1' => 'value' ]);
    }

    public function testAlertLog()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->log(GrayLogger::ALERT, 'test', [ 'param_1' => 'value' ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::ALERT, null, [ 'param_1' => 'value' ]);
    }

    public function testEmergencyLog()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->log(GrayLogger::EMERGENCY, 'test', [ 'param_1' => 'value' ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::EMERGENCY, null, [ 'param_1' => 'value' ]);
    }

    public function testMultipleLevelLog()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $logger->log(GrayLogger::INFO, 'test', [ 'param_1' => 'value' ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::INFO, null, [ 'param_1' => 'value' ]);

        $logger->log(GrayLogger::ERROR, 'test', [ 'param_2' => 'value' ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::ERROR, null, [ 'param_2' => 'value' ]);
    }
}
