<?php declare(strict_types=1);

namespace MySQLAdapters\Sensor;

use DB;
use Core;
use DateTime;
use MySQLAdapters;

class Creating implements Core\Sensor\Ports\Changing
{
  public function change(
    Core\Sensor\Entity $sensor
  ): Core\Common\Errors\InfraStructure | bool
  {
    DB::startTransaction();

    DB::insert('sensors', [
      'id' => $sensor->getId(),
      'created' => $sensor->getCreated(),
      'name' => $sensor->getName()->getValue(),
      'longitude' => $sensor->getLongitude()->getValue(),
      'latitude' => $sensor->getLatitude()->getValue(),
      'status' => $sensor->getStatus()->getValue(),
      'description' => $sensor->getDescription()
    ]);

    DB::insert('account_sensor', [
      'account_id' => $sensor->getOwner()->getAccount()->getId(),
      'sensor_id' => $sensor->getId()
    ]);

    DB::commit();

    return true;
  }
}
