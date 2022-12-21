<?php declare(strict_types=1);

namespace Core\ConfirmationCode\Ports;

use Core;

/**
 *
 * Getting port
 *  
**/

interface Getting 
{
  public function get(
    Core\Common\ValueObjects\Email $email
  ): Core\Common\Errors\InfraStructure | Core\ConfirmationCode\Entity;
}