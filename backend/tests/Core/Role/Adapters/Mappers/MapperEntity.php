<?php declare(strict_types=1);

namespace Tests\Core\Role\Adapters\Mappers;

use Core;
use DateTime;
use Ramsey\Uuid\Uuid;

class MapperEntity extends Core\Role\Entity {
  public function __construct(
    string $name,
  )
  {
    parent::__construct(
      Uuid::uuid4()->toString(), 
      Core\Role\ValueObjects\Name::new($name), 
      new DateTime()
    );
  }
}