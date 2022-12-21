<?php declare(strict_types=1);

namespace Core\Common\Errors;

class Common
{
  private string $message;

  function __construct(string $message)
  {
    $this->message = $message;
  }

  public function getMessage(): string
  {
    return $this->message;
  }
}