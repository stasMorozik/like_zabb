<?php declare(strict_types=1);

namespace Tests\Core\ConfirmationCode;

use PHPUnit\Framework\TestCase;
use Core;
use Tests;


class CreatingTest extends TestCase 
{
  protected $codes = [];
  protected $creating_code_adapter;
  protected $getting_code_adapter;
  protected $notifying_adapter;
  protected $creating_code_use_case;

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
  }

  public function testCreateCode(): void
  {

    $maybe_true = $this->creating_code_use_case->create('name@gmail.com');

    $this->assertSame(
      $maybe_true,
      true
    );
  }

  public function testInvalidEmail(): void
  {

    $maybe_true = $this->creating_code_use_case->create('name@gmail.');

    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $maybe_true
    );
  }
}