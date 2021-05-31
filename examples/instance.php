<?php

use GrayLogger\GrayLogger;

// Создаем объект класса GrayLogger
$logger = GrayLogger::instance();

// Устанавливаем адрес сервера Graylog
$logger->setServer('graylog.example.com');

// Устанавливаем TCP-порт сервера Graylog
$logger->setPort(9000);

// Устанавливаем таймаут соединения с сервером Graylog равный 5 секундам
$logger->setConnectTimeout(5.0);

/*
 * Явно запрещаем выбрасывать исключение класса GrayLoggerException
 * при возникновении ошибки (поведение по умолчанию)
 */
$logger->setThrowExceptions(false);

/*
 * Устанавливаем массив сопутствующих данных в кодировке UTF-8,
 * передаваемых в дополнительных полях (additional field) GELF
 * во всех последующих лог-сообщениях
 */
$logger->setContext([
    /*
     * Уникальный буквенно-цифровой идентификатор, необходимый для поиска в Graylog
     * всех лог-сообщений в рамках одного запроса
     */
    'request_id' => $this->getUniqId()
]);

/*
 * Выполняем логирование с уровнем INFO
 * Пример сформированного сообщения GELF (pretty print):
 * {
 *     "version": "1.1",
 *     "host": "localhost",
 *     "timestamp": 1622394995.449,
 *     "short_message": "Request",
 *     "level": 6,
 *     "_request_id": "i4prla2",
 *    "_foo": "bar"
 * }
 */
$logger->info('Request', [ 'foo' => 'bar' ]);

/*
 * В другом месте получаем тот же объект класса GrayLogger
 * $logger === $logger2
 */
$logger2 = GrayLogger::instance();

/*
 * Выполняем логирование с уровнем WARNING
 * Пример сформированного сообщения GELF (pretty print):
 * {
 *     "version": "1.1",
 *     "host": "localhost",
 *     "timestamp": 1622394996.261,
 *     "short_message": "Value of parameter foo is bar",
 *     "level": 7,
 *     "_request_id": "i4prla2",
 *    "_foo": "bar"
 * }
 */
$logger2->log(GrayLogger::DEBUG, 'Value of parameter foo is {foo}');

// Выводим последнее сформированное лог-сообщение
echo $logger2->getLastMessage();

// Выводим последнее сообщение об ошибке (исключении) при его наличии
$errorMessage = $logger2->getLastErrorMessage();
if (isset($errorMessage)) {
    echo $errorMessage;
}
