<?php declare(strict_types=1);

namespace Core\Account\UseCases;

use Core;

/**
 *
 * Creating Use Case
 *
**/

class Creating
{
  private string $_salt;
  private Core\Account\Ports\Changing $_creating_account_port;
  private Core\ConfirmationCode\Ports\Getting $_getting_confirmation_code_port;
  private Core\Role\Ports\Getting $_getting_role_port;

  public function __construct(
    string $salt,
    Core\Account\Ports\Changing $creating_account_port,
    Core\ConfirmationCode\Ports\Getting $getting_confirmation_code_port,
    Core\Role\Ports\Getting $getting_role_port
  )
  {
    $this->_salt = $salt;
    $this->_creating_account_port = $creating_account_port;
    $this->_getting_confirmation_code_port = $getting_confirmation_code_port;
    $this->_getting_role_port = $getting_role_port;
  }

  public function create(array $args): Core\Common\Errors\Domain | Core\Common\Errors\InfraStructure | bool
  {
    $keys = ['email', 'password', 'name'];
    foreach ($keys as &$k) {
      if (!isset($args[$k])) {
        return new Core\Common\Errors\Domain('Invalid arguments');
      }
    }

    $maybe_role = $this->_getting_role_port->get(Core\Role\ValueObjects\Name::new([
      'name' => Core\Role\ValueObjects\Name::SUPER
    ]));
    if ($maybe_role instanceof Core\Common\Errors\InfraStructure) {
      return $maybe_role;
    }

    $maybe_email = Core\Common\ValueObjects\Email::new(['email' => $args['email']]);
    if ($maybe_email instanceof Core\Common\Errors\Domain) {
      return $maybe_email;
    }

    $maybe_code = $this->_getting_confirmation_code_port->get($maybe_email);
    if ($maybe_code instanceof Core\Common\Errors\InfraStructure) {
      return $maybe_code;
    }

    $maybe_true = $maybe_code->isConfirmed();
    if ($maybe_true instanceof Core\Common\Errors\Domain) {
      return $maybe_true;
    }

    $account = Core\Account\Entity::new(['code' => $maybe_code]);

    $maybe_user = Core\User\Entity::new([
      'account' => $account,
      'role' => $maybe_role,
      'email' => $maybe_email,
      'salt' => $this->_salt,
      'name' => $args['name'],
      'password' => $args['password']
    ]);
    if ($maybe_user instanceof Core\Common\Errors\Domain) {
      return $maybe_user;
    }

    return $this->_creating_account_port->change(
      $maybe_user
    );
  }
}
