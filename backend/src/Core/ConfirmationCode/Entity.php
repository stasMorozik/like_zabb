<?php declare(strict_types=1);

namespace Core\ConfirmationCode;

use Core;
use DateTime;
use Ramsey\Uuid\Uuid;

/**
 *
 * Entity Confirmation Code
 *
**/

class Entity extends Core\Common\Entity
{
  protected Core\Common\ValueObjects\Email $email;
  protected int $created_time;
  protected int $code;
  protected bool $confirmed;

  protected function __construct(
    string $id,
    DateTime $created,
    int $created_time,
    int $code,
    bool $confirmed,
    Core\Common\ValueObjects\Email $email
  )
  {
    $this->email = $email;
    $this->created_time = $created_time;
    $this->code = $code;
    $this->confirmed = $confirmed;
    parent::__construct($id, $created);
  }

  public function getEmail(): Core\Common\ValueObjects\Email
  {
    return $this->email;
  }

  public function getCode(): int
  {
    return $this->code;
  }

  public function getCreatedTime(): int
  {
    return $this->created_time;
  }

  public function getConfirmed(): bool
  {
    return $this->confirmed;
  }

  public function isConfirmed(): bool | Core\Common\Errors\Domain
  {
    if (!$this->confirmed) {
      return new Core\Common\Errors\Domain('Code not confirmed');;
    }

    return true;
  }

  public static function new(array $args): Entity
  {
    if (!isset($args['email'])) {
      return new Core\Common\Errors\Domain('Invalid argument');
    }

    if (($args['email'] instanceof Core\Common\ValueObjects\Email) == false) {
      return new Core\Common\Errors\Domain('Invalid code');
    }

    return new Entity(
      Uuid::uuid4()->toString(),
      new DateTime(),
      time() + 900,
      rand(1000, 9999),
      false,
      $args['email']
    );
  }

  //Validate code lifetime
  private function validateLifetime(): Core\Common\Errors\Domain | bool
  {
    if (time() >= $this->created_time) {
      return new Core\Common\Errors\Domain('Invalid code expiration time');
    }

    return true;
  }

  //Validate code
  private function validateCode(?int $code): Core\Common\Errors\Domain | bool
  {
    if ($code != $this->code) {
      return new Core\Common\Errors\Domain('Wrong code');
    }

    return true;
  }

  public function confirm(?int $code): Core\Common\Errors\Domain | bool
  {
    $maybe_true = $this->validateLifetime();
    if ($maybe_true instanceof Core\Common\Errors\Domain) {
      return $maybe_true;
    }

    $maybe_true = $this->validateCode($code);
    if ($maybe_true instanceof Core\Common\Errors\Domain) {
      return $maybe_true;
    }

    $this->confirmed = true;

    return true;
  }

  public function checkLifetime(): Core\Common\Errors\Domain | bool
  {
    if ($this->validateLifetime() === true) {
      return new Core\Common\Errors\Domain('You already have a confirmation code');
    }

    return true;
  }
}
