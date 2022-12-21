<?php declare(strict_types=1);

namespace Tests\Core\ConfirmationCode;

use PHPUnit\Framework\TestCase;
use Core;
use Tests;


class ConfirmingTest extends TestCase 
{
  protected $codes = [];
  protected $email = 'name@gmail.com';

  protected $creating_code_adapter;
  protected $getting_code_adapter;
  protected $notifying_adapter;
  protected $creating_code_use_case;
  protected $confirming_code_use_case;

  protected function setUp(): void
  {
    $this->creating_code_adapter = new Tests\Core\ConfirmationCode\Adapters\Changing($this->codes);
    $this->getting_code_adapter = new Tests\Core\ConfirmationCode\Adapters\Getting($this->codes);
    $this->notifying_adapter = new Tests\Core\ConfirmationCode\Adapters\Notifying();
    
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
  }

  public function testConfirmCode(): void
  {
    $maybe_true = $this->confirming_code_use_case->confirm(
      $this->email,
      $this->codes[$this->email]->getCode()
    );

    $this->assertSame(
      $maybe_true,
      true
    );
  }

  public function testCodeNotFound(): void
  {
    $maybe_true = $this->confirming_code_use_case->confirm(
      'name1@gmail.com',
      $this->codes[$this->email]->getCode()
    );

    $this->assertInstanceOf(
      Core\Common\Errors\InfraStructure::class,
      $maybe_true
    );
  }

  public function testWrongCode(): void
  {
    $maybe_true = $this->confirming_code_use_case->confirm(
      $this->email,
      123
    );

    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $maybe_true
    );
  }
}