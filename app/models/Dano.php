<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use MongoDB\BSON\ObjectId;

class Dano extends Model
{
    protected static string $collection = 'danos';

    public const GRAVIDADES = [
        'baixa' => 'Baixa',
        'media' => 'Média',
        'alta' => 'Alta',
        'critica' => 'Crítica',
    ];

    public const LOCALIZACOES = [
        'dianteira' => 'Dianteira',
        'traseira' => 'Traseira',
        'lateral_esquerda' => 'Lateral esquerda',
        'lateral_direita' => 'Lateral direita',
        'teto' => 'Teto',
        'interior' => 'Interior',
        'outro' => 'Outro',
    ];

    public static function localizacoes(): array
    {
        return self::LOCALIZACOES;
    }

    public static function create(array $data): ObjectId
    {
        $data['created_at'] = now();

        return static::insert($data);
    }

    public static function porVeiculo(string|ObjectId $vehicleId): array
    {
        return static::findMany(
            ['vehicle_id' => new ObjectId((string) $vehicleId)],
            ['sort' => ['data' => -1]]
        );
    }
}
