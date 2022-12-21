<?php declare(strict_types=1);

namespace Tests\Core\Account;

use PHPUnit\Framework\TestCase;
use Core;

class EntityTest extends TestCase 
{
  public function testNewAccount(): void
  {
    $maybe_email = Core\Common\ValueObjects\Email::new('name@gmail.com');
    if ($maybe_email instanceof Core\Common\ValueObjects\Email) {
      $code = Core\ConfirmationCode\Entity::new($maybe_email);

      $this->assertInstanceOf(
        Core\Account\Entity::class,
        Core\Account\Entity::new($code)
      );
    }
  }

  public function testInvalidEmail(): void
  {
    $maybe_email = Core\Common\ValueObjects\Email::new('namegmail.');

    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $maybe_email
    );
  }
}