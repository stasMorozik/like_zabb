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

class Entity
{
  protected string $id;
  protected DateTime $created;
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
    $this->id = $id;
    $this->created = $created;
    $this->name = $name;
    $this->email = $email;
    $this->password = $password;
    $this->role = $role;
    $this->account = $account;
  }

  public function getId(): string
  {
    return $this->id;
  }

  public function getCreated(): DateTime
  {
    return $this->created;
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

  public static function new(
    Core\Account\Entity $account,
    Core\Role\Entity $role,
    Core\Common\ValueObjects\Email $email,
    string $salt,
    ?string $name,
    ?string $password
  ): Core\Common\Errors\Domain | Entity
  {
    $maybe_name = Core\Common\ValueObjects\Name::new($name);
    if ($maybe_name instanceof Core\Common\Errors\Domain) {
      return $maybe_name;
    }

    $maybe_password = Core\User\ValueObjects\Password::new($password, $salt);
    if ($maybe_password instanceof Core\Common\Errors\Domain) {
      return $maybe_password;
    }

    return new Entity(
      Uuid::uuid4()->toString(),
      new DateTime(),
      $maybe_name,
      $email,
      $maybe_password,
      $role
    );
  }

  public function validatePassword(?string $password, string $salt): Core\Common\Errors\Domain | bool
  {
    return $this->password->validate($password, $salt);
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
