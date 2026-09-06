<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use MongoDB\BSON\ObjectId;

class Usuario extends Model
{
    protected static string $collection = 'usuarios';

    public static function findByEmail(string $email): ?object
    {
        return static::findOne(['email' => $email]);
    }

    public static function findByCnpj(string $cnpj): ?object
    {
        return static::findOne(['cnpj' => $cnpj]);
    }

    public static function create(string $cnpj, string $email, string $password, string $name = ''): ObjectId
    {
        return static::insert([
            'cnpj' => trim($cnpj),
            'email' => strtolower(trim($email)),
            'name' => trim($name),
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'created_at' => now(),
        ]);
    }
}
