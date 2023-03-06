<?php declare(strict_types=1);

namespace SMTPAdapters;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use Core;

class Notifying implements Core\Common\Ports\Notifying
{
  private string $host;
  private string $user_name;
  private string $password;
  private string $from;
  private int $port;

  public function __construct(
    string $host,
    int $port,
    string $user_name,
    string $password,
    string $from
  ){
    $this->host = $host;
    $this->port = $port;
    $this->user_name = $user_name;
    $this->password = $password;
    $this->from = $from;
  }

  public function notify(
    Core\Common\ValueObjects\Email $email,
    string $subject,
    string $message
  ): Core\Common\Errors\InfraStructure | bool
  {
    try {
      $mail = new PHPMailer(true);

      $mail->SMTPDebug = false;
      $mail->isSMTP();
      $mail->Host = $this->host;
      $mail->SMTPAuth = true;
      $mail->Username = $this->user_name;
      $mail->Password = $this->password;
      $mail->Port = $this->port;

      $mail->setFrom($this->from, 'Mailer');

      $mail->isHTML(true);

      $mail->addAddress($email->getValue());
      $mail->Subject = $subject;
      $mail->Body    = $message;

      $mail->send();

      return true;
    } catch(Exception $e) {
      return new Core\Common\Errors\InfraStructure($e->getMessage());
    }
  }
}
