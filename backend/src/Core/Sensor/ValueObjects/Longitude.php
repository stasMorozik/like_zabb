<?php declare(strict_types=1);

namespace Core\Sensor\ValueObjects;

use Core;

/**
 *
 * Value Object Longitude
 *
**/

class Longitude extends Core\Common\ValueObjects\Common
{
  protected function __construct(float $longitude)
  {
    parent::__construct($longitude);
  }

  public static function new(?float $longitude): Longitude | Core\Common\Errors\Domain
  {
    return match ($longitude > 180.0 || $longitude < -180.0) {
      false => new Longitude($longitude),
      default => new Core\Common\Errors\Domain('Invalid longitude')
    };
  }
}
