<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Marca extends Model
{
    protected static string $collection = 'marcas';

    public static function getAll(): array
    {
        return static::collection()
            ->find([], ['sort' => ['nome' => 1]])
            ->toArray();
    }

    public static function getNames(): array
    {
        return array_map(fn (object $m) => (string) $m->nome, static::getAll());
    }
}
