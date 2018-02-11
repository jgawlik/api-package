<?php

declare(strict_types=1);

namespace Api\Tests;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

abstract class DatabaseTestCase extends TestCase
{
    use TestCaseTrait;

    private $conn = null;
    private $dbalConnection = null;
    static private $pdo = null;

    final public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new \PDO('sqlite::memory:');
                self::$pdo->exec(file_get_contents(dirname(__FILE__) . "/schema/items.sql"));
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, ':memory:');
        }

        return $this->conn;
    }

    protected function getDoctrineDbalConnection(): Connection
    {
        if ($this->dbalConnection === null) {
            $this->dbalConnection = DriverManager::getConnection(['pdo' => $this->getConnection()->getConnection()]);
        }

        return $this->dbalConnection;
    }
}
