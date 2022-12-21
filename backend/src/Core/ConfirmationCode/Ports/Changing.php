<?php declare(strict_types=1);

namespace Core\ConfirmationCode\Ports;

use Core;

/**
 *
 * Creating and updating port
 *  
**/

interface Changing 
{
  public function change(
    Core\ConfirmationCode\Entity $entity
  ): Core\Common\Errors\InfraStructure | bool;
}