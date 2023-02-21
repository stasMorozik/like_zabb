<?php declare(strict_types=1);

namespace Core\Common\ValueObjects;

use Core;

/**
 *
 * Value Object of Name, for user or something alike
 *
**/

class Name extends Core\Common\ValueObjects\ValueObject
{
  protected function __construct(string $name)
  {
    parent::__construct($name);
  }

  public static function new(array $args): Name | Core\Common\Errors\Domain
  {
    if (!isset($args['name'])) {
      return new Core\Common\Errors\Domain('Invalid argument');
    }

    if (mb_strlen($args['name'], 'UTF-8') < 2 || mb_strlen($args['name'], 'UTF-8') > 30) {
      return new Core\Common\Errors\Domain('Invalid name');
    }

    if (!preg_match("/^[a-zA-Z\s]+$/i", $args['name'])) {
      return new Core\Common\Errors\Domain('Invalid name');
    }

    return new Name($args['name']);
  }
}
