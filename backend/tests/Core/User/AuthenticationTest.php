<?php declare(strict_types=1);

namespace Tests\Core\User;

use PHPUnit\Framework\TestCase;
use Core;
use Tests;

class AuthenticationTest extends TestCase
{
  protected static $users = [];
  protected static $password_salt = 'some_secret';
  protected static $access_token_salt = 'some_secret?';
  protected static $refresh_token_salt = 'some_secret?';
  protected static $authentication_use_case;
  protected static $getting_user_adapter;
  protected static $email = 'name@gmail.com';
  protected static $password = '12345';

  protected function setUp(): void
  {
    self::$getting_user_adapter = new Tests\Core\User\Adapters\Getting(self::$users);

    self::$authentication_use_case = new Core\User\UseCases\Authentication(
      self::$password_salt,
      self::$access_token_salt,
      self::$refresh_token_salt,
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
      'password' => self::$password
    ]);

    self::$users[self::$email] = $user;
  }

  public function testAuthentication()
  {
    $maybe_session = self::$authentication_use_case->auth(['email' => self::$email, 'password' => self::$password]);

    $this->assertInstanceOf(
      Core\Session\Entity::class,
      $maybe_session
    );
  }

  public function testWrongPassword()
  {
    $maybe_session = self::$authentication_use_case->auth(['email' => self::$email, 'password' => '123']);

    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $maybe_session
    );
  }

  public function testInvalidEmail()
  {
    $maybe_session = self::$authentication_use_case->auth(['email' => 'nam1', 'password' => '123']);

    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $maybe_session
    );
  }

  public function testUserNotFound()
  {
    $maybe_session = self::$authentication_use_case->auth(['email' => 'name1@gmail.com', 'password' => '123']);

    $this->assertInstanceOf(
      Core\Common\Errors\Infrastructure::class,
      $maybe_session
    );
  }
}
