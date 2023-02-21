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

class Entity extends Core\Common\Entity
{
  protected Core\Common\ValueObjects\Email $email;

  protected function __construct(
    string $id,
    DateTime $created,
    Core\Common\ValueObjects\Email $email
  )
  {
    $this->email = $email;
    parent::__construct($id, $created);
  }

  public function getEmail(): Core\Common\ValueObjects\Email
  {
    return $this->email;
  }

  public static function new(array $args): Entity
  {
    if (!isset($args['code'])) {
      return new Core\Common\Errors\Domain('Invalid argument');
    }

    if (($args['code'] instanceof Core\ConfirmationCode\Entity) == false) {
      return new Core\Common\Errors\Domain('Invalid code');
    }

    return new Entity(
      Uuid::uuid4()->toString(),
      new DateTime(),
      $args['code']->getEmail()
    );
  }
}
