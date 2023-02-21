<?php declare(strict_types=1);

namespace Tests\MySQLAdapters\Account;

use PHPUnit\Framework\TestCase;
use Tests;
use DB;
use Core;
use MySQLAdapters;

class CreatingTest extends TestCase
{
  protected static MySQLAdapters\DBFactory $db;
  protected static MySQLAdapters\Account\Creating $creating_account_adapter;
  protected static MySQLAdapters\Role\Getting $getting_role_adapter;
  protected static $email = 'name@gmail.com';
  protected static $salt = 'some_secret';

  protected function tearDown(): void
  {
    DB::query("DELETE FROM user_role");
    DB::query("DELETE FROM user_account");
    DB::query("DELETE FROM accounts");
    DB::query("DELETE FROM users WHERE email = %s", self::$email);
  }

  public static function setUpBeforeClass(): void
  {
    self::$db = Tests\MySQLAdapters\DBFactory::factory();
    self::$creating_account_adapter = new MySQLAdapters\Account\Creating();
    self::$getting_role_adapter = new MySQLAdapters\Role\Getting();
  }

  public function testCreate(): void
  {
    $maybe_email = Core\Common\ValueObjects\Email::new(['email' => self::$email]);
    $code = Core\ConfirmationCode\Entity::new(['email' => $maybe_email]);
    $account = Core\Account\Entity::new(['code' => $code]);
    $role = self::$getting_role_adapter->get(
      new MySQLAdapters\Role\Mappers\ValueObjects\Name(
        Core\Role\ValueObjects\Name::ADMIN
      )
    );

    $user = Core\User\Entity::new([
      'account' => $account,
      'role' => $role,
      'email' => $maybe_email,
      'salt' => self::$salt,
      'name' => 'Joe',
      'password' => '12345'
    ]);

    $maybe_true = self::$creating_account_adapter->change(
      $user
    );

    $this->assertSame(
      true,
      $maybe_true
    );
  }

  public function testAlreadyExists(): void
  {
    $maybe_email = Core\Common\ValueObjects\Email::new(['email' => self::$email]);
    $code = Core\ConfirmationCode\Entity::new(['email' => $maybe_email]);
    $account = Core\Account\Entity::new(['code' => $code]);
    $role = self::$getting_role_adapter->get(
      new MySQLAdapters\Role\Mappers\ValueObjects\Name(
        Core\Role\ValueObjects\Name::ADMIN
      )
    );
    $user = Core\User\Entity::new([
      'account' => $account,
      'role' => $role,
      'email' => $maybe_email,
      'salt' => self::$salt,
      'name' => 'Joe',
      'password' => '12345'
    ]);

    $maybe_true = self::$creating_account_adapter->change(
      $user
    );

    $maybe_true = self::$creating_account_adapter->change(
      $user
    );

    $this->assertInstanceOf(
      Core\Common\Errors\InfraStructure::class,
      $maybe_true
    );
  }
}
