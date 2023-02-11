<?php declare(strict_types=1);

namespace MySQLAdapters\Common\Mappers\ValueObjects;

use Core;

class Name extends Core\Common\ValueObjects\Name
{
  public function __construct(
    string $name
  )
  {
    parent::__construct($name);
  }
}
