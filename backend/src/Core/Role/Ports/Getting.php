<?php declare(strict_types=1);

namespace Core\Role\Ports;

use Core;

/**
 *
 * Getting port
 *  
**/

interface Getting 
{
  public function get(
    Core\Role\ValueObjects\Name $name
  ): Core\Common\Errors\InfraStructure | Core\Role\Entity;
}