<?php declare(strict_types=1);

namespace Core\ConfirmationCode\UseCases;

use Core;

/**
 *
 * Confirming Use Case
 *  
**/

class Confirming 
{
  private Core\ConfirmationCode\Ports\Changing $_updating_port;
  private Core\ConfirmationCode\Ports\Getting $_getting_port;

  public function __construct(
    Core\ConfirmationCode\Ports\Changing $updating_port,
    Core\ConfirmationCode\Ports\Getting $getting_port
  )
  {
    $this->_updating_port = $updating_port;
    $this->_getting_port = $getting_port;
  }

  public function confirm(
    ?string $email,
    ?int $code
  ): Core\Common\Errors\Domain | Core\Common\Errors\InfraStructure | bool
  {
    //Validate email address
    $maybe_email = Core\Common\ValueObjects\Email::new($email);
    if ($maybe_email instanceof Core\Common\Errors\Domain) {
      return $maybe_email;
    }

    //Getting entity code from storage
    $maybe_code = $this->_getting_port->get($maybe_email);
    if ($maybe_code instanceof Core\Common\Errors\InfraStructure) {
      return $maybe_code;
    }

    //Confirming code
    $maybe_true = $maybe_code->confirm($code);
    if ($maybe_true instanceof Core\Common\Errors\Domain) {
      return $maybe_true;
    }

    return $this->_updating_port->change($maybe_code);
  }
}