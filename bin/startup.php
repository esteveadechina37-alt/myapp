<?php
// This file runs BEFORE Laravel on every request
// It ensures the database config is correct

// Define constants early
define('LARAVEL_START', microtime(true));

// Force environment variables to be loaded
$env_file = '/app/.env';
if (file_exists($env_file)) {
    $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') === false || strpos($line, '#') === 0) continue;
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, '\'"');
        if (!getenv($key)) {
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
}

// FORCE MySQL as database
if (!getenv('DB_CONNECTION')) {
    putenv('DB_CONNECTION=mysql');
}

// Clear config cache on first request of deployment
static $cache_cleared = false;
if (!$cache_cleared && file_exists('/app/bootstrap/cache/config.php')) {
    // Check if config is for SQLite
    $config_content = file_get_contents('/app/bootstrap/cache/config.php');
    if (strpos($config_content, "'sqlite'") !== false && strpos($config_content, "'default' => 'sqlite'") !== false) {
        @unlink('/app/bootstrap/cache/config.php');
        @unlink('/app/bootstrap/cache/routes.php');
        @unlink('/app/bootstrap/cache/events.php');
        error_log('🔥 SQLite config detected! Cleared config cache.');
    }
    $cache_cleared = true;
}
