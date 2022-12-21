<?php declare(strict_types=1);

namespace Core\Role;

use Core;
use DateTime;
use Ramsey\Uuid\Uuid;

/**
 *
 * Entity Role
 *  
**/

class Entity 
{
  protected string $id;
  protected Core\Role\ValueObjects\Name $name;
  protected DateTime $created;

  protected function __construct(
    string $id,
    Core\Role\ValueObjects\Name $name,
    DateTime $created
  )
  {
    $this->id = $id;
    $this->name = $name;
    $this->created = $created;
  }

  public function getId(): string 
  {
    return $this->id;
  }

  public function getName(): Core\Role\ValueObjects\Name 
  {
    return $this->name;
  }

  public function getCreated(): DateTime
  {
    return $this->created;
  }

  public static function new(
    ?string $name
  ): Core\Common\Errors\Domain | Entity
  {
    $maybe_name = Core\Role\ValueObjects\Name::new($name);

    if ($maybe_name instanceof Core\Common\Errors\Domain) {
      return $maybe_name;
    }

    return new Entity(
      Uuid::uuid4()->toString(),
      $maybe_name,
      new DateTime()
    );
  }
}