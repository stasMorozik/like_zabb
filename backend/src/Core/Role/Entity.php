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

class Entity extends Core\Common\Entity
{
  protected Core\Role\ValueObjects\Name $name;

  protected function __construct(
    string $id,
    DateTime $created,
    Core\Role\ValueObjects\Name $name
  )
  {
    $this->name = $name;
    parent::__construct($id, $created);
  }

  public function getName(): Core\Role\ValueObjects\Name
  {
    return $this->name;
  }

  public static function new(array $args): Core\Common\Errors\Domain | Entity
  {
    $maybe_name = Core\Role\ValueObjects\Name::new($args);

    if ($maybe_name instanceof Core\Common\Errors\Domain) {
      return $maybe_name;
    }

    return new Entity(
      Uuid::uuid4()->toString(),
      new DateTime(),
      $maybe_name
    );
  }
}
