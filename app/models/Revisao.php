<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use MongoDB\BSON\ObjectId;

class Revisao extends Model
{
    protected static string $collection = 'revisoes';

    public const TIPOS = [
        'preventiva' => 'Preventiva',
        'corretiva' => 'Corretiva',
        'revisao_programada' => 'Revisão programada',
        'troca_pneus' => 'Troca de pneus',
        'troca_oleo' => 'Troca de óleo',
        'outra' => 'Outra',
    ];

    public static function create(array $data): ObjectId
    {
        $data['created_at'] = now();

        return static::insert($data);
    }

    public static function porVeiculo(string|ObjectId $vehicleId): array
    {
        return static::findMany(
            ['vehicle_id' => new ObjectId((string) $vehicleId)],
            ['sort' => ['data_revisao' => -1]]
        );
    }
}
