<?php declare(strict_types=1);

namespace Tests\Core\ConfirmationCode\Adapters;

use Core;
use Exception;

class Getting implements Core\ConfirmationCode\Ports\Getting
{
  private $codes;

  public function __construct(&$codes)
  {
    $this->codes = &$codes;
  }

  public function get(Core\Common\ValueObjects\Email $email): Core\Common\Errors\InfraStructure | Core\ConfirmationCode\Entity
  {
    if (isset($this->codes[ $email->getValue() ])) {
      return $this->codes[ $email->getValue() ];
    }

    return new Core\Common\Errors\InfraStructure('Code not found');
  }
}
