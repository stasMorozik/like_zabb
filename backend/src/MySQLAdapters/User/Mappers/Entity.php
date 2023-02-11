<?php declare(strict_types=1);

namespace MySQLAdapters\User\Mappers;

use Core;
use MySQLAdapters;
use DateTime;

class Entity extends Core\User\Entity
{
  public function __construct(
    string $id,
    DateTime $created,
    MySQLAdapters\Common\Mappers\ValueObjects\Name $name,
    MySQLAdapters\Common\Mappers\ValueObjects\Email $email,
    MySQLAdapters\User\Mappers\ValueObjects\Password $password,
    MySQLAdapters\Role\Mappers\Entity $role
  )
  {
    parent::__construct($id, $created, $name, $email, $password, $role);
  }
}
