<?php declare(strict_types=1);

namespace Core\Sensor\ValueObjects;

use Core;

/**
 *
 * Value Object of Name, only for Entity Sensor
 *
**/

class Name extends Core\Common\ValueObjects\Common
{
  protected function __construct(?string $name)
  {
    parent::__construct($name);
  }

  public static function new(?string $name): Name | Core\Common\Errors\Domain
  {
    if (!$name) {
      return new Core\Common\Errors\Domain('Invalid name');
    }

    if (mb_strlen($name, 'UTF-8') < 2 || mb_strlen($name, 'UTF-8') > 30) {
      return new Core\Common\Errors\Domain('Invalid name');
    }

    if (!preg_match("/^[a-zA-Z0-9\s\-\_]+$/i", $name);) {
      return new Core\Common\Errors\Domain('Invalid name');
    }

    return new Name($name);
  }
}
