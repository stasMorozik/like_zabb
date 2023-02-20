<?php declare(strict_types=1);

namespace Core\User\ValueObjects;

use Core;

/**
 *
 * Value Object of Password, for user or something alike
 *
**/

class Password extends Core\Common\ValueObjects\Common
{
  protected function __construct(string $password)
  {
    parent::__construct($password);
  }

  public static function new(?string $password, string $salt): Password | Core\Common\Errors\Domain
  {
    if (!$password) {
      return new Core\Common\Errors\Domain('Invalid password');
    }

    if(preg_match("/[А-я,Ё,ё]/", $password)) {
      return new Core\Common\Errors\Domain('Invalid password');
    }

    if (mb_strlen($password, 'UTF-8') > 10) {
      return new Core\Common\Errors\Domain('Invalid password');
    }

    if (mb_strlen($password, 'UTF-8') < 5) {
      return new Core\Common\Errors\Domain('Invalid password');
    }

    return new Password(crypt($password, $salt));
  }

  public function validate(string $password, string $salt): Core\Common\Errors\Domain | bool
  {
    if (!hash_equals($this->value, crypt($password, $salt))) {
      return new Core\Common\Errors\Domain('Wrong password');
    }

    return true;
  }
}
