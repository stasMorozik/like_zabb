<?php declare(strict_types=1);

namespace Core\Common\ValueObjects;

use Core;

/**
 *
 * ValueObject Email
 *
**/

class Email extends Core\Common\ValueObjects\Common
{
  protected function __construct(string $email)
  {
    parent::__construct($email);
  }

  public static function new(?string $email): Email | Core\Common\Errors\Domain
  {
    if (!$email) {
      return new Core\Common\Errors\Domain('Invalid email');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return new Core\Common\Errors\Domain('Invalid email');
    }

    return new Email($email);
  }
}
