<?php declare(strict_types=1);

namespace MySQLAdapters\Account\Mappers;

use DateTime;
use Core;

class Entity extends Core\Account\Entity
{
  public function __construct(
    string $id,
    Core\Common\ValueObjects\Email $email,
    DateTime $created
  )
  {
    parent::__construct($id, $email, $created);
  }
}
