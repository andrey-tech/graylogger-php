<?php

namespace Test\Functional;

use RuntimeException;

class Json
{
    /**
     * @param string $string
     * @return array
     * @throws RuntimeException
     */
    public static function decode($string)
    {
        $data = json_decode($string, true);
        if ($data === false || $data === null) {
            $errorMessage = self::jsonLastErrorMsg();
            throw new RuntimeException("Can't decode JSON ({$errorMessage}): " . $string);
        }
        return $data;
    }

    /**
     * @param mixed $data
     * @return string
     * @throws RuntimeException
     */
    public static function encode($data)
    {
        $json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            $errorMessage = self::jsonLastErrorMsg();
            throw new RuntimeException("Can't encode to JSON ({$errorMessage}): " . print_r($data, true));
        }
        return $json;
    }

    /**
     * @return string
     */
    public static function jsonLastErrorMsg()
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
