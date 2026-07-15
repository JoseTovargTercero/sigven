<?php

function loadEnv($filePath)
{
    if (!file_exists($filePath)) {
        return;
    }
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (strpos($line, '#') === 0 || strpos($line, '=') === false) {
            continue;
        }
        list($key, $value) = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

loadEnv(__DIR__ . '/../../../.env_sigven');

define('URL', $_ENV['APP_URL']);
define('USER', $_ENV['DB_USER']);
define('PASSWORD', $_ENV['DB_PASSWORD']);
define('HOST', $_ENV['DB_HOST']);
define('DB', $_ENV['DB_NAME']);
define('APIKEY', $_ENV['APIKEY']);
define('CHARSET', $_ENV['DB_CHARSET'] ?? 'utf8mb4');
