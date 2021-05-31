<?php

namespace Test\Functional;

use PHPUnit\Framework\TestCase;
use GrayLogger\GrayLogger;

class AppTestCase extends TestCase
{
    protected $server;

    protected function setUp()
    {
        parent::setUp();
        $this->server = new SocketServerStub();
        $this->server->setHost('localhost');
        $this->server->setPort(12201);
    }

    protected function tearDown()
    {
        parent::tearDown();
        unset($this->server);
    }

    protected function buildGrayLogger()
    {
        $logger = new GrayLogger();
        $logger->setServer('localhost');
        $logger->setPort(12201);
        $logger->setConnectTimeout(1.0);
        $logger->setThrowExceptions(true);
        return $logger;
    }

    protected function getGrayLoggerMessage()
    {
        return $this->server->accept();
    }

    protected function checkGrayLoggerMessage(
        $shortMessage = 'test',
        $level = GrayLogger::DEBUG,
        $hostname = null,
        array $checkParams = []
    ) {
        $message = $this->getGrayLoggerMessage();
        $message =  Json::decode(rtrim($message));

        self::assertArrayHasKey('version', $message);
        self::assertEquals('1.1', $message['version']);

        if (!isset($hostname)) {
            $hostname = gethostname();
            if ($hostname === false) {
                $hostname = '';
            }
        }

        self::assertArrayHasKey('host', $message);
        self::assertEquals($hostname, $message['host']);

        self::assertArrayHasKey('short_message', $message);
        self::assertEquals($shortMessage, $message['short_message']);

        self::assertArrayHasKey('level', $message);
        self::assertEquals($level, $message['level']);

        self::assertArrayHasKey('timestamp', $message);
        self::assertRegExp('/^\d{10}(\.\d{1,3})?$/', (string) $message['timestamp']);
        self::assertEquals(time(), $message['timestamp'], 'Incorrect value of timestamp', 5.0);

        foreach ($checkParams as $name => $value) {
            $keyName = '_' . $name;
            self::assertArrayHasKey($keyName, $message);
            self::assertEquals($value, $message[$keyName], "Filed assertEquals() for parameter '{$name}'.");
        }
    }
}
