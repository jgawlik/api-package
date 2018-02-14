<?php

declare(strict_types=1);

namespace Api\Tests;

use Api\Repository\ItemQueryInterface;
use Api\Repository\ItemRepository;
use Api\Service\ItemService;
use PHPUnit\Framework\TestCase;

class ItemServiceTest extends TestCase
{
    private $itemRepository;
    private $itemQueryParameters;

    public function setUp(): void
    {
        $this->itemRepository = $this->prophesize(ItemRepository::class);
        $this->itemQueryParameters = $this->prophesize(ItemQueryInterface::class);
    }

    public function tearDown(): void
    {
        $this->itemRepository = null;
    }

    /**
     * @test
     * @expectedException \Api\Exception\ItemNotFoundException
     */
    public function itThrowsExceptionWhenItemNotFound(): void
    {
        $this->itemRepository->get(15)->shouldBeCalled();
        $this->itemRepository->get(15)->willReturn([]);
        $result = $this->prepareService()->getItem(15);
    }

    /**
     * @test
     */
    public function itReturnsItem(): void
    {
        $this->itemRepository->get(1)->shouldBeCalled();
        $this->itemRepository->get(1)->willReturn($this->getItems()[0]);
        $result = $this->prepareService()->getItem(1);
        $this->assertEquals($result, $this->getItems()[0]);
    }

    /**
     * @test
     */
    public function itDoesntReturnAnythingWhileSuccessfullyUpdateItem(): void
    {
        $this->itemRepository->update('Product 1', 10, 1)->shouldBeCalled();
        $nullIsOk = $this->prepareService()->updateItem('Product 1', 10, 1);
        $this->assertNull($nullIsOk);
    }

    /**
     * @test
     */
    public function itDoesntReturnAnythingWhileSuccessfullyRemoveItem(): void
    {
        $this->itemRepository->remove(1)->shouldBeCalled();
        $nullIsOk = $this->prepareService()->removeItem(1);
        $this->assertNull($nullIsOk);
    }

    /**
     * @test
     */
    public function itReturnsItemIdOnSucessfullyAddedItem(): void
    {
        $this->itemRepository->add('Product 56', 12)->shouldBeCalled();
        $this->itemRepository->add('Product 56', 12)->willReturn(50);
        $this->assertEquals(50, $this->prepareService()->addItem('Product 56', 12));
    }

    /**
     * @test
     */
    public function itReturnsItemCollection(): void
    {
        $this->itemRepository->findByCriteria($this->itemQueryParameters->reveal())->shouldBeCalled();
        $this->itemRepository->findByCriteria($this->itemQueryParameters->reveal())->willReturn($this->getItems());
        $result = $this->prepareService()->findByCriteria($this->itemQueryParameters->reveal());
        $this->assertEquals($result, $this->getItems());
        $this->assertEquals(count($result), 2);
    }

    private function prepareService(): ItemService
    {
        return new ItemService($this->itemRepository->reveal());
    }

    private function getItems(): array
    {
        return [
            [
                "id" => "1",
                "name" => "Produkt 1",
                "amount" => "4"
            ],
            [
                "id" => "2",
                "name" => "Produkt 2",
                "amount" => "16"
            ]
        ];
    }
}
