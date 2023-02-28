<?php declare(strict_types=1);

namespace Core\Common\Errors;

use Core;

class Unauthorized extends Core\Common\Errors\Common
{
  function __construct(string $message)
  {
    parent::__construct($message);
  }
}
