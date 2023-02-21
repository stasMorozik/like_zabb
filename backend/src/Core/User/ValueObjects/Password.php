<?php declare(strict_types=1);

namespace Core\User\ValueObjects;

use Core;

/**
 *
 * Value Object of Password, for user or something alike
 *
**/

class Password extends Core\Common\ValueObjects\ValueObject
{
  protected function __construct(string $password)
  {
    parent::__construct($password);
  }

  public static function new(array $args): Password | Core\Common\Errors\Domain
  {
    if (!isset($args['password']) || !isset($args['salt'])) {
      return new Core\Common\Errors\Domain('Invalid argument');
    }

    if(preg_match("/[А-я,Ё,ё]/", $args['password'])) {
      return new Core\Common\Errors\Domain('Invalid password');
    }

    if (mb_strlen($args['password'], 'UTF-8') > 10) {
      return new Core\Common\Errors\Domain('Invalid password');
    }

    if (mb_strlen($args['password'], 'UTF-8') < 5) {
      return new Core\Common\Errors\Domain('Invalid password');
    }

    return new Password(crypt($args['password'], $args['salt']));
  }

  public function validate(array $args): Core\Common\Errors\Domain | bool
  {
    if (!isset($args['password']) || !isset($args['salt'])) {
      return new Core\Common\Errors\Domain('Invalid argument');
    }

    if (!hash_equals($this->getValue(), crypt($args['password'], $args['salt']))) {
      return new Core\Common\Errors\Domain('Wrong password');
    }

    return true;
  }
}
