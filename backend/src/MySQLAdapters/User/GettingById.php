<?php declare(strict_types=1);

namespace MySQLAdapters\User;

use DB;
use Core;
use DateTime;
use MySQLAdapters;
use Ramsey\Uuid\Uuid;

class GettingById implements Core\User\Ports\GettingById
{
  public function get(
    string $id
  ): Core\Common\Errors\InfraStructure | Core\User\Entity
  {
    $user = DB::queryFirstRow("SELECT
        u.id AS user_id, u.name AS user_name, u.created AS user_created, u.email AS user_email, u.password AS user_password,
        r.id AS role_id, r.name AS role_name, r.created AS role_created
      FROM users AS u
        JOIN user_role AS ur ON u.id = ur.user_id
        JOIN roles AS r ON ur.role_id = r.id WHERE u.id = %s",
      Uuid::fromString($id)
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
      )
    );
  }
}
