<?php declare(strict_types=1);

namespace Tests\MySQLAdapters\ConfirmationCode;

use PHPUnit\Framework\TestCase;
use Tests;
use DB;
use Core;
use MySQLAdapters;

class GettingTest extends TestCase
{
  protected static MySQLAdapters\DBFactory $db;
  protected static MySQLAdapters\ConfirmationCode\Getting $getting_code_adapter;
  protected static MySQLAdapters\ConfirmationCode\Creating $creating_code_adapter;
  protected static string $email = 'name@gmail.com';

  protected function tearDown(): void
  {
    DB::query("DELETE FROM confirmation_codes");
  }

  public static function setUpBeforeClass(): void
  {
    self::$db = Tests\MySQLAdapters\DBFactory::factory();
    self::$getting_code_adapter = new MySQLAdapters\ConfirmationCode\Getting();
    self::$creating_code_adapter = new MySQLAdapters\ConfirmationCode\Creating();
  }

  public function testGet(): void
  {
    $maybe_email = Core\Common\ValueObjects\Email::new(self::$email);
    $code = Core\ConfirmationCode\Entity::new($maybe_email);
    $maybe_true = self::$creating_code_adapter->change($code);

    if ($maybe_true === true) {
      $maybe_code = self::$getting_code_adapter->get(
        new MySQLAdapters\Common\Mappers\ValueObjects\Email(self::$email)
      );

      $this->assertInstanceOf(
        Core\ConfirmationCode\Entity::class,
        $maybe_code
      );
    }
  }

  public function testNotFound(): void
  {
    $maybe_code = self::$getting_code_adapter->get(
      new MySQLAdapters\Common\Mappers\ValueObjects\Email(self::$email)
    );

    $this->assertInstanceOf(
      Core\Common\Errors\InfraStructure::class,
      $maybe_code
    );
  }
}
