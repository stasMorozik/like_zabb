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

    $this->assertInstanceOf(
      Core\User\Entity::class,
      $user
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
      'name' => 'Joe12',
      'password' => '12345'
    ]);

    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $user
    );
  }
}
