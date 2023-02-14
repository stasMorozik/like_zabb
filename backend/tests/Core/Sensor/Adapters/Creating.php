<?php declare(strict_types=1);

namespace Tests\Core\Sensor\Adapters;

use Core;

class Creating implements Core\Sensor\Ports\Changing
{
  private $sensors;

  public function __construct(&$sensors)
  {
    $this->sensors = &$sensors;
  }

  public function change(Core\Sensor\Entity $sensor): Core\Common\Errors\InfraStructure | bool
  {
    $this->sensors[ $sensor->getId() ] = $sensor;

    return true;
  }
}
