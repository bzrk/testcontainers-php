<?php

declare(strict_types=1);

namespace Testcontainers\Tests\Integration;

use Testcontainers\Container\MariaDBContainer;

class MariaDBContainerTest extends ContainerTestCase
{
    public static function setUpBeforeClass(): void
    {
        self::$container = (new MariaDBContainer())
            ->withMariaDBDatabase('foo')
            ->withMariaDBUser('bar', 'baz')
            ->start();
    }

    public function testMySQLContainer(): void
    {
        $pdo = new \PDO(
            sprintf('mysql:host=%s;port=3306', self::$container->getAddress()),
            'bar',
            'baz',
        );

        $query = $pdo->query('SHOW databases');

        $this->assertInstanceOf(\PDOStatement::class, $query);

        $databases = $query->fetchAll(\PDO::FETCH_COLUMN);

        $this->assertContains('foo', $databases);
    }
}
