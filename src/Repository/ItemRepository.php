<?php

declare(strict_types=1);

namespace Api\Repository;

use Api\Item\ItemQueryParameters;
use Doctrine\DBAL\Connection;

class ItemRepository
{
    private $database;

    public function __construct(Connection $dbal)
    {
        $this->database = $dbal;
    }

    public function findByCriteria(ItemQueryParameters $itemQueryParameters): array
    {
        $items = $this->database->createQueryBuilder()
            ->select('*')
            ->from('items', 'i');
        if ($itemQueryParameters->getEquals() !== null) {
            $items->andWhere('i.amount = :equalsAmount')
                ->setParameter('equalsAmount', $itemQueryParameters->getEquals());
        }
        if ($itemQueryParameters->getGreater() !== null) {
            $items->andWhere('i.amount > :greaterAmount')
                ->setParameter('greaterAmount', $itemQueryParameters->getGreater());
        }

        return $items->execute()->fetchAll();
    }

    public function add(string $name, int $amount): void
    {
        $this->database->insert('items', [
            'name' => $name,
            'amount' => $amount,
        ]);
    }

    public function remove(int $id): void
    {
        $this->database->delete('items', [
            'id' => $id,
        ]);
    }
}
