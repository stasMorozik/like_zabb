<?php declare(strict_types=1);

namespace Core\Sensor\ValueObjects;

use Core;

/**
 *
 * Value Object Latitude
 *
**/

class Latitude extends Core\Common\ValueObjects\Common
{
  protected function __construct(int $latitude)
  {
    parent::__construct($latitude);
  }

  public static function new(?int $latitude): Latitude | Core\Common\Errors\Domain
  {
    return match ($latitude > 90.0 || $latitude < -90.0) {
      false => new Latitude($latitude),
      default => new Core\Common\Errors\Domain('Invalid latitude')
    };
  }
}
