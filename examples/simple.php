<?php

use GrayLogger\GrayLogger;
use GrayLogger\GrayLoggerException;

try {
    // Создаем объект класса GrayLogger
    $logger = new GrayLogger();

    // Устанавливаем адрес сервера Graylog
    $logger->setServer('graylog.example.com');

    // Устанавливаем TCP-порт сервера Graylog
    $logger->setPort(9000);

    // Устанавливаем таймаут соединения с сервером Graylog равный 5 секундам
    $logger->setConnectTimeout(5.0);

    // Разрешаем выбрасывать исключение класса GrayLoggerException при возникновении ошибки
    $logger->setThrowExceptions(true);

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
        'request_id' => $this->getUniqId(),

        // Имя файла скрипта, который сейчас выполняется, относительно корня документов
        'script'     => $_SERVER['PHP_SELF']
    ]);

    /*
     * Выполняем логирование с уровнем INFO
     * Пример сформированного сообщения GELF (pretty print):
     * {
     *     "version": "1.1",
     *     "host": "localhost",
     *     "timestamp": 1622394990.561,
     *     "short_message": "Request",
     *     "level": 6,
     *     "_request_id": "w1fv73k",
     *     "_script": "/index.php",
     *     "_request": "{ \"id\": \"12345\" }"
     * }
     */
    $logger->info('Request', [
        'request'    => $_POST // Данные POST-запроса в кодировке UTF-8
    ]);

    /*
     * Добавляем новые элементы в массив сопутствующих данных в кодировке UTF-8,
     * передаваемых в дополнительных полях (additional field) GELF
     * во всех последующих лог-сообщениях
     */
    $logger->addContext([
        'param'     => 6459
    ]);

    /*
     * Выполняем логирование с уровнем WARNING
     * Пример сформированного сообщения GELF (pretty print):
     * {
     *     "version": "1.1",
     *     "host": "localhost",
     *     "timestamp": 1622394991.113,
     *     "short_message": "Value of parameter is 6459",
     *     "level": 4,
     *     "_request_id": "w1fv73k",
     *     "_script": "/index.php",
     *     "_param": 6459,
     *     "_foo": "bar"
     * }
     */
    $logger->log(GrayLogger::WARNING, 'Value of parameter is {param}', [ 'foo' => 'bar' ]);
} catch (GrayLoggerException $exception) {
    printf('Ошибка (%d): %s' . PHP_EOL, $exception->getCode(), $exception->getMessage());
}
