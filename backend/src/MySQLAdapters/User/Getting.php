<?php declare(strict_types=1);

namespace MySQLAdapters\User;

use DB;
use Core;
use DateTime;
use MySQLAdapters;

class Getting implements Core\User\Ports\Getting
{
  public function get(
    Core\Common\ValueObjects\Email $email
  ): Core\Common\Errors\InfraStructure | Core\User\Entity
  {
    $user = DB::queryFirstRow("SELECT
        u.id AS user_id, u.name AS user_name, u.created AS user_created, u.email AS user_email, u.password AS user_password,
        r.id AS role_id, r.name AS role_name, r.created AS role_created,
        a.id AS account_id, a.email AS account_email, a.created AS account_reated
      FROM users AS u
        JOIN user_role AS ur ON u.id = ur.user_id
        JOIN roles AS r ON ur.role_id = r.id
        JOIN user_account AS ua ON u.id = ua.user_id
        JOIN accounts AS a ON ua.account_id = a.id
      WHERE u.email=%s",
      $email->getValue()
    );

    if (!$user) {
      return new Core\Common\Errors\InfraStructure('User not found');
    }

    return new MySQLAdapters\User\Mappers\Entity(
      $user['user_id'],
      new DateTime($user['user_created']),
      new MySQLAdapters\Common\Mappers\ValueObjects\Name($user['user_name']),
      new MySQLAdapters\Common\Mappers\ValueObjects\Email($user['user_email']),
      new MySQLAdapters\User\Mappers\ValueObjects\Password($user['user_password']),
      new MySQLAdapters\Role\Mappers\Entity(
        $user['role_id'],
        new MySQLAdapters\Role\Mappers\ValueObjects\Name($user['role_name']),
        new DateTime($user['role_created'])
      ),
      new MySQLAdapters\Account\Mappers\Entity(
        $user['account_id'],
        new MySQLAdapters\Common\Mappers\ValueObjects\Email($user['account_email']),
        new DateTime($user['account_reated'])
      )
    );
  }
}
