<?php declare(strict_types=1);

namespace MySQLAdapters\Sensor\Mappers\ValueObjects;

use Core;

class Longitude extends Core\Sensor\ValueObjects\Longitude
{
  public function __construct(
    float $longitude
  )
  {
    parent::__construct($longitude);
  }
}
