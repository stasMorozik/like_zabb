<?php declare(strict_types=1);

namespace Core\Role\Ports;

use Core;

/**
 *
 * Getting list port
 *  
**/

interface Getting 
{
  public function get(): Core\Common\Errors\InfraStructure | array;
}