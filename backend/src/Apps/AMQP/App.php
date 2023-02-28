<?php declare(strict_types=1);

namespace Apps\AMQP;

use Bunny;
use PHPMailer;

class App
{
  private PHPMailer\PHPMailer\PHPMailer $mailer;
  private Bunny\Channel $amqp_channel;
  private string $notifying_queue;
  private string $logging_queue;
  private string $smtp_user;
  private string $id_app;

  public function __construct(
    PHPMailer\PHPMailer\PHPMailer $mailer,
    Bunny\Channel $amqp_channel,
    string $notifying_queue,
    string $logging_queue,
    string $smtp_user,
    string $id_app
  )
  {
    $this->mailer = $mailer;
    $this->amqp_channel = $amqp_channel;
    $this->notifying_queue = $notifying_queue;
    $this->logging_queue = $logging_queue;
    $this->smtp_user = $smtp_user;
    $this->id_app = $id_app;
  }

  public function publish_function($args): void
  {
    $this->amqp_channel->publish(json_encode([
      "type" => $args["type"],
      "message" => $args["message"],
      "date" => date("Y-m-d H:i:s")
    ]), [], "", $this->logging_queue);
  }

  public function send_mail_function($args): void
  {
    $mail->setFrom($this->smtp_user, "");
    $mail->addAddress($args["to"]);

    $mail->isHTML(true);
    $mail->Subject = $args["subject"];
    $mail->Body = $args["message"];

    try {
      $mail->send();

      $this->publish_function([
        "type" => "info",
        "message" => "Sent email. Queue - queue. Id application - id. Payload - {$args['to']}"
      ]);
    } catch (Exception $e) {
      $this->publish_function([
        "type" => "warning",
        "message" => "{$mail->ErrorInfo}. Queue - queue. Id application - id. Payload - {$args['to']}"
      ]);
    }
  }

  public function run(): void
  {
    $self = $this;

    $this->amqp_channel->run(
      function (Bunny\Message $message, Bunny\Channel $channel, Bunny\Client $bunny) use($self) {
        $obj = json_decode($message->{'content'});

        if(!is_object($obj)) {
          $self->publish_function([
            "type" => "info",
            "message" => "Invalid json. Queue - {$_ENV['LOGGING_QUEUE']}. Id application - {$_ENV['ID_APPLICATION']}. Payload - {$message->{'content'}}"
          ]);
        }

        if (is_object($obj)) {
          if(!isset($obj->{'email'}) || !isset($obj->{'subject'}) || !isset($obj->{'message'})) {
            $self->publish_function([
              "type" => "info",
              "message" => "Invalid json. Queue - {$_ENV['LOGGING_QUEUE']}. Id application - {$_ENV['ID_APPLICATION']}. Payload - {$message->{'content'}}"
            ]);
          }

          if (isset($obj->{'email'}) && isset($obj->{'subject'}) && isset($obj->{'message'})) {
            $result_validation = filter_var($obj->{'email'}, FILTER_VALIDATE_EMAIL);

            if (!$result_validation) {
              $self->publish_function([
                "type" => "info",
                "message" => "Invalid email address. Queue - {$_ENV['LOGGING_QUEUE']}. Id application - {$_ENV['ID_APPLICATION']}. Payload - {$message->{'content'}}"
              ]);
            }

            if ($result_validation) {
              $self->send_mail_function([
                "to" => $obj->{"email"},
                "subject" => $obj->{"subject"},
                "message" => $obj->{"message"}
              ]);
            }
          }

          $channel->ack($message);
        }
      },
      $this->notifying_queue
    );
  }
}
