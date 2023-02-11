<?php declare(strict_types=1);

namespace Core\User\Ports;

use Core;

/**
 *
 * Getting User port by id
 *
**/

interface GettingById
{
  public function get(
    string $id
  ): Core\Common\Errors\InfraStructure | Core\User\Entity;
}
