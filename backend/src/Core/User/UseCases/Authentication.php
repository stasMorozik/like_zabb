<?php declare(strict_types=1);

namespace Core\User\UseCases;

use Core;

/**
 *
 * Authentication Use Case
 *
**/

class Authentication
{
  private string $_password_salt;
  private string $_token_salt;
  private string $_refresh_token_salt;
  private Core\User\Ports\Getting $_getting_port;

  public function __construct(
    string $password_salt,
    string $token_salt,
    Core\User\Ports\Getting $_getting_port
  )
  {
    $this->_salt = $salt;
    $this->_creating_account_port = $creating_account_port;
    $this->_getting_confirmation_code_port = $getting_confirmation_code_port;
    $this->_getting_role_port = $getting_role_port;
  }

  public function auth(): Core\Common\Errors\Domain | Core\Common\Errors\InfraStructure | Core\Session\Entity
  {

  }
}
