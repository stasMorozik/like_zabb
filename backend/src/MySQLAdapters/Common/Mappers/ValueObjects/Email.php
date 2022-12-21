<?php declare(strict_types=1);

namespace MySQLAdapters\Common\Mappers\ValueObjects;

use Core;

class Email extends Core\Common\ValueObjects\Email
{
  public function __construct(
    string $email
  )
  {
    parent::__construct($email);
  }
}

