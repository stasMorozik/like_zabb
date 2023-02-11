<?php declare(strict_types=1);

namespace Tests\Core\User;

use PHPUnit\Framework\TestCase;
use Core;
use Tests;

class AuthorizationTest extends TestCase
{
  protected $users = [];
  protected $password_salt = 'some_secret';
  protected $access_token_salt = 'some_secret?';
  protected $refresh_token_salt = 'some_secret?';
  protected $authorization_use_case;
  protected $getting_user_adapter;
  protected $email = 'name@gmail.com';
  protected $password = '12345';
  protected $session;

  protected function setUp(): void
  {
    $this->getting_user_adapter = new Tests\Core\User\Adapters\GettingById($this->users);

    $this->authorization_use_case = new Core\User\UseCases\Authorization(
      $this->access_token_salt,
      $this->getting_user_adapter
    );

    $maybe_email = Core\Common\ValueObjects\Email::new($this->email);
    $code = Core\ConfirmationCode\Entity::new($maybe_email);
    $account = Core\Account\Entity::new($code);
    $role = Core\Role\Entity::new(Core\Role\ValueObjects\Name::ADMIN);
    $user = Core\User\Entity::new($account, $role, $this->password_salt, 'Joe', $this->password);

    $this->session = new Core\Session\Entity(
      $this->access_token_salt,
      $this->refresh_token_salt,
      $user->getId()
    );

    $this->users[$user->getId()] = $user;
  }

  public function testAuthorization()
  {
    $maybe_user = $this->authorization_use_case->auth($this->session->access_token);
    $this->assertInstanceOf(
      Core\User\Entity::class,
      $maybe_user
    );
  }

  public function testInvalidToken()
  {
    $maybe_user = $this->authorization_use_case->auth('invalid token');
    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $maybe_user
    );
  }
}
