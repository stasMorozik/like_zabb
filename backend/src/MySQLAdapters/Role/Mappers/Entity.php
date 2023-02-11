<?php declare(strict_types=1);

namespace MySQLAdapters\Role\Mappers;

use Core;
use DateTime;

class Entity extends Core\Role\Entity
{
  public function __construct(
    string $id,
    Core\Role\ValueObjects\Name $name,
    DateTime $created
  )
  {
    parent::__construct($id, $name, $created);
  }
}
