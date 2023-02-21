<?php declare(strict_types=1);

namespace Tests\Core\Sensor;

use PHPUnit\Framework\TestCase;
use Core;

class EntityTest extends TestCase
{
  protected static $email = 'name@gmail.com';
  protected static $salt = 'some_secret';

  public function testNewSensor(): void
  {
    $maybe_email = Core\Common\ValueObjects\Email::new(['email' => self::$email]);
    $code = Core\ConfirmationCode\Entity::new(['email' => $maybe_email]);
    $account = Core\Account\Entity::new(['code' => $code]);
    $role = Core\Role\Entity::new(['name' => Core\Role\ValueObjects\Name::ADMIN]);

    $user = Core\User\Entity::new([
      'account' => $account,
      'role' => $role,
      'email' => $maybe_email,
      'salt' => self::$salt,
      'name' => 'Joe',
      'password' => '12345'
    ]);

    $maybe_sensor = Core\Sensor\Entity::new([
      'name' => 'EXAMPLE-891_98',
      'longitude' => 167.3,
      'latitude' => 64.6,
      'status' => Core\Sensor\ValueObjects\Status::NOT_AVAILABLE,
      'description' => 'Example',
      'owner' => $user
    ]);

    $this->assertInstanceOf(
      Core\Sensor\Entity::class,
      $maybe_sensor
    );
  }

  public function testInvalidName(): void
  {
    $maybe_email = Core\Common\ValueObjects\Email::new(['email' => self::$email]);
    $code = Core\ConfirmationCode\Entity::new(['email' => $maybe_email]);
    $account = Core\Account\Entity::new(['code' => $code]);
    $role = Core\Role\Entity::new(['name' => Core\Role\ValueObjects\Name::ADMIN]);

    $user = Core\User\Entity::new([
      'account' => $account,
      'role' => $role,
      'email' => $maybe_email,
      'salt' => self::$salt,
      'name' => 'Joe',
      'password' => '12345'
    ]);

    $maybe_sensor = Core\Sensor\Entity::new([
      'name' => 'EXAMPLE-891_98?',
      'longitude' => 167.3,
      'latitude' => 64.6,
      'status' => Core\Sensor\ValueObjects\Status::NOT_AVAILABLE,
      'description' => 'Example',
      'owner' => $user
    ]);

    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $maybe_sensor
    );
  }

  public function testUpdateStatus(): void
  {
    $maybe_email = Core\Common\ValueObjects\Email::new(['email' => self::$email]);
    $code = Core\ConfirmationCode\Entity::new(['email' => $maybe_email]);
    $account = Core\Account\Entity::new(['code' => $code]);
    $role = Core\Role\Entity::new(['name' => Core\Role\ValueObjects\Name::ADMIN]);

    $user = Core\User\Entity::new([
      'account' => $account,
      'role' => $role,
      'email' => $maybe_email,
      'salt' => self::$salt,
      'name' => 'Joe',
      'password' => '12345'
    ]);

    $sensor = Core\Sensor\Entity::new([
      'name' => 'EXAMPLE-891_98',
      'longitude' => 167.3,
      'latitude' => 64.6,
      'status' => Core\Sensor\ValueObjects\Status::NOT_AVAILABLE,
      'description' => 'Example',
      'owner' => $user
    ]);

    $maybe_true = $sensor->updtaeStatus([
      'owner' => $user,
      'status' => Core\Sensor\ValueObjects\Status::ACTIVE
    ]);

    $this->assertSame(
      true,
      $maybe_true
    );
  }

  public function testInvalidOwner(): void
  {
    $maybe_email = Core\Common\ValueObjects\Email::new(['email' => self::$email]);
    $code = Core\ConfirmationCode\Entity::new(['email' => $maybe_email]);
    $account = Core\Account\Entity::new(['code' => $code]);
    $role = Core\Role\Entity::new(['name' => Core\Role\ValueObjects\Name::ADMIN]);

    $user = Core\User\Entity::new([
      'account' => $account,
      'role' => $role,
      'email' => $maybe_email,
      'salt' => self::$salt,
      'name' => 'Joe',
      'password' => '12345'
    ]);

    $sensor = Core\Sensor\Entity::new([
      'name' => 'EXAMPLE-891_98',
      'longitude' => 167.3,
      'latitude' => 64.6,
      'status' => Core\Sensor\ValueObjects\Status::NOT_AVAILABLE,
      'description' => 'Example',
      'owner' => $user
    ]);

    $maybe_email = Core\Common\ValueObjects\Email::new(['email' => self::$email]);
    $code = Core\ConfirmationCode\Entity::new(['email' => $maybe_email]);
    $account = Core\Account\Entity::new(['code' => $code]);
    $role = Core\Role\Entity::new(['name' => Core\Role\ValueObjects\Name::ADMIN]);

    $user = Core\User\Entity::new([
      'account' => $account,
      'role' => $role,
      'email' => $maybe_email,
      'salt' => self::$salt,
      'name' => 'Joe',
      'password' => '12345'
    ]);

    $maybe_true = $sensor->updtaeStatus([
      'onwer' => $user,
      'status' => Core\Sensor\ValueObjects\Status::ACTIVE
    ]);

    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $maybe_true
    );
  }

  public function testInvalidRole(): void
  {
    $maybe_email = Core\Common\ValueObjects\Email::new(['email' => self::$email]);
    $code = Core\ConfirmationCode\Entity::new(['email' => $maybe_email]);
    $account = Core\Account\Entity::new(['code' => $code]);
    $role = Core\Role\Entity::new(['name' => Core\Role\ValueObjects\Name::USER]);

    $user = Core\User\Entity::new([
      'account' => $account,
      'role' => $role,
      'email' => $maybe_email,
      'salt' => self::$salt,
      'name' => 'Joe',
      'password' => '12345'
    ]);

    $maybe_sensor = Core\Sensor\Entity::new([
      'name' => 'EXAMPLE-891_98',
      'longitude' => 167.3,
      'latitude' => 64.6,
      'status' => Core\Sensor\ValueObjects\Status::NOT_AVAILABLE,
      'description' => 'Example',
      'owner' => $user
    ]);

    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $maybe_sensor
    );
  }
}
