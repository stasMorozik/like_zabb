<?php declare(strict_types=1);

namespace MySQLAdapters\ConfirmationCode;

use DB;
use Core;

class Confirming implements Core\ConfirmationCode\Ports\Changing 
{
  public function change(Core\ConfirmationCode\Entity $entity): Core\Common\Errors\InfraStructure | bool
  {
    DB::startTransaction();

    DB::query("UPDATE confirmation_codes SET confirmed=1 WHERE email=%s", $entity->getEmail()->getValue());

    $counter = DB::affectedRows();

    if (!$counter) {
      DB::rollback();
      return new Core\Common\Errors\InfraStructure('Confirmation code not found');
    } 

    DB::commit();

    return true;
  }
}