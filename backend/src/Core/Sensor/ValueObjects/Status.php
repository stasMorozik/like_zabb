<?php declare(strict_types=1);

namespace Core\Sensor\ValueObjects;

use Core;

/**
 *
 * Value Status of Sensor
 *
**/

class Status extends Core\Common\ValueObjects\Common
{
  const ACTIVE = 'ACTIVE';
  const NOT_AVAILABLE = 'NOT_AVAILABLE';

  protected function __construct(string $status)
  {
    parent::__construct($status);
  }

  public static function new(?string $status): Status | Core\Common\Errors\Domain
  {
    return match ($status) {
      self::ACTIVE => new Status($status),
      self::NOT_AVAILABLE => new Status($status),
      default => new Core\Common\Errors\Domain('Invalid status')
    };
  }
}
