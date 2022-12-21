<?php declare(strict_types=1);

namespace SMTPAdapters;

use Core;

class Notifying implements Core\Common\Ports\Notifying
{
  private string $_url;
  private int $_port;
  private string $_user;
  private string $_password;

  public function __construct(
    string $url,
    int $port,
    string $user,
    string $password
  )
  {
    $this->_url = $url;
    $this->_port = $port;
    $this->_user = $user;
    $this->_password = $password;
  }

  public function notify(Core\Common\ValueObjects\Email $email, string $message): void
  {
    
  }
}