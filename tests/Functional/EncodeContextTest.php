<?php

namespace Test\Functional;

use GrayLogger\GrayLogger;

class EncodeContextTest extends AppTestCase
{
    public function testEncodeContextSuccess()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();

        $context = [
            'param_1' => 'test string',
            'param_2' => 'Тестовая строка',
            '_param_3' => '\ "test" string &amp; <br/>',
            '__param_4' => '',
            'param_5' => 123456789,
            'param_6' => 12345.6789,
            'param_7' => null,
            'param_8' => [ 'foo' => 'bar', 12 => 12345.678, 'fuz' => null ],
            'param_9' => new \stdClass()
        ];
        $logger->debug('test', $context);
        self::assertNull($logger->getLastErrorMessage());

        $messageParams = [
            'param_1' => 'test string',
            'param_2' => 'Тестовая строка',
            '_param_3' => '\ "test" string &amp; <br/>',
            '__param_4' => '',
            'param_5' => 123456789,
            'param_6' => 12345.6789,
            'param_7' => 'NULL',
            'param_8' => Json::encode([ 'foo' => 'bar', 12 => 12345.678, 'fuz' => null ]),
            'param_9' => '[object (stdClass)]'
        ];

        $this->checkGrayLoggerMessage('test', GrayLogger::DEBUG, null, $messageParams);
    }

    public function testEncodeContextError()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();

        $this->setExpectedException('GrayLogger\GrayLoggerException');
        $logger->debug('test', [ 'param_1'  => urldecode('%EF%F2%E8%F6%E0') ]);
    }
}
