<?php

declare(strict_types=1);

namespace App\Core;

use MongoDB\Client;

class Database
{
    private static ?Client $connection = null;

    public static function getConnection(): Client
    {
        if (self::$connection === null) {
            $config = require dirname(__DIR__, 2) . '/config/database.php';

            $auth = '';
            if (!empty($config['username'])) {
                $auth = rawurlencode($config['username']) . ':' . rawurlencode($config['password'] ?? '') . '@';
            }

            $uri = sprintf(
                'mongodb://%s%s:%d/?appname=pi_cartrix',
                $auth,
                $config['host'],
                $config['port']
            );

            $uriOptions = ['connectTimeoutMS' => $config['connectTimeoutMS'] ?? 5000];
            if (!empty($config['replicaSet'])) {
                $uriOptions['replicaSet'] = $config['replicaSet'];
            }

            self::$connection = new Client($uri, $uriOptions);
        }

        return self::$connection;
    }

    public static function getDatabase(): \MongoDB\Database
    {
        $config = require dirname(__DIR__, 2) . '/config/database.php';

        return self::getConnection()->selectDatabase($config['database']);
    }
}