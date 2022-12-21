<?php declare(strict_types=1);

namespace Core\ConfirmationCode;

use Core;
use Ramsey\Uuid\Uuid;

/**
 *
 * Entity Confirmation Code
 *  
**/

class Entity 
{
  protected string $id;
  protected Core\Common\ValueObjects\Email $email;
  protected int $created;
  protected int $code;
  protected bool $confirmed;

  protected function __construct(
    string $id,
    Core\Common\ValueObjects\Email $email,
    int $created,
    int $code,
    bool $confirmed
  )
  {
    $this->id = $id;
    $this->email = $email;
    $this->created = $created;
    $this->code = $code;
    $this->confirmed = $confirmed;
  }

  public function getId(): string 
  {
    return $this->id;
  }

  public function getEmail(): Core\Common\ValueObjects\Email 
  {
    return $this->email;
  }

  public function getCode(): int 
  {
    return $this->code;
  }

  public function getCreated(): int 
  {
    return $this->created;
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

  public static function new(
    Core\Common\ValueObjects\Email $email
  ): Entity 
  {
    return new Entity(
      Uuid::uuid4()->toString(),
      $email,
      time() + 900,
      rand(1000, 9999),
      false
    );
  }

  //Validate code lifetime
  private function validateLifetime(): Core\Common\Errors\Domain | bool
  {
    if (time() >= $this->created) {
      return new Core\Common\Errors\Domain('Invalid code expiration time');
    }

    return true;
  }

  //Validate code
  private function validateCode(int $code): Core\Common\Errors\Domain | bool
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