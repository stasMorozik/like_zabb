<?php declare(strict_types=1);

namespace Core\Common\ValueObjects;

use Core;

abstract class ValueObject {

  private mixed $value;

  public function __construct($value)
  {
    $this->value = $value;
  }

  public function getValue()
  {
    return $this->value;
  }

  abstract public static function new(array $args): ValueObject | Core\Common\Errors\Domain;
}

