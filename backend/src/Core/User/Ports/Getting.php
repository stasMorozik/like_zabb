<?php declare(strict_types=1);

namespace Core\User\Ports;

use Core;

/**
 *
 * Getting User port
 *
**/

interface Getting
{
  public function get(
    Core\Common\ValueObjects\Email $email
  ): Core\Common\Errors\InfraStructure | Core\User\Entity;
}
