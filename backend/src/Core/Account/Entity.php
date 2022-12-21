<?php declare(strict_types=1);

namespace Core\Account;

use Core;
use DateTime;
use Ramsey\Uuid\Uuid;

/**
 *
 * Entity Account
 *  
**/

class Entity {
  protected string $id;
  protected Core\Common\ValueObjects\Email $email;
  protected DateTime $created;

  protected function __construct(
    string $id,
    Core\Common\ValueObjects\Email $email,
    DateTime $created
  )
  {
    $this->id = $id;
    $this->email = $email;
    $this->created = $created;
  }

  public function getId(): string
  {
    return $this->id;
  }

  public function getEmail(): Core\Common\ValueObjects\Email 
  {
    return $this->email;
  }

  public function getCreated(): DateTime
  {
    return $this->created;
  }

  public static function new(
    Core\ConfirmationCode\Entity $code
  ): Entity
  {
    return new Entity(
      Uuid::uuid4()->toString(),
      $code->getEmail(),
      new DateTime()
    );
  }
}