<?php declare(strict_types=1);

namespace Tests\Core\User\Adapters;

use Core;

class Getting implements Core\User\Ports\Getting
{
  private $users;

  public function __construct(&$users)
  {
    $this->users = &$users;
  }

  public function get(
    Core\Common\ValueObjects\Email $email
  ): Core\Common\Errors\InfraStructure | Core\User\Entity
  {
    if (isset($this->users[ $email->getValue() ])) {
      return $this->users[ $email->getValue() ];
    }

    return new Core\Common\Errors\InfraStructure('User not found');
  }
}
