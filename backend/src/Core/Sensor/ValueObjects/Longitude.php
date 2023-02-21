<?php declare(strict_types=1);

namespace Core\Sensor\ValueObjects;

use Core;

/**
 *
 * Value Object Longitude
 *
**/

class Longitude extends Core\Common\ValueObjects\ValueObject
{
  protected function __construct(float $longitude)
  {
    parent::__construct($longitude);
  }

  public static function new(array $args): Longitude | Core\Common\Errors\Domain
  {
    if (!isset($args['longitude'])) {
      return new Core\Common\Errors\Domain('Invalid argument');
    }

    if (gettype($args['longitude']) != 'double') {
      new Core\Common\Errors\Domain('Invalid longitude');
    }

    return match ($args['longitude'] > 180.0 || $args['longitude'] < -180.0) {
      false => new Longitude($args['longitude']),
      default => new Core\Common\Errors\Domain('Invalid longitude')
    };
  }
}
