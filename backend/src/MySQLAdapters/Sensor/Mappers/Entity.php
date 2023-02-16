<?php declare(strict_types=1);

namespace MySQLAdapters\Sensor\Mappers;

use Core;
use DateTime;
use MySQLAdapters;

class Entity extends Core\Sensor\Entity
{
  public function __construct(
    string $id,
    DateTime $created,
    MySQLAdapters\Sensor\Mappers\ValueObjects\Name $name,
    MySQLAdapters\Sensor\Mappers\ValueObjects\Longitude $longitude,
    MySQLAdapters\Sensor\Mappers\ValueObjects\Latitude $latitude,
    MySQLAdapters\Sensor\Mappers\ValueObjects\Status $status,
    string $description,
    MySQLAdapters\User\Mappers\Entity $owner
  )
  {
    parent::__construct(
      $id,
      $created,
      $name,
      $longitude,
      $latitude,
      $status,
      $description,
      $owner
    );
  }
}
