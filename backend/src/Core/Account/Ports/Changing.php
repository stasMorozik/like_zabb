<?php declare(strict_types=1);

namespace Core\Account\Ports;

use Core;

/**
 *
 * Creating and updating port
 *  
**/

interface Changing 
{ 
  public function change(
    Core\Account\Entity $account,
    Core\User\Entity $user
  ): Core\Common\Errors\InfraStructure | bool;
}