<?php declare(strict_types=1);

namespace MySQLAdapters\Role\Mappers;

use Core;
use MySQLAdapters;
use DateTime;

class Entity extends Core\Role\Entity
{
  public function __construct(
    string $id,
    DateTime $created,
    MySQLAdapters\Role\Mappers\ValueObjects\Name $name
  )
  {
    parent::__construct($id, $created, $name);
  }
}
