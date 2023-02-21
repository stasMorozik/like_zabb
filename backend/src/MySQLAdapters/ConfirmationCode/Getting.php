<?php declare(strict_types=1);

namespace MySQLAdapters\ConfirmationCode;

use DB;
use Core;
use MySQLAdapters;
use DateTime;

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

    return new MySQLAdapters\ConfirmationCode\Mappers\Entity(
      $code['id'],
      new DateTime($code['created']),
      (int) $code['created_time'],
      (int) $code['code'],
      (bool) $code['confirmed'],
      new MySQLAdapters\Common\Mappers\ValueObjects\Email($code['email'])
    );
  }
}
