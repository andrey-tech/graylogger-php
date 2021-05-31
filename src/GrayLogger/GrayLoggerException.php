<?php

/**
 * Обработчик исключений в классе GrayLogger
 *
 * @author    andrey-tech
 * @copyright 2021 andrey-tech
 * @see       https://github.com/andrey-tech/graylogger-php
 * @license   MIT
 *
 * @version 1.0.0
 *
 * v1.0.0 (30.05.2021) Первый релиз
 *
 */

namespace GrayLogger;

use Exception;

class GrayLoggerException extends Exception
{
    /**
     * Добавляет идентификационную строку в сообщение об исключении
     *
     * @param string         $message  Сообщение об исключении
     * @param int            $code     Код исключения
     * @param Exception|null $previous Предыдущее исключение
     */
    public function __construct($message = '', $code = 0, $previous = null)
    {
        parent::__construct("GrayLogger: " . $message, $code, $previous);
    }
}
