<?php

namespace Advent;

class Logger
{
    CONST LEVELS = array('debug'=>0, 'log'=>1, 'warn'=>2, 'error'=>3);

    public static function debug($message) {
        self::doLogging('debug', $message);
    }

    public static function log($message) {
        self::doLogging('log', $message);
    }

    public static function warn($message) {
        self::doLogging('warn', $message);
    }

    public static function error($message) {
        self::doLogging('error', $message);
    }

    private static function doLogging($level, $message) {
        if (self::checkLevel($level)) {
            echo "$message\n";
        }
    }

    private static function checkLevel($level) {
        $ENV_LEVEL = (isset($_SERVER['LOG_LEVEL'])) ? (int) $_SERVER['LOG_LEVEL'] : 1;
        return $ENV_LEVEL <= self::LEVELS[$level];
    }
}