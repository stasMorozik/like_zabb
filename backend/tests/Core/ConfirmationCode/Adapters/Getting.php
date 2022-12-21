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
    try {
      return $this->codes[ $email->getValue() ];
    } catch(Exception $_) {
      return new Core\Common\Errors\InfraStructure('Code not found');
    }
  }
}