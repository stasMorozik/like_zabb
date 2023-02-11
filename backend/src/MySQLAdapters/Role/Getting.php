<?php declare(strict_types=1);

namespace MySQLAdapters\Role;

use DB;
use Core;
use DateTime;
use MySQLAdapters;

class Getting implements Core\Role\Ports\Getting
{
  public function get(Core\Role\ValueObjects\Name $name): Core\Common\Errors\InfraStructure | Core\Role\Entity
  {
    $role = DB::queryFirstRow("SELECT * FROM roles WHERE name=%s", $name->getValue());

    if (!$role) {
      return new Core\Common\Errors\InfraStructure('Role not found');
    }

    return new MySQLAdapters\Role\Mappers\Entity(
      $role['id'],
      new MySQLAdapters\Role\Mappers\ValueObjects\Name($role['name']),
      new DateTime($role['created'])
    );
  }
}
