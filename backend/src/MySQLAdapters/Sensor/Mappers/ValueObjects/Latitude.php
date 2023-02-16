<?php declare(strict_types=1);

namespace MySQLAdapters\Sensor\Mappers\ValueObjects;

use Core;

class Latitude extends Core\Sensor\ValueObjects\Latitude
{
  public function __construct(
    float $latitude
  )
  {
    parent::__construct($latitude);
  }
}
