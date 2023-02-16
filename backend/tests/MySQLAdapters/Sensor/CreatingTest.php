<?php declare(strict_types=1);

namespace Tests\MySQLAdapters\Sensor;

use PHPUnit\Framework\TestCase;
use DateTime;
use DB;
use Ramsey\Uuid\Uuid;
use Tests;
use Core;
use MySQLAdapters;

class CreatingTest extends TestCase
{
  protected static $db;
  protected static $creating_sensor_adapter;
  protected static $email = 'name3@gmail.com';
  protected static $password = '12345';
  protected static $name = 'Joe';
  protected static $id_user;
  protected static $id_account;
  protected static $sensor;

  protected function tearDown(): void
  {
    DB::query("DELETE FROM user_role WHERE user_id = %s", self::$id_user);
    DB::query("DELETE FROM user_account WHERE user_id = %s", self::$id_user);
    DB::query("DELETE FROM users WHERE email = %s", self::$email);
    DB::query("DELETE FROM accounts WHERE email = %s", self::$email);
    DB::query("DELETE FROM account_sensor WHERE account_id = %s", self::$id_account);
    DB::query("DELETE FROM sensors");
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

    self::$sensor = new MySQLAdapters\Sensor\Mappers\Entity(
      Uuid::uuid4()->toString(),
      new DateTime(),
      new MySQLAdapters\Sensor\Mappers\ValueObjects\Name("TEST_98"),
      new MySQLAdapters\Sensor\Mappers\ValueObjects\Longitude(33.09),
      new MySQLAdapters\Sensor\Mappers\ValueObjects\Latitude(27.08),
      new MySQLAdapters\Sensor\Mappers\ValueObjects\Status(Core\Sensor\ValueObjects\Status::ACTIVE),
      'Test',
      new MySQLAdapters\User\Mappers\Entity(
        self::$id_user,
        new DateTime(),
        new MySQLAdapters\Common\Mappers\ValueObjects\Name(self::$name),
        new MySQLAdapters\Common\Mappers\ValueObjects\Email(self::$email),
        new MySQLAdapters\User\Mappers\ValueObjects\Password(self::$password),
        new MySQLAdapters\Role\Mappers\Entity(
          $role['id'],
          new MySQLAdapters\Role\Mappers\ValueObjects\Name($role['name']),
          new DateTime(),
        ),
        new MySQLAdapters\Account\Mappers\Entity(
          self::$id_account,
          new MySQLAdapters\Common\Mappers\ValueObjects\Email(self::$email),
          new DateTime()
        )
      )
    );
  }

  public static function setUpBeforeClass(): void
  {
    self::$db = Tests\MySQLAdapters\DBFactory::factory();
    self::$creating_sensor_adapter = new MySQLAdapters\Sensor\Creating();
    self::$id_user = Uuid::uuid4()->toString();
    self::$id_account = Uuid::uuid4()->toString();
  }

  public function testCreate(): void
  {
    $maybe_true = self::$creating_sensor_adapter->change(self::$sensor);

    $this->assertSame(
      true,
      $maybe_true
    );
  }
}
