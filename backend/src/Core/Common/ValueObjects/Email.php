<?php declare(strict_types=1);

namespace Core\Common\ValueObjects;

use Core;

/**
 *
 * ValueObject Email
 *
**/

class Email extends Core\Common\ValueObjects\ValueObject
{
  protected function __construct(string $email)
  {
    parent::__construct($email);
  }

  public static function new(array $args): Email | Core\Common\Errors\Domain
  {
    if (!isset($args['email'])) {
      return new Core\Common\Errors\Domain('Invalid argument');
    }

    if (!filter_var($args['email'], FILTER_VALIDATE_EMAIL)) {
      return new Core\Common\Errors\Domain('Invalid email');
    }

    return new Email($args['email']);
  }
}
