<?php declare(strict_types=1);

namespace Tests\Core\ConfirmationCode\Adapters;

use Core;

class Notifying implements Core\Common\Ports\Notifying
{
  public function __construct()
  {

  }

  public function notify(Core\Common\ValueObjects\Email $email, string $subject, string $message): Core\Common\Errors\InfraStructure | bool
  {
    print_r($message);
    return true;
  }
}
