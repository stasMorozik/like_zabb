<?php declare(strict_types=1);

namespace Tests\Core\ConfirmationCode\Adapters;

use Core;

class Changing implements Core\ConfirmationCode\Ports\Changing
{
  private $codes;

  public function __construct(&$codes)
  {
    $this->codes = &$codes;
  }

  public function change(Core\ConfirmationCode\Entity $entity): Core\Common\Errors\InfraStructure | bool
  {
    $this->codes[ $entity->getEmail()->getValue() ] = $entity;

    return true;
  }
}