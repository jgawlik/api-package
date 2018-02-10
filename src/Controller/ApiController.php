<?php

declare(strict_types=1);

namespace Api\Controller;

use Api\Item\ItemQueryParameters;
use Api\Response\ErrorResponse;
use Api\Service\ItemService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController
{
    private $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    public function getItems(Request $request): Response
    {
        try {
            $itemQueryParameters = new ItemQueryParameters($request->query->get('amount', []));
        } catch (\InvalidArgumentException $exception) {
            $errorResponse  = new ErrorResponse($exception->getMessage());
            return new JsonResponse($errorResponse->respond(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return new JsonResponse($this->itemService->findByCriteria($itemQueryParameters));
    }
}
