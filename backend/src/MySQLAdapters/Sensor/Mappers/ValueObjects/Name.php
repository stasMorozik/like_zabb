<?php declare(strict_types=1);

namespace MySQLAdapters\Sensor\Mappers\ValueObjects;

use Core;

class Name extends Core\Sensor\ValueObjects\Name
{
  public function __construct(
    string $name
  )
  {
    parent::__construct($name);
  }
}
