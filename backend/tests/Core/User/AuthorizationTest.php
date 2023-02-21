<?php declare(strict_types=1);

namespace Tests\Core\User;

use PHPUnit\Framework\TestCase;
use Core;
use Tests;

class AuthorizationTest extends TestCase
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
      'password' => self::$password
    ]);

    self::$session = Core\Session\Entity::new([
      'access_token_salt' => self::$access_token_salt,
      'refresh_token_salt' => self::$refresh_token_salt,
      'id' => $user->getId()
    ]);

    self::$users[$user->getId()] = $user;
  }

  public function testAuthorization()
  {
    $maybe_user = self::$authorization_use_case->auth(['access_token' => self::$session->getAccessToken()]);
    $this->assertInstanceOf(
      Core\User\Entity::class,
      $maybe_user
    );
  }

  public function testInvalidToken()
  {
    $maybe_user = self::$authorization_use_case->auth(['access_token' => 'invalid token']);
    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $maybe_user
    );
  }
}
