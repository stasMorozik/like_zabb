<?php declare(strict_types=1);

namespace MySQLAdapters\ConfirmationCode\Mappers;

use Core;
use DateTime;
use MySQLAdapters;

class Entity extends Core\ConfirmationCode\Entity
{
  public function __construct(
    string $id,
    DateTime $created,
    int $created_time,
    int $code,
    bool $confirmed,
    MySQLAdapters\Common\Mappers\ValueObjects\Email $email
  )
  {
    parent::__construct($id, $created, $created_time, $code, $confirmed, $email);
  }
}
