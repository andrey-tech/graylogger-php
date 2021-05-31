<?php

namespace Test\Functional;

use GrayLogger\GrayLogger;

class GetUniqIdTest extends AppTestCase
{
    public function testGetUniqId()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $uniqId = $logger->getUniqId();
        self::assertRegExp('/^[a-z0-9]{7}$/', $uniqId);

        $logger->debug('test', [ 'uniq_id' => $uniqId ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::DEBUG, null, [ 'uniq_id' => $uniqId ]);
    }

    public function testGetUniqIdMultiple()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $uniqId1 = $logger->getUniqId();
        self::assertRegExp('/^[a-z0-9]{7}$/', $uniqId1);

        $logger->debug('test', [ 'uniq_id' => $uniqId1 ]);
        self::assertNull($logger->getLastErrorMessage());

        $uniqId2 = $logger->getUniqId();
        self::assertRegExp('/^[a-z0-9]{7}$/', $uniqId2);

        self::assertEquals($uniqId1, $uniqId2);
        $this->checkGrayLoggerMessage('test', GrayLogger::DEBUG, null, [ 'uniq_id' => $uniqId1 ]);
    }

    public function testGetUniqIdLength()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $uniqId = $logger->getUniqId(10);
        self::assertRegExp('/^[a-z0-9]{10}$/', $uniqId);

        $logger->debug('test', [ 'uniq_id' => $uniqId ]);
        self::assertNull($logger->getLastErrorMessage());

        $this->checkGrayLoggerMessage('test', GrayLogger::DEBUG, null, [ 'uniq_id' => $uniqId ]);
    }

    public function testGetUniqIdLengthMultiple()
    {
        $this->server->createSocket();

        $logger = $this->buildGrayLogger();
        $uniqId1 = $logger->getUniqId(10);
        self::assertRegExp('/^[a-z0-9]{10}$/', $uniqId1);

        $logger->debug('test', [ 'uniq_id' => $uniqId1 ]);
        self::assertNull($logger->getLastErrorMessage());

        $uniqId2 = $logger->getUniqId(10);
        self::assertRegExp('/^[a-z0-9]{10}$/', $uniqId2);

        self::assertEquals($uniqId1, $uniqId2);
        $this->checkGrayLoggerMessage('test', GrayLogger::DEBUG, null, [ 'uniq_id' => $uniqId1 ]);
    }
}
