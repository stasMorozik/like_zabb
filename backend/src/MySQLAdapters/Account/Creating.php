<?php declare(strict_types=1);

namespace MySQLAdapters\Account;

use DB;
use Core;
use Exception;

class Creating implements Core\Account\Ports\Changing
{
  public function change(
    Core\User\Entity $user
  ): Core\Common\Errors\InfraStructure | bool
  {
    try {
      DB::startTransaction();

      DB::insert('accounts', [
        'id' => $user->getAccount()->getId(),
        'email' => $user->getAccount()->getEmail()->getValue(),
        'created' => $user->getAccount()->getCreated()
      ]);

      DB::insert('users', [
        'id' => $user->getId(),
        'email' => $user->getEmail()->getValue(),
        'created' => $user->getCreated(),
        'name' => $user->getName()->getValue(),
        'password' => $user->getPassword()->getValue(),
      ]);

      DB::insert('user_account', [
        'user_id' => $user->getId(),
        'account_id' => $user->getAccount()->getId()
      ]);

      DB::insert('user_role', [
        'user_id' => $user->getId(),
        'role_id' => $user->getRole()->getId()
      ]);

      DB::commit();

      return true;
    } catch(Exception $e) {
      DB::rollback();

      if ($e->getCode() == 1062) {
        return new Core\Common\Errors\InfraStructure('User already exists');
      }

      throw $e;
    }
  }
}
