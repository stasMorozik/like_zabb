<?php declare(strict_types=1);

namespace Core\Common\ValueObjects;

class Common {

  protected mixed $value;

  protected function __construct(mixed $value)
  {
    $this->value = $value;
  }

  public function getValue(): mixed
  {
    return $this->value;
  }
}

