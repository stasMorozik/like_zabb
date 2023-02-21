<?php declare(strict_types=1);

namespace Core\Sensor\ValueObjects;

use Core;

/**
 *
 * Value Object Latitude
 *
**/

class Latitude extends Core\Common\ValueObjects\ValueObject
{
  protected function __construct(float $latitude)
  {
    parent::__construct($latitude);
  }

  public static function new(array $args): Latitude | Core\Common\Errors\Domain
  {
    if (!isset($args['latitude'])) {
      return new Core\Common\Errors\Domain('Invalid argument');
    }

    if (gettype($args['latitude']) != 'double') {
      new Core\Common\Errors\Domain('Invalid latitude');
    }

    return match ($args['latitude'] > 90.0 || $args['latitude'] < -90.0) {
      false => new Latitude($args['latitude']),
      default => new Core\Common\Errors\Domain('Invalid latitude')
    };
  }
}
