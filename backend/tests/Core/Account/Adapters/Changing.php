<?php declare(strict_types=1);

namespace Tests\Core\Account\Adapters;

use Core;

class Changing implements Core\Account\Ports\Changing
{
  private $accounts;
  private $users;

  public function __construct(&$accounts, &$users)
  {
    $this->accounts = &$accounts;
    $this->users = &$users;
  }

  public function change(Core\User\Entity $user): Core\Common\Errors\InfraStructure | bool
  {
    $this->accounts[ $user->getEmail()->getValue() ] = $user->getAccount();

    $this->users[ $user->getEmail()->getValue() ] = $user;

    return true;
  }
}
