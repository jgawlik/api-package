<?php

declare(strict_types=1);

namespace Api\Repository;

use Doctrine\DBAL\Connection;
use PDO;

class ItemRepository
{
    private $database;

    public function __construct(Connection $dbal)
    {
        $this->database = $dbal;
    }

    public function get(int $itemId): array
    {
        $items = $this->database->createQueryBuilder()
            ->select('i.*')
            ->from('items', 'i')
            ->where('i.id = :id')
            ->setParameter('id', $itemId);
        $result = $items->execute()->fetch();
        if (is_bool($result)) {
            return [];
        }

        return $result;
    }

    public function findByCriteria(ItemQueryInterface $itemQuery): array
    {
        $items = $this->database->createQueryBuilder()
            ->select('*')
            ->from('items', 'i');
        if ($itemQuery->getEquals() !== null) {
            $items->andWhere('i.amount = :equalsAmount')
                ->setParameter('equalsAmount', $itemQuery->getEquals());
        }
        if ($itemQuery->getGreater() !== null) {
            $items->andWhere('i.amount > :greaterAmount')
                ->setParameter('greaterAmount', $itemQuery->getGreater());
        }

        return $items->execute()->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add(string $name, int $amount): int
    {
        $this->database->insert('items', [
            'name' => $name,
            'amount' => $amount,
        ]);

        return (int) $this->database->lastInsertId();
    }

    public function update(string $name, int $amount, int $id): void
    {
        $this->database->update(
            'items',
            ['name' => $name, 'amount' => $amount],
            ['id' => $id]
        );
    }

    public function remove(int $id): void
    {
        $this->database->delete('items', [
            'id' => $id,
        ]);
    }
}
