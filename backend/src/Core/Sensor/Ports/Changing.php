<?php declare(strict_types=1);

namespace Core\Sensor\Ports;

use Core;

/**
 *
 * Creating and updating port
 *
**/

interface Changing
{
  public function change(
    Core\Sensor\Entity $sensor
  ): Core\Common\Errors\InfraStructure | bool;
}
