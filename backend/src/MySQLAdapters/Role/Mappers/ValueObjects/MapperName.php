<?php declare(strict_types=1);

namespace MySQLAdapters\Role\Mappers\ValueObjects;

use Core;

class MapperName extends Core\Role\ValueObjects\Name 
{
  public function __construct(
    string $name
  )
  {
    parent::__construct($name);
  }
}