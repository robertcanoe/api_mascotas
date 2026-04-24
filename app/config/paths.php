<?php

declare(strict_types=1);

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__, 2));
}

if (!defined('APP_PATH')) {
    define('APP_PATH', ROOT_PATH . '/app');
}

if (!defined('CONFIG_PATH')) {
    define('CONFIG_PATH', APP_PATH . '/config');
}

if (!defined('VIEWS_PATH')) {
    define('VIEWS_PATH', ROOT_PATH . '/views');
}

if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', ROOT_PATH . '/public');
}

if (!defined('LOGS_PATH')) {
    define('LOGS_PATH', ROOT_PATH . '/logs');
}

if (!defined('CACHE_PATH')) {
    define('CACHE_PATH', ROOT_PATH . '/cache');
}
