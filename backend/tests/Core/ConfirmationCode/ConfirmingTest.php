<?php declare(strict_types=1);

namespace Tests\Core\ConfirmationCode;

use PHPUnit\Framework\TestCase;
use Core;
use Tests;


class ConfirmingTest extends TestCase
{
  protected static $codes = [];
  protected static $email = 'name@gmail.com';

  protected static $creating_code_adapter;
  protected static $getting_code_adapter;
  protected static $notifying_adapter;
  protected static $creating_code_use_case;
  protected static $confirming_code_use_case;

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

    self::$confirming_code_use_case = new Core\ConfirmationCode\UseCases\Confirming(
      self::$creating_code_adapter,
      self::$getting_code_adapter
    );

    self::$creating_code_use_case->create(['email' => self::$email]);
  }

  public function testConfirmCode(): void
  {
    $maybe_true = self::$confirming_code_use_case->confirm([
      'email' => self::$email,
      'code' => self::$codes[self::$email]->getCode()
    ]);

    $this->assertSame(
      $maybe_true,
      true
    );
  }

  public function testCodeNotFound(): void
  {
    $maybe_true = self::$confirming_code_use_case->confirm([
      'email' => 'name1@gmail.com',
      'code' => self::$codes[self::$email]->getCode()
    ]);

    $this->assertInstanceOf(
      Core\Common\Errors\InfraStructure::class,
      $maybe_true
    );
  }

  public function testWrongCode(): void
  {
    $maybe_true = self::$confirming_code_use_case->confirm([
      'email' => self::$email,
      'code' => 123
    ]);

    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $maybe_true
    );
  }
}
