<?php declare(strict_types=1);

namespace MySQLAdapters\ConfirmationCode\Mappers;

use Core;

class Entity extends Core\ConfirmationCode\Entity
{
  public function __construct(
    string $id,
    Core\Common\ValueObjects\Email $email,
    int $created,
    int $code,
    bool $confirmed
  )
  {
    parent::__construct($id, $email, $created, $code, $confirmed);
  }
}
