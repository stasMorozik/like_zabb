<?php declare(strict_types=1);

namespace Core\Role\ValueObjects;

use Core;

/**
 *
 * Value Object of Name, only for Entity Role
 *
**/

class Name extends Core\Common\ValueObjects\Common
{
  const SUPER = 'SUPER';
  const ADMIN = 'ADMIN';
  const USER = 'USER';
  const OBSERVER = 'OBSERVER';

  protected function __construct(string $name)
  {
    parent::__construct($name);
  }

  public static function new(?string $name): Name | Core\Common\Errors\Domain
  {
    return match ($name) {
      self::SUPER => new Name($name),
      self::ADMIN => new Name($name),
      self::USER => new Name($name),
      self::OBSERVER => new Name($name),
      default => new Core\Common\Errors\Domain('Invalid role')
    };
  }
}
