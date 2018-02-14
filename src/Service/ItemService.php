<?php

declare(strict_types=1);

namespace Api\Service;

use Api\Repository\ItemQueryInterface;
use Api\Repository\ItemRepository;

class ItemService
{
    private $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function getItem(int $itemId): array
    {
        return $this->itemRepository->get($itemId);
    }

    public function findByCriteria(ItemQueryInterface $itemQueryParameters): array
    {
        return $this->itemRepository->findByCriteria($itemQueryParameters);
    }

    public function addItem(string $name, int $amount): int
    {
        return $this->itemRepository->add($name, $amount);
    }

    public function updateItem(string $name, int $amount, int $id): void
    {
        $this->itemRepository->update($name, $amount, $id);
    }

    public function removeItem(int $itemId): void
    {
        $this->itemRepository->remove($itemId);
    }
}
