<?php declare(strict_types=1);

namespace Core\Common;

use Core;
use DateTime;

abstract class Entity
{
  private string $id;
  private DateTime $created;

  public function __construct($id, $created)
  {
    $this->id = $id;
    $this->created = $created;
  }

  public function getId(): string
  {
    return $this->id;
  }

  public function getCreated(): DateTime
  {
    return $this->created;
  }

  abstract public static function new(array $args):  Entity | Core\Common\Errors\Domain;
}
