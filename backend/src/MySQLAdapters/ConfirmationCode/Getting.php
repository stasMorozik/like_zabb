<?php declare(strict_types=1);

namespace MySQLAdapters\ConfirmationCode;

use DB;
use Core;
use MySQLAdapters;

class Getting implements Core\ConfirmationCode\Ports\Getting
{
  public function get(
    Core\Common\ValueObjects\Email $email
  ): Core\Common\Errors\InfraStructure | Core\ConfirmationCode\Entity
  {
    $code = DB::queryFirstRow("SELECT * FROM confirmation_codes WHERE email=%s", $email->getValue());

    if (!$code) {
      return new Core\Common\Errors\InfraStructure('Confirmation code not found');
    }

    return new MySQLAdapters\ConfirmationCode\Mappers\MapperEntity(
      $code['id'],
      new MySQLAdapters\Common\Mappers\ValueObjects\Email($code['email']),
      (int) $code['created'],
      (int) $code['code'],
      (bool) $code['confirmed'],
    );
  }
}
