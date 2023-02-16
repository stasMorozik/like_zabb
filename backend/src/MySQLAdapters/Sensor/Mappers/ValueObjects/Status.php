<?php declare(strict_types=1);

namespace MySQLAdapters\Sensor\Mappers\ValueObjects;

use Core;

class Status extends Core\Sensor\ValueObjects\Status
{
  public function __construct(
    string $status
  )
  {
    parent::__construct($status);
  }
}
