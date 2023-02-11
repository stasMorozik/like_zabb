<?php declare(strict_types=1);

namespace Tests\MySQLAdapters\User;

use PHPUnit\Framework\TestCase;
use DateTime;
use DB;
use Ramsey\Uuid\Uuid;
use Tests;
use Core;
use MySQLAdapters;

class GettingByIdTest extends TestCase
{
  protected static $db;
  protected static $getting_user_adapter;
  protected static $email = 'name2@gmail.com';
  protected static $password = '12345';
  protected static $name = 'Joe';
  protected static $id;

  protected function tearDown(): void
  {
    DB::query("DELETE FROM user_role WHERE user_id = %s", self::$id);
    DB::query("DELETE FROM users WHERE email = %s", self::$email);
  }

  protected function setUp(): void
  {
    DB::startTransaction();

    $role = DB::queryFirstRow("SELECT * FROM roles WHERE name= 'ADMIN'");

    DB::insert('users', [
      'id' => self::$id,
      'email' => self::$email,
      'created' => new DateTime(),
      'name' => self::$name,
      'password' => self::$password
    ]);

    DB::insert('user_role', [
      'user_id' => self::$id,
      'role_id' => $role['id']
    ]);

    DB::commit();
  }

  public static function setUpBeforeClass(): void
  {
    self::$db = Tests\MySQLAdapters\DBFactory::factory();
    self::$getting_user_adapter = new MySQLAdapters\User\GettingById();
    self::$id = Uuid::uuid4()->toString();
  }

  public function testGet(): void
  {
    $maybe_user = self::$getting_user_adapter->get(
      self::$id
    );

    $this->assertInstanceOf(
      Core\User\Entity::class,
      $maybe_user
    );
  }

  public function testNotFound(): void
  {
    $maybe_user = self::$getting_user_adapter->get(
      Uuid::uuid4()->toString()
    );

    $this->assertInstanceOf(
      Core\Common\Errors\Infrastructure::class,
      $maybe_user
    );
  }
}
