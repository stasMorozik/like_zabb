<?php declare(strict_types=1);

namespace Core\Session\UseCases;

use Core;

/**
 *
 * Refreshing Token Use Case
 *
**/

class Refreshing
{
  private string $_access_token_salt;
  private string $_refresh_token_salt;

  public function __construct(
    string $access_token_salt,
    string $refresh_token_salt
  )
  {
    $this->_access_token_salt = $access_token_salt;
    $this->_refresh_token_salt = $refresh_token_salt;
  }

  public function refresh(array $args): Core\Common\Errors\Domain | Core\Session\Entity
  {
    if (!isset($args['refresh_token'])) {
      return new Core\Common\Errors\Domain('Invalid argument');
    }

    return Core\Session\Entity::refresh([
      'access_token_salt' => $this->_access_token_salt,
      'refresh_token_salt' => $this->_refresh_token_salt,
      'refresh_token' => $args['refresh_token']
    ]);
  }
}
