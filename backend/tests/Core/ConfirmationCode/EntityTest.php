<?php declare(strict_types=1);

namespace Tests\Core\ConfirmationCode;

use PHPUnit\Framework\TestCase;
use Core;

class EntityTest extends TestCase
{
  public function testNewCode(): void
  {
    $maybe_email = Core\Common\ValueObjects\Email::new(['email' => 'name@gmail.com']);
    if ($maybe_email instanceof Core\Common\ValueObjects\Email) {
      $code = Core\ConfirmationCode\Entity::new(['email' => $maybe_email]);

      $this->assertInstanceOf(
        Core\ConfirmationCode\Entity::class,
        $code
      );
    }
  }

  public function testCheckLifetime(): void
  {
    $maybe_email = Core\Common\ValueObjects\Email::new(['email' => 'name@gmail.com']);
    if ($maybe_email instanceof Core\Common\ValueObjects\Email) {
      $code = Core\ConfirmationCode\Entity::new(['email' => $maybe_email]);

      $maybe_true = $code->checkLifetime();

      $this->assertInstanceOf(
        Core\Common\Errors\Domain::class,
        $maybe_true
      );
    }
  }

  public function testConfirm(): void
  {
    $maybe_email = Core\Common\ValueObjects\Email::new(['email' => 'name@gmail.com']);
    if ($maybe_email instanceof Core\Common\ValueObjects\Email) {
      $code = Core\ConfirmationCode\Entity::new(['email' => $maybe_email]);

      $maybe_true = $code->confirm($code->getCode());

      $this->assertSame(
        true,
        $maybe_true
      );
    }
  }

  public function testWrongCode(): void
  {
    $maybe_email = Core\Common\ValueObjects\Email::new(['email' => 'name@gmail.com']);
    if ($maybe_email instanceof Core\Common\ValueObjects\Email) {
      $code = Core\ConfirmationCode\Entity::new(['email' => $maybe_email]);

      $maybe_true = $code->confirm(12);

      $this->assertInstanceOf(
        Core\Common\Errors\Domain::class,
        $maybe_true
      );
    }
  }
}
