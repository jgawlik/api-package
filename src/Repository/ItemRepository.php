<?php

declare(strict_types=1);

namespace Api\Repository;

use Doctrine\DBAL\Connection;

class ItemRepository
{
    private $database;

    public function __construct(Connection $dbal)
    {
        $this->database = $dbal;
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

        return $items->execute()->fetchAll();
    }

    public function add(string $name, int $amount): int
    {
        $this->database->insert('items', [
            'name' => $name,
            'amount' => $amount,
        ]);

        return (int) $this->database->lastInsertId();
    }

    public function remove(int $id): void
    {
        $this->database->delete('items', [
            'id' => $id,
        ]);
    }
}
