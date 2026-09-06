<?php

declare(strict_types=1);

const BASE_PATH = __DIR__ . '/../';

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    if (str_starts_with($class, $prefix)) {
        $relative = substr($class, strlen($prefix));
        $file = BASE_PATH . 'app/' . str_replace('\\', '/', $relative) . '.php';
        if (is_file($file)) {
            require $file;
        }
    }
});

require BASE_PATH . 'app/helpers/functions.php';

date_default_timezone_set((require BASE_PATH . 'config/app.php')['timezone'] ?? 'UTC');