<?php declare(strict_types=1);

namespace Apps\AMQP;

use SMTPAdapters\Notifying;
use Core\Common\ValueObjects\Email;
use Core\Common\Errors\InfraStructure;
use Core\Common\Errors\Domain;
use Bunny\Channel;
use Bunny\Message;
use Bunny\Client;

class App
{
  private Notifying $mailer;
  private Channel $amqp_channel;
  private string $notifying_queue;
  private string $logging_queue;
  private string $smtp_user;
  private string $id_app;

  public function __construct(
    Notifying $mailer,
    Channel $amqp_channel,
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
      "date" => date("Y-m-d H:i:s"),
      "payload" => $args['payload']
    ]), [], "", $this->logging_queue);
  }

  public function send_mail_function($args): void
  {
    $maybe_true = $this->mailer->notify(
      $args['email'],
      $args['subject'],
      $args['message'],
    );

    if ($maybe_true instanceof InfraStructure) {
      $this->publish_function([
        "type" => "warning",
        "message" => "{$maybe_true->getMessage()}. Queue - queue. Id application - {$this->id_app}. Payload - {$args['email']->getValue()}",
        "payload" => $args['email']->getValue()
      ]);
    }

    if ($maybe_true === true) {
      $this->publish_function([
        "type" => "info",
        "message" => "Sent email. Queue - queue. Id application - {$this->id_app}. Payload - {$args['email']->getValue()}",
        "payload" => $args['email']->getValue()
      ]);
    }
  }

  public function run(): void
  {
    $self = $this;

    $this->amqp_channel->run(
      function (Message $message, Channel $channel, Client $bunny) use($self) {
        $obj = json_decode($message->{'content'});

        if(!is_object($obj)) {
          $self->publish_function([
            "type" => "info",
            "message" => "Invalid json. Queue - {$this->logging_queue}. Id application - {$this->id_app}. Payload - {$message->{'content'}}",
            "payload" => $message->{'content'}
          ]);
        }

        if (is_object($obj)) {
          if(!isset($obj->{'email'}) || !isset($obj->{'subject'}) || !isset($obj->{'message'})) {
            $self->publish_function([
              "type" => "info",
              "message" => "Invalid json. Queue - {$this->logging_queue}. Id application - {$this->id_app}. Payload - {$message->{'content'}}",
              "payload" => $message->{'content'}
            ]);
          }

          if (isset($obj->{'email'}) && isset($obj->{'subject'}) && isset($obj->{'message'})) {
            $maybe_email = Email::new(['email' => $obj->{'email'}]);

            if ($maybe_email instanceof Domain) {
              $self->publish_function([
                "type" => "info",
                "message" => "{$maybe_email->getMessage()}. Queue - {$this->logging_queue}. Id application - {$this->id_app}. Payload - {$message->{'content'}}",
                "payload" => $message->{'content'}
              ]);
            }

            if ($maybe_email instanceof Email) {
              $self->send_mail_function([
                "email" => $maybe_email,
                "subject" => $obj->{"subject"},
                "message" => $obj->{"message"}
              ]);
            }
          }
        }

        $channel->ack($message);
      },
      $this->notifying_queue
    );
  }
}
