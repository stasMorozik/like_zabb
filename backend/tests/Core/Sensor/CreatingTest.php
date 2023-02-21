<?php declare(strict_types=1);

namespace Tests\Core\Sensor;

use PHPUnit\Framework\TestCase;
use Core;
use Tests;

class CreatingTest extends TestCase
{
  protected static $users = [];
  protected static $password_salt = 'some_secret';
  protected static $access_token_salt = 'some_secret?';
  protected static $refresh_token_salt = 'some_secret?';
  protected static $authorization_use_case;
  protected static $getting_user_adapter;
  protected static $email = 'name@gmail.com';
  protected static $password = '12345';
  protected static $session;

  protected static $sensors = [];
  protected static $creating_sensor_adapter;
  protected static $creating_sensor_use_case;

  protected function setUp(): void
  {
    self::$getting_user_adapter = new Tests\Core\User\Adapters\GettingById(self::$users);

    self::$authorization_use_case = new Core\User\UseCases\Authorization(
      self::$access_token_salt,
      self::$getting_user_adapter
    );

    $maybe_email = Core\Common\ValueObjects\Email::new(['email' => self::$email]);
    $code = Core\ConfirmationCode\Entity::new(['email' => $maybe_email]);
    $account = Core\Account\Entity::new(['code' => $code]);
    $role = Core\Role\Entity::new(['name' => Core\Role\ValueObjects\Name::ADMIN]);

    $user = Core\User\Entity::new([
      'account' => $account,
      'role' => $role,
      'email' => $maybe_email,
      'salt' => self::$password_salt,
      'name' => 'Joe',
      'password' => '12345'
    ]);

    self::$session = Core\Session\Entity::new([
      'access_token_salt' => self::$access_token_salt,
      'refresh_token_salt' => self::$refresh_token_salt,
      'id' => $user->getId()
    ]);

    self::$users[$user->getId()] = $user;

    self::$creating_sensor_adapter = new Tests\Core\Sensor\Adapters\Creating(self::$sensors);
    self::$creating_sensor_use_case = new Core\Sensor\UseCases\Creating(
      self::$creating_sensor_adapter,
      self::$authorization_use_case
    );
  }

  public function testCreate()
  {
    $maybe_true = self::$creating_sensor_use_case->create([
      'name' => 'EXAMPLE-891_98',
      'longitude' => 167.3,
      'latitude' => 64.6,
      'status' => Core\Sensor\ValueObjects\Status::NOT_AVAILABLE,
      'description' => 'Example',
      'access_token' => self::$session->getAccessToken()
    ]);

    $this->assertSame(
      true,
      $maybe_true
    );
  }

  public function testInvalidToken()
  {
    $maybe_true = self::$creating_sensor_use_case->create([
      'name' => 'EXAMPLE-891_98',
      'longitude' => 167.3,
      'latitude' => 64.6,
      'status' => Core\Sensor\ValueObjects\Status::NOT_AVAILABLE,
      'description' => 'Example',
      'access_token' => 'Invalid token'
    ]);

    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $maybe_true
    );
  }

  public function testInvalidName()
  {
    $maybe_true = self::$creating_sensor_use_case->create([
      'name' => 'EXAMPLE-891_98?',
      'longitude' => 167.3,
      'latitude' => 64.6,
      'status' => Core\Sensor\ValueObjects\Status::NOT_AVAILABLE,
      'description' => 'Example',
      'access_token' => self::$session->getAccessToken()
    ]);

    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $maybe_true
    );
  }
}
