<?php declare(strict_types=1);

namespace Tests\Core\Sensor;

use PHPUnit\Framework\TestCase;
use Core;
use Tests;

class CreatingTest extends TestCase
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

  protected $sensors = [];
  protected $creating_sensor_adapter;
  protected $creating_sensor_use_case;

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
    $user = Core\User\Entity::new($account, $role, $maybe_email, $this->password_salt, 'Joe', $this->password);

    $this->session = new Core\Session\Entity(
      $this->access_token_salt,
      $this->refresh_token_salt,
      $user->getId()
    );

    $this->users[$user->getId()] = $user;

    $this->creating_sensor_adapter = new Tests\Core\Sensor\Adapters\Creating($this->sensors);
    $this->creating_sensor_use_case = new Core\Sensor\UseCases\Creating(
      $this->creating_sensor_adapter,
      $this->authorization_use_case
    );
  }

  public function testCreate()
  {
    $maybe_true = $this->creating_sensor_use_case->create(
      'EXAMPLE-891_98',
      167.3,
      64.6,
      Core\Sensor\ValueObjects\Status::NOT_AVAILABLE,
      'Example',
      $this->session->access_token
    );

    $this->assertSame(
      true,
      $maybe_true
    );
  }

  public function testInvalidToken()
  {
    $maybe_true = $this->creating_sensor_use_case->create(
      'EXAMPLE-891_98',
      167.3,
      64.6,
      Core\Sensor\ValueObjects\Status::NOT_AVAILABLE,
      'Example',
      'Invalid token'
    );

    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $maybe_true
    );
  }

  public function testInvalidName()
  {
    $maybe_true = $this->creating_sensor_use_case->create(
      'EXAMPLE-891_98?',
      167.3,
      64.6,
      Core\Sensor\ValueObjects\Status::NOT_AVAILABLE,
      'Example',
      $this->session->access_token
    );

    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $maybe_true
    );
  }
}
