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
  private string $_access_token_salt;
  private string $_refresh_token_salt;
  private Core\User\Ports\Getting $_getting_port;

  public function __construct(
    string $password_salt,
    string $access_token_salt,
    string $refresh_token_salt,
    Core\User\Ports\Getting $getting_port
  )
  {
    $this->_password_salt = $password_salt;
    $this->_access_token_salt = $access_token_salt;
    $this->_refresh_token_salt = $refresh_token_salt;
    $this->_getting_port = $getting_port;
  }

  public function auth(array $args): Core\Common\Errors\Domain | Core\Common\Errors\InfraStructure | Core\Session\Entity
  {
    $keys = ['email', 'password'];

    foreach ($keys as &$k) {
      if (!isset($args[$k])) {
        return new Core\Common\Errors\Domain('Invalid arguments');
      }
    }

    $maybe_email = Core\Common\ValueObjects\Email::new(['email' => $args['email']]);

    if ($maybe_email instanceof Core\Common\Errors\Domain) {
      return $maybe_email;
    }

    $maybe_user = $this->_getting_port->get($maybe_email);
    if ($maybe_user instanceof Core\Common\Errors\InfraStructure) {
      return $maybe_user;
    }

    $maybe_true = $maybe_user->validatePassword([
      'password' => $args['password'],
      'salt' => $this->_password_salt
    ]);
    if ($maybe_true instanceof Core\Common\Errors\Domain) {
      return $maybe_true;
    }

    return Core\Session\Entity::new([
      'access_token_salt' => $this->_access_token_salt,
      'refresh_token_salt' => $this->_refresh_token_salt,
      'id' => $maybe_user->getId()
    ]);
  }
}
