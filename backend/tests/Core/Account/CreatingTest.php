<?php declare(strict_types=1);

namespace Tests\Core\Account;

use PHPUnit\Framework\TestCase;
use Core;
use Tests;

class CreatingTest extends TestCase 
{
  protected $codes = [];
  protected $users = [];
  protected $roles = [];
  protected $accounts = [];
  protected $email = 'name@gmail.com';
  protected $password = '12345';
  protected $secret_key = 'some_secret';

  protected $creating_code_adapter;
  protected $getting_code_adapter;
  protected $notifying_adapter;
  protected $creating_code_use_case;
  protected $confirming_code_use_case;
  protected $getting_role_adapter;
  protected $creating_account_adapter;
  protected $creating_account_use_case;

  protected function setUp(): void
  {
    $this->creating_code_adapter = new Tests\Core\ConfirmationCode\Adapters\Changing($this->codes);
    $this->getting_code_adapter = new Tests\Core\ConfirmationCode\Adapters\Getting($this->codes);
    $this->notifying_adapter = new Tests\Core\ConfirmationCode\Adapters\Notifying();
    $this->getting_role_adapter = new Tests\Core\Role\Adapters\Getting($this->roles);
    
    $this->creating_code_use_case = new Core\ConfirmationCode\UseCases\Creating(
      $this->creating_code_adapter,
      $this->getting_code_adapter,
      $this->notifying_adapter
    );
    
    $this->confirming_code_use_case = new Core\ConfirmationCode\UseCases\Confirming(
      $this->creating_code_adapter,
      $this->getting_code_adapter
    );

    $this->creating_code_use_case->create($this->email);

    $this->confirming_code_use_case->confirm(
      $this->email,
      $this->codes[$this->email]->getCode()
    );

    $this->roles[Core\Role\ValueObjects\Name::ADMIN] = new Tests\Core\Role\Adapters\Mappers\MapperEntity(
      Core\Role\ValueObjects\Name::ADMIN
    );
    
    $this->creating_account_adapter = new Tests\Core\Account\Adapters\Changing($this->accounts, $this->users);

    $this->creating_account_use_case = new Core\Account\UseCases\Creating(
      $this->secret_key,
      $this->creating_account_adapter,
      $this->getting_code_adapter,
      $this->getting_role_adapter
    );
  }

  public function testCreateAccount(): void
  {
    $maybe_true = $this->creating_account_use_case->create($this->email, $this->password, 'Name');

    $this->assertSame(
      true,
      $maybe_true
    );
  }

  public function testInvalidEamil(): void
  {
    $maybe_true = $this->creating_account_use_case->create('name@gmail.', $this->password, 'Name');

    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $maybe_true
    );
  }

  public function testCodeNotFound(): void
  {
    $maybe_true = $this->creating_account_use_case->create('name1@gmail.com', $this->password, 'Name');

    $this->assertInstanceOf(
      Core\Common\Errors\InfraStructure::class,
      $maybe_true
    );
  }

  public function testInvalidName(): void
  {
    $maybe_true = $this->creating_account_use_case->create($this->email, $this->password, 'Name1');

    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $maybe_true
    );
  }
}