<?php declare(strict_types=1);

namespace Tests\Core\Account;

use PHPUnit\Framework\TestCase;
use Core;
use Tests;

class CreatingTest extends TestCase
{
  protected static $codes = [];
  protected static $users = [];
  protected static $roles = [];
  protected static $accounts = [];
  protected static $email = 'name@gmail.com';
  protected static $password = '12345';
  protected static $secret_key = 'some_secret';

  protected static $creating_code_adapter;
  protected static $getting_code_adapter;
  protected static $notifying_adapter;
  protected static $creating_code_use_case;
  protected static $confirming_code_use_case;
  protected static $getting_role_adapter;
  protected static $creating_account_adapter;
  protected static $creating_account_use_case;

  protected function setUp(): void
  {
    self::$creating_code_adapter = new Tests\Core\ConfirmationCode\Adapters\Changing(self::$codes);
    self::$getting_code_adapter = new Tests\Core\ConfirmationCode\Adapters\Getting(self::$codes);
    self::$notifying_adapter = new Tests\Core\ConfirmationCode\Adapters\Notifying();
    self::$getting_role_adapter = new Tests\Core\Role\Adapters\Getting(self::$roles);

    self::$creating_code_use_case = new Core\ConfirmationCode\UseCases\Creating(
      self::$creating_code_adapter,
      self::$getting_code_adapter,
      self::$notifying_adapter
    );

    self::$confirming_code_use_case = new Core\ConfirmationCode\UseCases\Confirming(
      self::$creating_code_adapter,
      self::$getting_code_adapter
    );

    self::$creating_code_use_case->create(['email' => self::$email]);

    self::$confirming_code_use_case->confirm([
      'email' => self::$email,
      'code' => self::$codes[self::$email]->getCode()
    ]);

    self::$roles[Core\Role\ValueObjects\Name::SUPER] = new Tests\Core\Role\Adapters\Mappers\MapperEntity(
      Core\Role\ValueObjects\Name::SUPER
    );

    self::$creating_account_adapter = new Tests\Core\Account\Adapters\Changing(self::$accounts, self::$users);

    self::$creating_account_use_case = new Core\Account\UseCases\Creating(
      self::$secret_key,
      self::$creating_account_adapter,
      self::$getting_code_adapter,
      self::$getting_role_adapter
    );
  }

  public function testCreateAccount(): void
  {
    $maybe_true = self::$creating_account_use_case->create([
      'email' => self::$email,
      'password' => self::$password,
      'name' => 'Name'
    ]);

    $this->assertSame(
      true,
      $maybe_true
    );
  }

  public function testInvalidEamil(): void
  {
    $maybe_true = self::$creating_account_use_case->create([
      'email' => 'name@gmail.',
      'password' => self::$password,
      'name' => 'Name'
    ]);

    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $maybe_true
    );
  }

  public function testCodeNotFound(): void
  {
    $maybe_true = self::$creating_account_use_case->create([
      'email' => 'name1@gmail.com',
      'password' => self::$password,
      'name' => 'Name'
    ]);

    $this->assertInstanceOf(
      Core\Common\Errors\InfraStructure::class,
      $maybe_true
    );
  }

  public function testInvalidName(): void
  {
    $maybe_true = self::$creating_account_use_case->create([
      'email' => self::$email,
      'password' => self::$password,
      'name' => 'Name1'
    ]);

    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $maybe_true
    );
  }
}
