<?php

declare(strict_types=1);

namespace Api\Response;

interface ResponseInterface
{
    public function respond(): array;
}
