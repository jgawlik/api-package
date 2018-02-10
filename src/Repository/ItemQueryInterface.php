<?php

declare(strict_types=1);

namespace Api\Repository;

interface ItemQueryInterface
{
    public function getGreater(): ?int;

    public function getEquals(): ?int;
}