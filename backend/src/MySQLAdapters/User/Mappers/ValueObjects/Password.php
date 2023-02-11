<?php declare(strict_types=1);

namespace MySQLAdapters\User\Mappers\ValueObjects;

use Core;

class Password extends Core\User\ValueObjects\Password
{
  public function __construct(string $password)
  {
    parent::__construct($password);
  }
}
