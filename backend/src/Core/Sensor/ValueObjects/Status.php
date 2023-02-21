<?php declare(strict_types=1);

namespace Core\Sensor\ValueObjects;

use Core;

/**
 *
 * Value Status of Sensor
 *
**/

class Status extends Core\Common\ValueObjects\ValueObject
{
  const ACTIVE = 'ACTIVE';
  const NOT_AVAILABLE = 'NOT_AVAILABLE';

  protected function __construct(string $status)
  {
    parent::__construct($status);
  }

  public static function new(array $args): Status | Core\Common\Errors\Domain
  {
    if (!isset($args['status'])) {
      return new Core\Common\Errors\Domain('Invalid argument');
    }

    return match ($args['status']) {
      self::ACTIVE => new Status(self::ACTIVE),
      self::NOT_AVAILABLE => new Status(self::NOT_AVAILABLE),
      default => new Core\Common\Errors\Domain('Invalid status')
    };
  }
}
