<?php declare(strict_types=1);

namespace Tests\Core\User\Adapters;

use Core;

class GettingById implements Core\User\Ports\GettingById
{
  private $users;

  public function __construct(&$users)
  {
    $this->users = &$users;
  }

  public function get(
    string $id
  ): Core\Common\Errors\InfraStructure | Core\User\Entity
  {
    if (isset($this->users[ $id ])) {
      return $this->users[ $id ];
    }

    return new Core\Common\Errors\InfraStructure('User not found');
  }
}
