<?php declare(strict_types=1);

namespace Core\User;

use Core;
use DateTime;
use Ramsey\Uuid\Uuid;

/**
 *
 * Entity User
 *
**/

class Entity extends Core\Common\Entity
{
  protected Core\Common\ValueObjects\Name $name;
  protected Core\Common\ValueObjects\Email $email;
  protected Core\User\ValueObjects\Password $password;
  protected Core\Role\Entity $role;
  protected Core\Account\Entity $account;

  protected function __construct(
    string $id,
    DateTime $created,
    Core\Common\ValueObjects\Name $name,
    Core\Common\ValueObjects\Email $email,
    Core\User\ValueObjects\Password $password,
    Core\Role\Entity $role,
    Core\Account\Entity $account
  )
  {
    $this->name = $name;
    $this->email = $email;
    $this->password = $password;
    $this->role = $role;
    $this->account = $account;
    parent::__construct($id, $created);
  }

  public function getName(): Core\Common\ValueObjects\Name
  {
    return $this->name;
  }

  public function getEmail(): Core\Common\ValueObjects\Email
  {
    return $this->email;
  }

  public function getPassword(): Core\User\ValueObjects\Password
  {
    return $this->password;
  }

  public function getRole(): Core\Role\Entity
  {
    return $this->role;
  }

  public function getAccount(): Core\Account\Entity
  {
    return $this->account;
  }

  public static function new(array $args): Core\Common\Errors\Domain | Entity
  {
    $keys = ['account', 'role', 'email', 'salt', 'name', 'password'];

    foreach ($keys as &$k) {
      if (!isset($args[$k])) {
        return new Core\Common\Errors\Domain('Invalid arguments');
      }
    }

    $maybe_name = Core\Common\ValueObjects\Name::new(['name' => $args['name']]);
    if ($maybe_name instanceof Core\Common\Errors\Domain) {
      return $maybe_name;
    }

    $maybe_password = Core\User\ValueObjects\Password::new([
      'password' => $args['password'],
      'salt' => $args['salt']
    ]);

    if ($maybe_password instanceof Core\Common\Errors\Domain) {
      return $maybe_password;
    }

    if (($args['email'] instanceof Core\Common\ValueObjects\Email) == false) {
      return new Core\Common\Errors\Domain('Invalid email');
    }

    if (($args['role'] instanceof Core\Role\Entity) == false) {
      return new Core\Common\Errors\Domain('Invalid role');
    }

    if (($args['account'] instanceof Core\Account\Entity) == false) {
      return new Core\Common\Errors\Domain('Invalid account');
    }

    return new Entity(
      Uuid::uuid4()->toString(),
      new DateTime(),
      $maybe_name,
      $args['email'],
      $maybe_password,
      $args['role'],
      $args['account']
    );
  }

  public function validatePassword(array $args): Core\Common\Errors\Domain | bool
  {
    return $this->password->validate($args);
  }

  public function isAdmin(): Core\Common\Errors\Domain | bool
  {
    if ($this->getRole()->getName()->getValue() != Core\Role\ValueObjects\Name::ADMIN) {
      return new Core\Common\Errors\Domain('You have not admin rights');
    }

    return true;
  }

  public function isRoot(): Core\Common\Errors\Domain | bool
  {
    if ($this->getRole()->getName()->getValue() != Core\Role\ValueObjects\Name::SUPER) {
      return new Core\Common\Errors\Domain('You have not root rights');
    }

    return true;
  }
}
