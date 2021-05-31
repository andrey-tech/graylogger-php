<?php

namespace Test\Functional;

use GrayLogger\GrayLogger;

class InterpolateTest extends AppTestCase
{
    public function testInterpolate()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();

        $context = [
            'param_1' => 'test string 1',
            'param_2' => 'Тестовая строка 2',
        ];

        $logger->debug("Short message '{param_1}' and '{param_2}'", $context);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage(
            "Short message 'test string 1' and 'Тестовая строка 2'",
            GrayLogger::DEBUG,
            null,
            $context
        );
    }
}
