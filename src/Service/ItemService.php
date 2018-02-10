<?php

declare(strict_types=1);

namespace Api\Service;

use Api\Item\ItemQueryParameters;
use Api\Repository\ItemRepository;

class ItemService
{
    private $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function findByCriteria(ItemQueryParameters $itemQueryParameters): array
    {
        return $this->itemRepository->findByCriteria($itemQueryParameters);
    }

    public function addItem(string $name, int $amount): int
    {
        return $this->itemRepository->add($name, $amount);
    }
}
