<?php declare(strict_types=1);

namespace Tests\MySQLAdapters\Role;

use PHPUnit\Framework\TestCase;
use Tests;
use Core;
use MySQLAdapters;

class GettingTest extends TestCase
{
  protected static MySQLAdapters\DBFactory $db;
  protected static MySQLAdapters\Role\Getting $getting_role_adapter;

  public static function setUpBeforeClass(): void
  {
    self::$db = Tests\MySQLAdapters\DBFactory::factory();
    self::$getting_role_adapter = new MySQLAdapters\Role\Getting();
  }

  public function testGet(): void
  {
    $maybe_role = self::$getting_role_adapter->get(
      new MySQLAdapters\Role\Mappers\ValueObjects\Name(
        Core\Role\ValueObjects\Name::ADMIN
      )
    );

    $this->assertInstanceOf(
      Core\Role\Entity::class,
      $maybe_role
    );
  }

  public function testNotFound(): void
  {
    $maybe_role = self::$getting_role_adapter->get(
      new MySQLAdapters\Role\Mappers\ValueObjects\Name(
        'TEST'
      )
    );

    $this->assertInstanceOf(
      Core\Common\Errors\InfraStructure::class,
      $maybe_role
    );
  }
}
