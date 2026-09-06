<?php

declare(strict_types=1);

namespace App\Core;

use MongoDB\BSON\ObjectId;
use MongoDB\Collection;

abstract class Model
{
    protected static string $collection;

    protected static function collection(): Collection
    {
        return Database::getDatabase()->selectCollection(static::$collection);
    }

    public static function all(): array
    {
        return static::collection()->find()->toArray();
    }

    public static function find(string|ObjectId $id): ?object
    {
        return static::collection()->findOne(['_id' => new ObjectId((string) $id)]);
    }

    public static function findOne(array $filter, array $options = []): ?object
    {
        return static::collection()->findOne($filter, $options);
    }

    public static function findMany(array $filter, array $options = []): array
    {
        return static::collection()->find($filter, $options)->toArray();
    }

    public static function insert(array $data): ObjectId
    {
        $result = static::collection()->insertOne($data);

        return $result->getInsertedId();
    }

    public static function update(string|ObjectId $id, array $data): bool
    {
        $result = static::collection()->updateOne(
            ['_id' => new ObjectId((string) $id)],
            ['$set' => $data]
        );

        return $result->getModifiedCount() > 0 || $result->getUpsertedCount() > 0;
    }

    public static function delete(string|ObjectId $id): bool
    {
        $result = static::collection()->deleteOne(['_id' => new ObjectId((string) $id)]);

        return $result->getDeletedCount() > 0;
    }
}