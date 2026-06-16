<?php
/**
 * Класс для логирования
 */

class Logger
{
    private static $logDir = __DIR__ . '/../storage/logs/';
    private static $levels = ['debug', 'info', 'warning', 'error', 'critical'];

    public static function log($message, $level = 'info')
    {
        if (!in_array($level, self::$levels)) {
            $level = 'info';
        }

        $date = date('Y-m-d');
        $time = date('H:i:s');
        $logFile = self::$logDir . $date . '.log';

        $logMessage = sprintf(
            "[%s] [%s] %s\n",
            $time,
            strtoupper($level),
            $message
        );

        error_log($logMessage, 3, $logFile);
    }

    public static function debug($message)
    {
        self::log($message, 'debug');
    }

    public static function info($message)
    {
        self::log($message, 'info');
    }

    public static function warning($message)
    {
        self::log($message, 'warning');
    }

    public static function error($message)
    {
        self::log($message, 'error');
    }

    public static function critical($message)
    {
        self::log($message, 'critical');
    }
}
