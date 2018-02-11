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
}
