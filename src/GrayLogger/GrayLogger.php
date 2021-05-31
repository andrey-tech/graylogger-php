<?php

/**
 * Класс GrayLogger. Выполняет логирование в Graylog в формате GELF версии 1.1 по протоколу TCP
 * @link https://docs.graylog.org/en/3.3/pages/gelf.html
 *
 * @author    andrey-tech
 * @copyright 2021 andrey-tech
 * @see https://github.com/andrey-tech/graylogger-php
 * @license   MIT
 *
 * @version 1.4.0
 *
 * v1.0.0 (02.05.2021) Первый релиз.
 * v1.1.0 (03.05.2021) Добавлены публичные методы для 6-и уровней логирования. Рефакторинг.
 * v1.1.1 (03.05.2021) Удален из конструктора параметр $throwException .
 * v1.2.0 (04.05.2021) Добавлено свойство $context. Добавлены сеттеры для закрытых свойств класса.
 * v1.3.0 (12.05.2021) Добавлены методы instance(), createUniqId() и getUniqId(), addContext().
 * v1.4.0 (28.05.2021) Приведение к стандарту PSR-3.
 *                     Добавлен класс GrayLoggerException.
 *                     Добавлен обработчик ошибок в закрытый метод closeSocket().
 *                     Добавлен параметр $length в публичный метод getUniqId().
 *
 */

namespace GrayLogger;

use Psr\Log\LoggerInterface;

class GrayLogger implements LoggerInterface
{
    /**
     * Адрес сервера Graylog
     * @var string
     */
    private $server = 'localhost';

    /**
     * Порт сервера Graylog
     * @var int
     */
    private $port = 12201;

    /**
     * Таймаут соединения с сервером, секунд
     * @var float
     */
    private $connectTimeout = 10.0;

    /**
     * Имя хоста для лога
     * @var string
     */
    private $host;

    /**
     * Уникальный буквенно-цифровой идентификатор объекта данного класса
     * @var string
     */
    private $uniqId;

    /**
     * Выбрасывать ли исключение при возникновении ошибки
     * @var bool
     */
    private $throwExceptions = false;

    /**
     * Дополнительные поля, передаваемые в каждом сообщении для логирования
     * @var array
     */
    private $context = [];

    /**
     * Хранит последнее созданное сообщение для логирования
     * @var string|null
     */
    private $lastMessage;

    /**
     * Хранит последнее сообщение об ошибке
     * @var string|null
     */
    private $lastErrorMessage;

    /**
     * Схема соединения для сокета
     * @var string
     */
    private $scheme = 'tcp';

    /**
     * Версия протокола GELF
     * @var string
     */
    private $version = '1.1';

    /**
     * Ресурс сокета
     * @var resource|null
     */
    private $socket;

    /**
     * Хранит единственный объект данного класса
     * @var GrayLogger
     */
    private static $instance;

    /**
     * Уровни логирования согласно PSR-3 и syslog log levels
     * @link https://success.trendmicro.com/solution/TP000086250-What-are-Syslog-Facilities-and-Levels
     * @var int
     */
    const EMERGENCY = 0;
    const ALERT = 1;
    const CRITICAL = 2;
    const ERROR = 3;
    const WARNING = 4;
    const NOTICE = 5;
    const INFO = 6;
    const DEBUG = 7;

    /**
     * Конструктор
     */
    public function __construct()
    {
        $this->host = $this->getHostname();
    }

    /**
     * Деструктор
     */
    public function __destruct()
    {
        try {
            $this->closeSocket();
        } catch (GrayLoggerException $exception) {
            $this->exceptionHandler($exception);
        }
    }

    /**
     * Возвращает единственный объект данного класса
     * @return GrayLogger
     */
    public static function instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Устанавливает адрес сервера Graylog
     * @param string $server
     */
    public function setServer($server)
    {
        $this->server = (string)$server;
    }

    /**
     * Устанавливает порт сервера Graylog
     * @param int $port
     */
    public function setPort($port)
    {
        $this->port = (int)$port;
    }

    /**
     * Устанавливает таймаут соединения с сервером Graylog в секундах
     * @param float $connectTimeout
     */
    public function setConnectTimeout($connectTimeout)
    {
        $this->connectTimeout = (float)$connectTimeout;
    }

    /**
     * Устанавливает имя хоста для логирования
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = (string)$host;
    }

    /**
     * Устанавливает флаг выбрасывать ли исключение при возникновении ошибки
     * @param bool $throwExceptions
     */
    public function setThrowExceptions($throwExceptions)
    {
        $this->throwExceptions = (bool)$throwExceptions;
    }

    /**
     * Устанавливает дополнительные поля, передаваемые в каждом сообщении для логирования
     * @param array $context
     */
    public function setContext(array $context)
    {
        $this->context = $context;
    }

    /**
     * Добавляет дополнительные поля, передаваемые в каждом сообщении для логирования
     * @param array $context
     */
    public function addContext(array $context)
    {
        $this->context = array_merge($this->context, $context);
    }

    /**
     * Возвращает последнее сформированной сообщение GELF
     * @return string|null
     */
    public function getLastMessage()
    {
        return $this->lastMessage;
    }

    /**
     * Возвращает последнее сообщение об ошибке (исключении)
     * @return string|null
     */
    public function getLastErrorMessage()
    {
        return $this->lastErrorMessage;
    }

    /**
     * Возвращает уникальный буквенно-цифровой идентификатор, связанный с объектом данного класса
     * @param  int $length Длина идентификатора (число символов)
     * @return string
     */
    public function getUniqId($length = 7)
    {
        if (isset($this->uniqId)) {
            return substr($this->uniqId, 0, $length);
        }

        $this->uniqId = str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz");

        return $this->getUniqId($length);
    }

    /**
     * Выполняет логирование с уровнем EMERGENCY
     * @param string $message
     * @param array $context
     * @throws GrayLoggerException
     */
    public function emergency($message, array $context = [])
    {
        $this->log(self::EMERGENCY, $message, $context);
    }

    /**
     * Выполняет логирование с уровнем ALERT
     * @param string $message
     * @param array $context
     * @throws GrayLoggerException
     */
    public function alert($message, array $context = [])
    {
        $this->log(self::ALERT, $message, $context);
    }

    /**
     * Выполняет логирование с уровнем CRITICAL
     * @param string $message
     * @param array $context
     * @throws GrayLoggerException
     */
    public function critical($message, array $context = [])
    {
        $this->log(self::CRITICAL, $message, $context);
    }

    /**
     * Выполняет логирование с уровнем ERROR
     * @param string $message
     * @param array $context
     * @throws GrayLoggerException
     */
    public function error($message, array $context = [])
    {
        $this->log(self::ERROR, $message, $context);
    }

    /**
     * Выполняет логирование с уровнем WARNING
     * @param string $message
     * @param array $context
     * @throws GrayLoggerException
     */
    public function warning($message, array $context = [])
    {
        $this->log(self::WARNING, $message, $context);
    }

    /**
     * Выполняет логирование с уровнем NOTICE
     * @param string $message
     * @param array $context
     * @throws GrayLoggerException
     */
    public function notice($message, array $context = [])
    {
        $this->log(self::NOTICE, $message, $context);
    }

    /**
     * Выполняет логирование с уровнем INFO
     * @param string $message
     * @param array $context
     * @throws GrayLoggerException
     */
    public function info($message, array $context = [])
    {
        $this->log(self::INFO, $message, $context);
    }

    /**
     * Выполняет логирование с уровнем DEBUG
     * @param string $message
     * @param array $context
     * @throws GrayLoggerException
     */
    public function debug($message, array $context = [])
    {
        $this->log(self::DEBUG, $message, $context);
    }

    /**
     * Выполняет логирование с заданным уровнем
     * @param $level
     * @param string $message
     * @param array $context
     * @throws GrayLoggerException
     */
    public function log($level, $message, array $context = [])
    {
        try {
            $message = $this->buildMessage($level, $message, $context);
            $this->lastMessage = $message;
            $this->writeToSocket($message);
            $this->lastErrorMessage = null;
        } catch (GrayLoggerException $exception) {
            $this->lastErrorMessage = $exception->getMessage();
            $this->exceptionHandler($exception);
        }
    }

    /**
     * Возвращает имя хоста на котором запущен скрипт
     * @return string
     */
    private function getHostname()
    {
        $hostname = gethostname();
        if ($hostname === false) {
            $hostname = '';
        }
        return $hostname;
    }

    /**
     * Возвращает текущее время UNIX epoch с миллисекундами
     * @return float
     */
    private function getTimestamp()
    {
        return (float)sprintf('%.3f', microtime(true));
    }

    /**
     * Формирует и возвращает сообщение для отправки
     * @param $level
     * @param string $message
     * @param array $context
     * @return string
     * @throws GrayLoggerException
     */
    private function buildMessage($level, $message, array $context = [])
    {
        $level   = $this->prepareLogLevel($level);
        $context = $this->prepareContext($context);
        $message = $this->interpolate($message, $context);

        $data = [
            'version'       => $this->version,
            'host'          => $this->host,
            'timestamp'     => $this->getTimestamp(),
            'short_message' => $message,
            'level'         => $level
        ];

        $context = array_combine(array_map(static function ($key) {
            return '_' . $key;
        }, array_keys($context)), $context);

        $data = array_merge($data, $context);

        $message = json_encode($data);
        if ($message === false) {
            $errorMessage = $this->getJsonLastErrorMessage();
            throw new GrayLoggerException(
                sprintf("Can't json_encode() GELF payload message: %s", $errorMessage)
            );
        }
        $message .= "\0";

        return $message;
    }

    /**
     * Проверяет и форматирует значение уровня логирования
     * @param $logLevel
     * @return int
     * @throws GrayLoggerException
     */
    private function prepareLogLevel($logLevel)
    {
        $level = (int)$logLevel;
        if ($level < 0 || $level > 7) {
            throw new GrayLoggerException(sprintf("Invalid log level '%s'", $logLevel));
        }

        return $level;
    }

    /**
     * Проверяет и форматирует дополнительные поля в сообщении
     * @param array $context
     * @return array
     * @throws GrayLoggerException
     */
    private function prepareContext(array $context)
    {
        $context = array_merge($this->context, $context);

        foreach ($context as $key => $value) {
            // @link https://docs.graylog.org/en/3.3/pages/gelf.html#gelf-payload-specification
            if (!preg_match('/^[\w\.\-]+$/', $key) || $key === 'id') {
                throw new GrayLoggerException(sprintf("Invalid key '%s' in context", $key));
            }

            $type = gettype($value);
            switch ($type) {
                case 'string':
                case 'integer':
                case 'double':
                    break;
                case 'array':
                case 'boolean':
                    $value = json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                    if ($value === false) {
                        $errorMessage = $this->getJsonLastErrorMessage();
                        throw new GrayLoggerException(
                            sprintf("Can't json_encode() value for key '%s' in context: %s", $key, $errorMessage)
                        );
                    }
                    break;
                case 'object':
                    if (method_exists($value, '__toString')) {
                        $value = (string)$value;
                    } else {
                        $value = '[object (' . get_class($value) . ')]';
                    }
                    break;
                case 'NULL':
                    $value = 'NULL';
                    break;
                default:
                    $value = '[' . $type . ']';
                    break;
            }

            $context[$key] = $value;
        }

        return $context;
    }

    /**
     * Записывает сообщение в сокет
     * @param string $message
     * @throws GrayLoggerException
     */
    private function writeToSocket($message)
    {
        $socket = $this->getSocket();

        $string       = (string)$message;
        $stringLength = strlen($string);

        $written = 0;
        while ($written < $stringLength) {
            $bytes = @fwrite($socket, substr($string, $written));
            if ($bytes === false || $bytes === 0) {
                throw new GrayLoggerException("Can't fwrite() this message to socket: %s", $string);
            }
            $written += $bytes;
        }
    }

    /**
     * Возвращает сокет
     * @return resource
     * @throws GrayLoggerException
     */
    private function getSocket()
    {
        if (!is_resource($this->socket)) {
            $this->socket = $this->buildSocket();
        }

        return $this->socket;
    }

    /**
     * Создает новый сокет
     * @return resource
     * @throws GrayLoggerException
     */
    private function buildSocket()
    {
        $socketDescriptor = sprintf('%s://%s:%d', $this->scheme, $this->server, $this->port);

        $socket = @stream_socket_client($socketDescriptor, $errNo, $errStr, $this->connectTimeout);
        if ($socket === false) {
            throw new GrayLoggerException(
                sprintf(
                    "Can't stream_socket_client() for %s in %.1f seconds: %s (%s)",
                    $socketDescriptor,
                    $this->connectTimeout,
                    $errStr,
                    $errNo
                )
            );
        }

        return $socket;
    }

    /**
     * Обрабатывает перехваченное исключение
     * @param GrayLoggerException $exception
     * @throws GrayLoggerException
     */
    private function exceptionHandler($exception)
    {
        if ($this->throwExceptions) {
            throw $exception;
        }
    }

    /**
     * Закрывает сокет
     * @throws GrayLoggerException
     */
    private function closeSocket()
    {
        if (is_resource($this->socket)) {
            if (!fclose($this->socket)) {
                throw new GrayLoggerException("Can't fclose() socket");
            }
            unset($this->socket);
        }
    }

    /**
     * Подставляет значения из context в заполнители {key} внутри сообщения
     * @link https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md#12-message
     * @param mixed $message
     * @param array $context
     * @return string
     */
    private function interpolate($message, array $context = [])
    {
        $replace = array();
        foreach ($context as $key => $val) {
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        return strtr($message, $replace);
    }

    /**
     * Возвращает строку с сообщением об ошибке последнего вызова json_encode()
     * @link https://www.php.net/manual/en/function.json-last-error.php
     * @return string
     */
    private function getJsonLastErrorMessage()
    {
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return 'No errors';
            case JSON_ERROR_DEPTH:
                return 'Maximum stack depth exceeded';
            case JSON_ERROR_STATE_MISMATCH:
                return 'Underflow or the modes mismatch';
            case JSON_ERROR_CTRL_CHAR:
                return 'Unexpected control character found';
            case JSON_ERROR_SYNTAX:
                return 'Syntax error, malformed JSON';
            case JSON_ERROR_UTF8:
                return 'Malformed UTF-8 characters, possibly incorrectly encoded';
            default:
                return 'Unknown error';
        }
    }
}
