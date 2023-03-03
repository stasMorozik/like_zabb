<?php declare(strict_types=1);

namespace AMQPAdapters;


use Core;

class Notifying implements Core\Common\Ports\Notifying
{

  public function __construct(

  ){

  }

  public function notify(
    Core\Common\ValueObjects\Email $email,
    string $subject,
    string $message
  ): Core\Common\Errors\InfraStructure | bool
  {
    try {
      return true;
    } catch(Exception $e) {
      return new Core\Common\Errors\InfraStructure($e->getMessage());
    }
  }
}
