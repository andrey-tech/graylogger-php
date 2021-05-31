# GrayLogger PHP

![Graylog logo](assets/graylog-logo.png)  

Простой [PSR-3](https://www.php-fig.org/psr/psr-3/)
логгер в [Graylog](https://www.graylog.org/)
в формате [GELF](https://docs.graylog.org/en/3.3/pages/gelf.html)
версии [1.1](https://docs.graylog.org/en/3.3/pages/gelf.html#gelf-payload-specification)
по протоколу [TCP](https://docs.graylog.org/en/3.3/pages/gelf.html#gelf-via-tcp).

[![Latest Stable Version](https://poser.pugx.org/andrey-tech/graylogger-php/v)](https://packagist.org/packages/andrey-tech/graylogger-php)
[![Total Downloads](https://poser.pugx.org/andrey-tech/graylogger-php/downloads)](https://packagist.org/packages/andrey-tech/graylogger-php)
[![License](https://poser.pugx.org/andrey-tech/graylogger-php/license)](https://packagist.org/packages/andrey-tech/graylogger-php)

# Содержание

<!-- MarkdownTOC levels="1,2,3,4,5,6" autoanchor="true" autolink="true" -->

- [Требования](#%D0%A2%D1%80%D0%B5%D0%B1%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D1%8F)
- [Установка](#%D0%A3%D1%81%D1%82%D0%B0%D0%BD%D0%BE%D0%B2%D0%BA%D0%B0)
- [Класс `GrayLogger`](#%D0%9A%D0%BB%D0%B0%D1%81%D1%81-graylogger)
    - [Методы класса](#%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%BA%D0%BB%D0%B0%D1%81%D1%81%D0%B0)
    - [Примеры](#%D0%9F%D1%80%D0%B8%D0%BC%D0%B5%D1%80%D1%8B)
- [Тестирование](#%D0%A2%D0%B5%D1%81%D1%82%D0%B8%D1%80%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D0%B5)
    - [Функциональное тестирование](#%D0%A4%D1%83%D0%BD%D0%BA%D1%86%D0%B8%D0%BE%D0%BD%D0%B0%D0%BB%D1%8C%D0%BD%D0%BE%D0%B5-%D1%82%D0%B5%D1%81%D1%82%D0%B8%D1%80%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D0%B5)
    - [Анализ кода](#%D0%90%D0%BD%D0%B0%D0%BB%D0%B8%D0%B7-%D0%BA%D0%BE%D0%B4%D0%B0)
- [Автор](#%D0%90%D0%B2%D1%82%D0%BE%D1%80)
- [Лицензия](#%D0%9B%D0%B8%D1%86%D0%B5%D0%BD%D0%B7%D0%B8%D1%8F)

<!-- /MarkdownTOC -->

<a id="%D0%A2%D1%80%D0%B5%D0%B1%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D1%8F"></a>
## Требования

- PHP >= 5.4;
- Произвольный автозагрузчик классов, реализующий стандарт [PSR-4](https://www.php-fig.org/psr/psr-4/),
и необходимый когда Composer не используется.

<a id="%D0%A3%D1%81%D1%82%D0%B0%D0%BD%D0%BE%D0%B2%D0%BA%D0%B0"></a>
## Установка

Установка через composer:
```
$ composer require andrey-tech/graylogger-php:"^1.4"
```

<a id="%D0%9A%D0%BB%D0%B0%D1%81%D1%81-graylogger"></a>
## Класс `GrayLogger`

Класс `\GrayLogger\GrayLogger` реализует интерфейс `\Psr\Log\LoggerInterface`, согласно стандарту [PSR-4](https://www.php-fig.org/psr/psr-4/),
и обеспечивает логирование в [Graylog](https://www.graylog.org/)
в формате [GELF](https://docs.graylog.org/en/3.3/pages/gelf.html) 
версии [1.1](https://docs.graylog.org/en/3.3/pages/gelf.html#gelf-payload-specification)
по протоколу [TCP](https://docs.graylog.org/en/3.3/pages/gelf.html#gelf-via-tcp).

При возникновении ошибок может выбрасываться исключение класса `\GrayLogger\GrayLoggerException`
(по умолчанию отключено, см. метод класса `setThrowException()`).

<a id="%D0%9C%D0%B5%D1%82%D0%BE%D0%B4%D1%8B-%D0%BA%D0%BB%D0%B0%D1%81%D1%81%D0%B0"></a>
### Методы класса

Класс `\GrayLogger\GrayLogger` имеет следующие публичные методы:

Метод                                          | Описание | По умолчанию  
----------------------------------------------------| ---------------- | ----------- 
`setServer(string $server): void`                   | Устанавливает адрес сервера Graylog | _localhost_
`setPort(string $port): void`                       | Устанавливает TCP-порт сервера Graylog  | _12201_
`setConnectTimeout(float $connectTimeout): void`    | Устанавливает таймаут соединения с сервером Graylog в секундах | _10.0_
`setHost(string $host): void`                       | Устанавливает имя хоста для логирования | [`gethostname()`](https://www.php.net/manual/en/function.gethostname.php)
`setThrowExceptions(bool $throwExceptions): void`   | Устанавливает флаг - выбрасывать ли исключение класса `GrayLoggerException` при возникновении ошибки: _true_ - выбрасывать, _false_ - не выбрасывать | _false_
`setContext(array $context): void`                  | Устанавливает массив сопутствующих данных в кодировке UTF-8, передаваемых в дополнительных полях (additional field) [GELF](https://docs.graylog.org/en/3.3/pages/gelf.html#gelf-payload-specification) во всех последующих лог-сообщениях
`addContext(array $context): void`                  | Добавляет новые элементы в массив сопутствующих данных в кодировке UTF-8, передаваемых в дополнительных полях (additional field) [GELF](https://docs.graylog.org/en/3.3/pages/gelf.html#gelf-payload-specification) во всех последующих лог-сообщениях
`getLastMessage(): ?string`                         | Возвращает последнее сформированной сообщение [GELF](https://docs.graylog.org/en/3.3/pages/gelf.html#gelf-payload-specification)
`getLastErrorMessage(): ?string`                    | Возвращает последнее сообщение об ошибке (исключении)
`getUniqId(int $length = 7) :string`                | Возвращает уникальный буквенно-цифровой идентификатор, связанный с объектом класса `GrayLogger` и необходимый для поиска в Graylog всех лог-сообщений, сформированных в рамках одного запуска PHP-скрипта<sup>1</sup> |
`static instance(): self`                           | Возвращает единственный объект класса `GrayLogger` (синглтон)
**Методы [PSR-4](https://www.php-fig.org/psr/psr-4/)** <sup>2</sup>                                   |                  | 
`emergency(string\|object $message, array $context = []): void`  | Выполняет логирование с уровнем EMERGENCY |
`alert(string\|object $message, array $context = []): void`      | Выполняет логирование с уровнем ALERT |
`critical(string\|object $message, array $context = []): void`   | Выполняет логирование с уровнем CRITICAL |
`error(string\|object $message, array $context = []): void`      | Выполняет логирование с уровнем ERROR |
`warning(string\|object $message, array $context = []): void`    | Выполняет логирование с уровнем WARNING |
`notice(string\|object $message, array $context = []): void`     | Выполняет логирование с уровнем NOTICE |
`info(string\|object $message, array $context = []): void`       | Выполняет логирование с уровнем INFO |
`debug(string\|object $message, array $context = []): void`      | Выполняет логирование с уровнем DEBUG |
`log(int $level, string\|object $message, array $context = []): void` | Выполняет логирование с уровнем, заданным параметром `$level`<sup>3</sup> |

Примечания.

1) Строка идентификатора удовлетворяет регулярному выражению _/^[a-z0-9]+$/_. Допустимая длина идентификатора: 0-36 символов, по умолчанию - 7 символов.

2) В параметре `$message` передается сообщение, которое должно быть строкой в кодировке UTF-8 или объектом, реализующим метод `__toString()`.
Сообщение может содержать плейсхолдеры в виде `{foo}`, где `foo` будет заменено на значение элемента массива сопутствующих данных, передаваемых в параметре `$context` с ключом `foo`.
Сообщение передается в поле `short_message` [GELF](https://docs.graylog.org/en/3.3/pages/gelf.html#gelf-payload-specification).  
Параметр `$context` может содержать массив сопутствующих данных в кодировке UTF-8, передаваемых в дополнительных полях (additional field) [GELF](https://docs.graylog.org/en/3.3/pages/gelf.html#gelf-payload-specification).
  
3) Возможные значения параметра `$level` задаются публичными константами класса: 
`GrayLogger::EMERGENCY`, `GrayLogger::ALERT`, `GrayLogger::CRITICAL`, `GrayLogger::ERROR`,
`GrayLogger::WARNING`, `GrayLogger::NOTICE`, `GrayLogger::INFO`, `GrayLogger::DEBUG`.

<a id="%D0%9F%D1%80%D0%B8%D0%BC%D0%B5%D1%80%D1%8B"></a>
### Примеры

Файлы примеров расположены в каталоге _examples_.

Пример использования класса `GrayLogger` с перехватом исключений класса `GrayLoggerException`:
```php
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
```

Пример использования класса `GrayLogger` и метода `instance()` с запретом выбрасывать исключения класса `GrayLoggerException`:

```php
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
```

<a id="%D0%A2%D0%B5%D1%81%D1%82%D0%B8%D1%80%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D0%B5"></a>
## Тестирование

Тестирование выполняется с помощью библиотеки [PHPUnit версии 4](https://phpunit.de/getting-started/phpunit-4.html)
для обеспечения совместимости с PHP 5.4.

<a id="%D0%A4%D1%83%D0%BD%D0%BA%D1%86%D0%B8%D0%BE%D0%BD%D0%B0%D0%BB%D1%8C%D0%BD%D0%BE%D0%B5-%D1%82%D0%B5%D1%81%D1%82%D0%B8%D1%80%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D0%B5"></a>
### Функциональное тестирование

Классы функциональных тестов расположены в каталоге _tests/Functional_.
Функциональное тестирование реализовано при помощи класса `\Test\Functional\SocketServerStub`,
который эмулирует сервер GrayLog и принимает входящие запросы по адресу `tcp://localhost:12201`.
Для функционального тестирования разработано 46 тестов PHPUnit, запускаемых командой:
```
$ vendor/bin/phpunit
```

<a id="%D0%90%D0%BD%D0%B0%D0%BB%D0%B8%D0%B7-%D0%BA%D0%BE%D0%B4%D0%B0"></a>
### Анализ кода

Для анализа нарушений стандарта кодирования [PSR-2](https://www.php-fig.org/psr/psr-2/) используется PHP CodeSniffer,
запускаемый командой:
```
$ vendor/bin/phpcs --standard=PSR2 src tests examples
```

<a id="%D0%90%D0%B2%D1%82%D0%BE%D1%80"></a>
## Автор

© 2021 andrey-tech

<a id="%D0%9B%D0%B8%D1%86%D0%B5%D0%BD%D0%B7%D0%B8%D1%8F"></a>
## Лицензия

Данная библиотека распространяется на условиях лицензии [MIT](./LICENSE).
