<?php declare(strict_types=1);

namespace MySQLAdapters\Account\Mappers;

use DateTime;
use Core;

class Entity extends Core\Account\Entity
{
  public function __construct(
    string $id,
    DateTime $created,
    Core\Common\ValueObjects\Email $email
  )
  {
    parent::__construct($id, $created, $email);
  }
}
