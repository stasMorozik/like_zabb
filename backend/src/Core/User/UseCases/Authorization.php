<?php declare(strict_types=1);

namespace Core\User\UseCases;

use Core;

/**
 *
 * Authorization Use Case
 *
**/

class Authorization
{
  private string $_access_token_salt;
  private Core\User\Ports\GettingById $_getting_port;

  public function __construct(
    string $access_token_salt,
    Core\User\Ports\GettingById $getting_port
  )
  {
    $this->_access_token_salt = $access_token_salt;
    $this->_getting_port = $getting_port;
  }

  public function auth(array $args): Core\Common\Errors\InfraStructure | Core\Common\Errors\Domain | Core\User\Entity
  {
    if (!isset($args['access_token'])) {
      return new Core\Common\Errors\Domain('Invalid argument');
    }

    $maybe_id = Core\Session\Entity::decode([
      'access_token_salt' => $this->_access_token_salt,
      'access_token' => $args['access_token']
    ]);

    if ($maybe_id instanceof Core\Common\Errors\Domain) {
      return $maybe_id;
    }

    return $this->_getting_port->get($maybe_id);
  }
}
