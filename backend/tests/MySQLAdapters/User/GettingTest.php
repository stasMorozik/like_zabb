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
  protected static MySQLAdapters\DBFactory $db;
  protected static MySQLAdapters\User\Getting $getting_user_adapter;
  protected static $email = 'name@gmail.com';
  protected static $password = '12345';
  protected static $name = 'Joe';

  protected function tearDown(): void
  {
    DB::query("DELETE FROM user_role");
    DB::query("DELETE FROM users");
  }

  protected function setUp(): void
  {
    DB::startTransaction();

    $id_user = Uuid::uuid4()->toString();

    $role = DB::queryFirstRow("SELECT * FROM roles WHERE name= 'ADMIN'");

    DB::insert('users', [
      'id' => $id_user,
      'email' => self::$email,
      'created' => new DateTime(),
      'name' => self::$name,
      'password' => self::$password
    ]);

    DB::insert('user_role', [
      'user_id' => $id_user,
      'role_id' => $role['id']
    ]);

    DB::commit();
  }

  public static function setUpBeforeClass(): void
  {
    self::$db = Tests\MySQLAdapters\DBFactory::factory();
    self::$getting_user_adapter = new MySQLAdapters\User\Getting();
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
