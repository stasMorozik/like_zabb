<?php declare(strict_types=1);

namespace Tests\MySQLAdapters\User;

use PHPUnit\Framework\TestCase;
use DateTime;
use DB;
use Ramsey\Uuid\Uuid;
use Tests;
use Core;
use MySQLAdapters;

class GettingTest extends TestCase
{
  protected static $db;
  protected static $getting_user_adapter;
  protected static $email = 'name3@gmail.com';
  protected static $password = '12345';
  protected static $name = 'Joe';
  protected static $id_user;
  protected static $id_account;

  protected function tearDown(): void
  {
    DB::query("DELETE FROM user_role WHERE user_id = %s", self::$id_user);
    DB::query("DELETE FROM user_account WHERE user_id = %s", self::$id_user);
    DB::query("DELETE FROM users WHERE email = %s", self::$email);
    DB::query("DELETE FROM accounts WHERE email = %s", self::$email);
  }

  protected function setUp(): void
  {
    DB::startTransaction();

    $role = DB::queryFirstRow("SELECT * FROM roles WHERE name= 'ADMIN'");

    DB::insert('accounts', [
      'id' => self::$id_account,
      'email' => self::$email,
      'created' => new DateTime()
    ]);

    DB::insert('users', [
      'id' => self::$id_user,
      'email' => self::$email,
      'created' => new DateTime(),
      'name' => self::$name,
      'password' => self::$password
    ]);

    DB::insert('user_account', [
      'user_id' => self::$id_user,
      'account_id' => self::$id_account
    ]);

    DB::insert('user_role', [
      'user_id' => self::$id_user,
      'role_id' => $role['id']
    ]);

    DB::commit();
  }

  public static function setUpBeforeClass(): void
  {
    self::$db = Tests\MySQLAdapters\DBFactory::factory();
    self::$getting_user_adapter = new MySQLAdapters\User\Getting();
    self::$id_user = Uuid::uuid4()->toString();
    self::$id_account = Uuid::uuid4()->toString();
  }

  public function testGet(): void
  {
    $maybe_user = self::$getting_user_adapter->get(
      new MySQLAdapters\Common\Mappers\ValueObjects\Email(
        self::$email
      )
    );

    $this->assertInstanceOf(
      Core\User\Entity::class,
      $maybe_user
    );
  }

  public function testNotFound(): void
  {
    $maybe_user = self::$getting_user_adapter->get(
      new MySQLAdapters\Common\Mappers\ValueObjects\Email(
        'name1@gmail.com'
      )
    );

    $this->assertInstanceOf(
      Core\Common\Errors\Infrastructure::class,
      $maybe_user
    );
  }
}
