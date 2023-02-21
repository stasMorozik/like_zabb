<?php declare(strict_types=1);

namespace Tests\MySQLAdapters\ConfirmationCode;

use PHPUnit\Framework\TestCase;
use Tests;
use DB;
use Core;
use MySQLAdapters;

class CreatingTest extends TestCase
{
  protected static MySQLAdapters\DBFactory $db;
  protected static MySQLAdapters\ConfirmationCode\Creating $creating_code_adapter;
  protected static string $email = 'name@gmail.com';

  protected function tearDown(): void
  {
    DB::query("DELETE FROM confirmation_codes");
  }

  public static function setUpBeforeClass(): void
  {
    self::$db = Tests\MySQLAdapters\DBFactory::factory();
    self::$creating_code_adapter = new MySQLAdapters\ConfirmationCode\Creating();
  }

  public function testCreate(): void
  {
    $maybe_email = Core\Common\ValueObjects\Email::new(['email' => self::$email]);
    $code = Core\ConfirmationCode\Entity::new(['email' => $maybe_email]);

    $maybe_true = self::$creating_code_adapter->change($code);
    $this->assertSame(
      true,
      $maybe_true
    );
  }
}
