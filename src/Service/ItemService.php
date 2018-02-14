<?php

declare(strict_types=1);

namespace Api\Service;

use Api\Exception\ItemNotFoundException;
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
        $result = $this->itemRepository->get($itemId);
        if (empty($result)) {
            throw new ItemNotFoundException($itemId);
        }

        return $result;
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
