<?php

declare(strict_types=1);

namespace Api\Tests\Repository;

use Api\Repository\ItemQueryInterface;
use Api\Repository\ItemRepository;
use Api\Tests\DatabaseTestCase;
use PHPUnit\DbUnit\DataSet\YamlDataSet;

class ItemRepositoryTest extends DatabaseTestCase
{
    private $itemQuery;

    public function setUp()
    {
        parent::setUp();
        $this->itemQuery = $this->prophesize(ItemQueryInterface::class);
    }

    /**
     * @test
     */
    public function itReturnsOneItem()
    {
        $itemRepository = new ItemRepository($this->getDoctrineDbalConnection());
        $result = $itemRepository->get(1);
        $this->assertEquals(['id' => "1", 'name' => "Produkt 1", 'amount' => "4",], $result);
    }

    /**
     * @test
     */
    public function itReturnsEmptyArrayWhenQueryNotExistingItem()
    {
        $itemRepository = new ItemRepository($this->getDoctrineDbalConnection());
        $result = $itemRepository->get(14545);
        $this->assertEquals([], $result);
    }

    /**
     * @test
     * @dataProvider itemCollectionDataProvider
     */
    public function itReturnsItemCollection(?int $greater, ?int $equals, array $expectedResult): void
    {
        $itemRepository = new ItemRepository($this->getDoctrineDbalConnection());
        $this->itemQuery->getGreater()->willReturn($greater);
        $this->itemQuery->getEquals()->willReturn($equals);
        $result = $itemRepository->findByCriteria($this->itemQuery->reveal());
        $this->assertEquals($result, $expectedResult);
    }

    /**
     * @test
     */
    public function itAddsItem(): void
    {
        $itemRepository = new ItemRepository($this->getDoctrineDbalConnection());
        $insertedItemId = $itemRepository->add('Product 10', 18);
        $recordsInDatabase = $this->getRecordsFromDatabase();
        $this->assertEquals(6, $insertedItemId);
        $this->assertCount(6, $recordsInDatabase);
    }

    /**
     * @test
     */
    public function itUpdateItem(): void
    {
        $itemRepository = new ItemRepository($this->getDoctrineDbalConnection());
        $itemRepository->update('New Product Name', 9, 1);
        $recordsInDatabase = $this->getRecordsFromDatabase();
        $this->assertEquals(["id" => "1", "name" => "New Product Name", "amount" => "9"], $recordsInDatabase[0]);
    }

    /**
     * @test
     */
    public function itRemovesItem(): void
    {
        $itemRepository = new ItemRepository($this->getDoctrineDbalConnection());
        $result = $itemRepository->remove(1);
        $recordsInDatabase = $this->getRecordsFromDatabase();
        $this->assertEquals(4, count($recordsInDatabase));
    }

    public function itemCollectionDataProvider(): array
    {
        return [
            [
                null,
                null,
                [
                    [
                        "id" => "1",
                        "name" => "Produkt 1",
                        "amount" => "4"
                    ],
                    [
                        "id" => "2",
                        "name" => "Produkt 2",
                        "amount" => "12"
                    ],
                    [
                        "id" => "3",
                        "name" => "Produkt 5",
                        "amount" => "0"
                    ],
                    [
                        "id" => "4",
                        "name" => "Produkt 7",
                        "amount" => "6"
                    ],
                    [
                        "id" => "5",
                        "name" => "Produkt 8",
                        "amount" => "2"
                    ]
                ]
            ],
            [
                0,
                null,
                [
                    [
                        "id" => "1",
                        "name" => "Produkt 1",
                        "amount" => "4"
                    ],
                    [
                        "id" => "2",
                        "name" => "Produkt 2",
                        "amount" => "12"
                    ],
                    [
                        "id" => "4",
                        "name" => "Produkt 7",
                        "amount" => "6"
                    ],
                    [
                        "id" => "5",
                        "name" => "Produkt 8",
                        "amount" => "2"
                    ]
                ]
            ],
            [
                5,
                null,
                [
                    [
                        "id" => "2",
                        "name" => "Produkt 2",
                        "amount" => "12"
                    ],
                    [
                        "id" => "4",
                        "name" => "Produkt 7",
                        "amount" => "6"
                    ],
                ]
            ],
            [null, 0, [["id" => "3", "name" => "Produkt 5", "amount" => "0"],]],
        ];
    }

    public function getDataSet()
    {
        return new YamlDataSet(dirname(__FILE__) . "/../fixtures/items.yaml");
    }


    private function getRecordsFromDatabase(): array
    {
        return $this->getDoctrineDbalConnection()->createQueryBuilder()
            ->select('i.*')
            ->from('items', 'i')
            ->execute()
            ->fetchAll();
    }
}
