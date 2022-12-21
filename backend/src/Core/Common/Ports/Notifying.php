<?php declare(strict_types=1);

namespace Core\Common\Ports;

use Core;

/**
 *
 * Creating Use Case
 *  
**/

interface Notifying 
{
  public function notify(
    Core\Common\ValueObjects\Email $email,
    string $message
  ): void;
}