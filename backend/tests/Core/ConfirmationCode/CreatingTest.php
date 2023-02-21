<?php declare(strict_types=1);

namespace Tests\Core\ConfirmationCode;

use PHPUnit\Framework\TestCase;
use Core;
use Tests;


class CreatingTest extends TestCase
{
  protected static $codes = [];
  protected static $creating_code_adapter;
  protected static $getting_code_adapter;
  protected static $notifying_adapter;
  protected static $creating_code_use_case;

  protected function setUp(): void
  {
    self::$creating_code_adapter = new Tests\Core\ConfirmationCode\Adapters\Changing(self::$codes);
    self::$getting_code_adapter = new Tests\Core\ConfirmationCode\Adapters\Getting(self::$codes);
    self::$notifying_adapter = new Tests\Core\ConfirmationCode\Adapters\Notifying();
    self::$creating_code_use_case = new Core\ConfirmationCode\UseCases\Creating(
      self::$creating_code_adapter,
      self::$getting_code_adapter,
      self::$notifying_adapter
    );
  }

  public function testCreateCode(): void
  {

    $maybe_true = self::$creating_code_use_case->create(['email' => 'name@gmail.com']);

    $this->assertSame(
      $maybe_true,
      true
    );
  }

  public function testInvalidEmail(): void
  {

    $maybe_true = self::$creating_code_use_case->create(['email' => 'name@gmail.']);

    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $maybe_true
    );
  }
}
