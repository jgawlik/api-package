<?php

declare(strict_types=1);

namespace Api\Exception;

class ItemNotFoundException extends \Exception
{
    public function __construct(int $itemId)
    {
        parent::__construct("Nie znaleziono produktu o id {$itemId}");
    }
}