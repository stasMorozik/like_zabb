<?php declare(strict_types=1);

namespace Tests\Core\Role\Adapters;

use Core;
use Exception;

class Getting implements Core\Role\Ports\Getting
{
  private $roles;

  public function __construct(&$roles)
  {
    $this->roles = &$roles;
  }

  public function get(Core\Role\ValueObjects\Name $name): Core\Common\Errors\InfraStructure | Core\Role\Entity
  {
    try {
      return $this->roles[ $name->getValue() ];
    } catch(Exception $_) {
      return new Core\Common\Errors\InfraStructure('Role not found');
    }
  }
}