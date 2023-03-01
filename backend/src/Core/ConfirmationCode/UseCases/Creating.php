<?php declare(strict_types=1);

namespace Core\ConfirmationCode\UseCases;

use Core;

/**
 *
 * Creating Use Case
 *
**/

class Creating
{
  private Core\ConfirmationCode\Ports\Changing $_creating_port;
  private Core\ConfirmationCode\Ports\Getting $_getting_port;
  private Core\Common\Ports\Notifying $_notifying_port;

  public function __construct(
    Core\ConfirmationCode\Ports\Changing $creating_port,
    Core\ConfirmationCode\Ports\Getting $getting_port,
    Core\Common\Ports\Notifying $notifying_port
  )
  {
    $this->_creating_port = $creating_port;
    $this->_getting_port = $getting_port;
    $this->_notifying_port = $notifying_port;
  }

  public function create(array $args): Core\Common\Errors\Domain | Core\Common\Errors\InfraStructure | bool
  {
    if (!isset($args['email'])) {
      return new Core\Common\Errors\Domain('Invalid arguments');
    }

    //Validate email address
    $maybe_email = Core\Common\ValueObjects\Email::new(['email' => $args['email']]);
    if ($maybe_email instanceof Core\Common\Errors\Domain) {
      return $maybe_email;
    }

    //Getting entity code from storage
    $maybe_code = $this->_getting_port->get($maybe_email);
    if ($maybe_code instanceof Core\ConfirmationCode\Entity) {
      //Checking confirmed
      $maybe_true = $maybe_code->isConfirmed();

      if ($maybe_true instanceof Core\Common\Errors\Domain) {
        //Checking lifetime
        //If lifetime is true then confirmation code already exists
        $maybe_true = $maybe_code->checkLifetime();
        if ($maybe_true instanceof Core\Common\Errors\Domain) {
          return $maybe_true;
        }
      }
    }

    $maybe_code = Core\ConfirmationCode\Entity::new(['email' => $maybe_email]);

    $maybe_true = $this->_creating_port->change($maybe_code);
    if ($maybe_true instanceof Core\Common\Errors\InfraStructure) {
      return $maybe_true;
    }

    $this->_notifying_port->notify(
      $maybe_email,
      "Confirm email address",
      "Hello! Your Code is {$maybe_code->getCode()}."
    );

    return true;
  }
}
