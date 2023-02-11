<?php declare(strict_types=1);

namespace Tests\Core\User;

use PHPUnit\Framework\TestCase;
use Core;
use Tests;

class AuthenticationTest extends TestCase
{
  protected $users = [];
  protected $password_salt = 'some_secret';
  protected $access_token_salt = 'some_secret?';
  protected $refresh_token_salt = 'some_secret?';
  protected $authentication_use_case;
  protected $getting_user_adapter;
  protected $email = 'name@gmail.com';
  protected $password = '12345';

  protected function setUp(): void
  {
    $this->getting_user_adapter = new Tests\Core\User\Adapters\Getting($this->users);

    $this->authentication_use_case = new Core\User\UseCases\Authentication(
      $this->password_salt,
      $this->access_token_salt,
      $this->refresh_token_salt,
      $this->getting_user_adapter
    );

    $maybe_email = Core\Common\ValueObjects\Email::new($this->email);
    $code = Core\ConfirmationCode\Entity::new($maybe_email);
    $account = Core\Account\Entity::new($code);
    $role = Core\Role\Entity::new(Core\Role\ValueObjects\Name::ADMIN);
    $user = Core\User\Entity::new($account, $role, $this->password_salt, 'Joe', $this->password);
    $this->users[$this->email] = $user;
  }

  public function testAuthentication()
  {
    $maybe_session = $this->authentication_use_case->auth($this->email, $this->password);

    $this->assertInstanceOf(
      Core\Session\Entity::class,
      $maybe_session
    );
  }

  public function testWrongPassword()
  {
    $maybe_session = $this->authentication_use_case->auth($this->email, '123');

    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $maybe_session
    );
  }

  public function testInvalidEmail()
  {
    $maybe_session = $this->authentication_use_case->auth('nam1', '123');

    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $maybe_session
    );
  }

  public function testUserNotFound()
  {
    $maybe_session = $this->authentication_use_case->auth('name1@gmail.com', '123');

    $this->assertInstanceOf(
      Core\Common\Errors\Infrastructure::class,
      $maybe_session
    );
  }
}
