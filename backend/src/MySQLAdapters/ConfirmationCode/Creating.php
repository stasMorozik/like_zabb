<?php declare(strict_types=1);

namespace MySQLAdapters\ConfirmationCode;

use DB;
use Core;

class Creating implements Core\ConfirmationCode\Ports\Changing
{
  public function change(Core\ConfirmationCode\Entity $entity): Core\Common\Errors\InfraStructure | bool
  {
    DB::startTransaction();

    DB::query("DELETE FROM confirmation_codes WHERE email=%s", $entity->getEmail()->getValue());

    DB::insert('confirmation_codes', [
      'id' => $entity->getId(),
      'email' => $entity->getEmail()->getValue(),
      'created' => $entity->getCreated(),
      'created_time' => $entity->getCreatedTime(),
      'code' => $entity->getCode(),
      'confirmed' => $entity->getConfirmed(),
    ]);

    DB::commit();

    return true;
  }
}
