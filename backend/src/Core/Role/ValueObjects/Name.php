<?php declare(strict_types=1);

namespace Core\Role\ValueObjects;

use Core;

/**
 *
 * Value Object of Name, only for Entity Role
 *
**/

class Name extends Core\Common\ValueObjects\ValueObject
{
  const SUPER = 'SUPER';
  const ADMIN = 'ADMIN';
  const USER = 'USER';
  const OBSERVER = 'OBSERVER';

  protected function __construct(string $name)
  {
    parent::__construct($name);
  }

  public static function new(array $args): Name | Core\Common\Errors\Domain
  {
    if (!isset($args['name'])) {
      return new Core\Common\Errors\Domain('Invalid argument');
    }

    return match ($args['name']) {
      self::SUPER => new Name(self::SUPER),
      self::ADMIN => new Name(self::ADMIN),
      self::USER => new Name(self::USER),
      self::OBSERVER => new Name(self::OBSERVER),
      default => new Core\Common\Errors\Domain('Invalid role')
    };
  }
}
