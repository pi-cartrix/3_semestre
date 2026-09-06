<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use MongoDB\BSON\ObjectId;

class Veiculo extends Model
{
    protected static string $collection = 'veiculos';

    public const STATUSES = [
        'disponivel' => 'Disponível',
        'em_revisao' => 'Em revisão',
        'danificado' => 'Danificado',
        'em_manutencao' => 'Em manutenção',
        'indisponivel' => 'Indisponível',
    ];

    public static function create(array $data): ObjectId
    {
        $data['created_at'] = now();
        $data['updated_at'] = now();

        return static::insert($data);
    }

    public static function atualizarVeiculo(string|ObjectId $id, array $data): bool
    {
        $data['updated_at'] = now();

        return static::update($id, $data);
    }

    public static function atualizarStatus(string|ObjectId $id, string $status): bool
    {
        return static::atualizarVeiculo($id, ['status' => $status]);
    }

    public static function findByPlate(string $plate): ?object
    {
        return static::findOne(['placa' => strtoupper(trim($plate))]);
    }

    public static function search(?string $query = null, ?string $status = null): array
    {
        $filter = [];

        if ($status !== null && $status !== '' && isset(self::STATUSES[$status])) {
            $filter['status'] = $status;
        }

        if ($query !== null && trim($query) !== '') {
            $pattern = new \MongoDB\BSON\Regex(preg_quote(trim($query), null), 'i');
            $filter['$or'] = [
                ['placa' => $pattern],
                ['marca' => $pattern],
                ['modelo' => $pattern],
                ['chassi' => $pattern],
            ];
        }

        return static::findMany($filter, ['sort' => ['created_at' => -1]]);
    }
}
