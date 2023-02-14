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
    $maybe_email = Core\Common\ValueObjects\Email::new(self::$email);
    $code = Core\ConfirmationCode\Entity::new($maybe_email);
    $account = Core\Account\Entity::new($code);
    $role = Core\Role\Entity::new(Core\Role\ValueObjects\Name::ADMIN);
    $user = Core\User\Entity::new($account, $role, $maybe_email, self::$salt, 'Joe', '12345');

    $maybe_sensor = Core\Sensor\Entity::new('EXAMPLE-891_98', 167.3, 64.6, Core\Sensor\ValueObjects\Status::NOT_AVAILABLE, 'Example', $user);

    $this->assertInstanceOf(
      Core\Sensor\Entity::class,
      $maybe_sensor
    );
  }

  public function testInvalidName(): void
  {
    $maybe_email = Core\Common\ValueObjects\Email::new(self::$email);
    $code = Core\ConfirmationCode\Entity::new($maybe_email);
    $account = Core\Account\Entity::new($code);
    $role = Core\Role\Entity::new(Core\Role\ValueObjects\Name::ADMIN);
    $user = Core\User\Entity::new($account, $role, $maybe_email, self::$salt, 'Joe', '12345');

    $maybe_sensor = Core\Sensor\Entity::new('EXAMPLE-891_98?', 167.3, 64.6, Core\Sensor\ValueObjects\Status::NOT_AVAILABLE, 'Example', $user);

    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $maybe_sensor
    );
  }

  public function testUpdateStatus(): void
  {
    $maybe_email = Core\Common\ValueObjects\Email::new(self::$email);
    $code = Core\ConfirmationCode\Entity::new($maybe_email);
    $account = Core\Account\Entity::new($code);
    $role = Core\Role\Entity::new(Core\Role\ValueObjects\Name::ADMIN);
    $user = Core\User\Entity::new($account, $role, $maybe_email, self::$salt, 'Joe', '12345');

    $sensor = Core\Sensor\Entity::new('EXAMPLE-891_98', 167.3, 64.6, Core\Sensor\ValueObjects\Status::NOT_AVAILABLE, 'Example', $user);

    $maybe_true = $sensor->updtaeStatus(
      $user,
      Core\Sensor\ValueObjects\Status::ACTIVE
    );

    $this->assertSame(
      true,
      $maybe_true
    );
  }

  public function testInvalidOwner(): void
  {
    $maybe_email = Core\Common\ValueObjects\Email::new(self::$email);
    $code = Core\ConfirmationCode\Entity::new($maybe_email);
    $account = Core\Account\Entity::new($code);
    $role = Core\Role\Entity::new(Core\Role\ValueObjects\Name::ADMIN);
    $user = Core\User\Entity::new($account, $role, $maybe_email, self::$salt, 'Joe', '12345');

    $sensor = Core\Sensor\Entity::new('EXAMPLE-891_98', 167.3, 64.6, Core\Sensor\ValueObjects\Status::NOT_AVAILABLE, 'Example', $user);

    $maybe_email = Core\Common\ValueObjects\Email::new(self::$email);
    $code = Core\ConfirmationCode\Entity::new($maybe_email);
    $account = Core\Account\Entity::new($code);
    $role = Core\Role\Entity::new(Core\Role\ValueObjects\Name::ADMIN);
    $user = Core\User\Entity::new($account, $role, $maybe_email, self::$salt, 'Joe', '12345');

    $maybe_true = $sensor->updtaeStatus(
      $user,
      Core\Sensor\ValueObjects\Status::ACTIVE
    );

    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $maybe_true
    );
  }

  public function testInvalidRole(): void
  {
    $maybe_email = Core\Common\ValueObjects\Email::new(self::$email);
    $code = Core\ConfirmationCode\Entity::new($maybe_email);
    $account = Core\Account\Entity::new($code);
    $role = Core\Role\Entity::new(Core\Role\ValueObjects\Name::USER);
    $user = Core\User\Entity::new($account, $role, $maybe_email, self::$salt, 'Joe', '12345');

    $maybe_sensor = Core\Sensor\Entity::new('EXAMPLE-891_98', 167.3, 64.6, Core\Sensor\ValueObjects\Status::NOT_AVAILABLE, 'Example', $user);

    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $maybe_sensor
    );
  }
}
