<?php declare(strict_types=1);

namespace Tests\Core\User;

use PHPUnit\Framework\TestCase;
use Core;

class EntityTest extends TestCase
{
  protected static $email = 'name@gmail.com';
  protected static $salt = 'some_secret';

  public function testNewUser(): void
  {
    $maybe_email = Core\Common\ValueObjects\Email::new(self::$email);
    $code = Core\ConfirmationCode\Entity::new($maybe_email);
    $account = Core\Account\Entity::new($code);
    $role = Core\Role\Entity::new(Core\Role\ValueObjects\Name::ADMIN);
    $user = Core\User\Entity::new($account, $role, self::$salt, 'Joe', '12345');

    $this->assertInstanceOf(
      Core\User\Entity::class,
      $user
    );
  }

  public function testInvalidName(): void
  {
    $maybe_email = Core\Common\ValueObjects\Email::new(self::$email);
    $code = Core\ConfirmationCode\Entity::new($maybe_email);
    $account = Core\Account\Entity::new($code);
    $role = Core\Role\Entity::new(Core\Role\ValueObjects\Name::ADMIN);
    $user = Core\User\Entity::new($account, $role, self::$salt, 'Joe12', '12345');

    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $user
    );
  }
}
