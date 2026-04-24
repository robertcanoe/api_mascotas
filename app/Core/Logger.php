<?php

declare(strict_types=1);

namespace App\Core;

class Logger
{
    public static function write(string $message, string $level = 'INFO'): void
    {
        $level = strtoupper($level);
        $date = date('Y-m-d H:i:s');
        $line = sprintf("[%s] [%s] %s%s", $date, $level, $message, PHP_EOL);

        $logDir = defined('LOGS_PATH') ? LOGS_PATH : dirname(__DIR__, 2) . '/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0775, true);
        }

        $logFile = $logDir . '/app-' . date('Y-m-d') . '.log';
        file_put_contents($logFile, $line, FILE_APPEND);
    }
}
