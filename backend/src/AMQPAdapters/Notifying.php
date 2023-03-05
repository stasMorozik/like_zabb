<?php declare(strict_types=1);

namespace AMQPAdapters;

use Bunny\Client;
use Bunny\Channel;
use Core;

class Notifying implements Core\Common\Ports\Notifying
{
  private string $queue;
  private Channel $channel;
  private Client $client;

  public function __construct(
    string $host,
    string $virtual_host,
    string $user,
    string $password,
    string $queue
  )
  {
    $this->client = new Client([
      'host' => $host,
      'vhost' => $virtual_host,
      'user' => $user,
      'password' => $password,
    ]);
    $this->client->connect();

    $this->channel = $this->client->channel();
    $this->channel->queueDeclare($queue);
    $this->queue = $queue;
  }

  public function __destruct()
  {
    $this->client->disconnect();
  }

  public function notify(
    Core\Common\ValueObjects\Email $email,
    string $subject,
    string $message
  ): Core\Common\Errors\InfraStructure | bool
  {
    try {
      $this->channel->publish(json_encode([
        'email' => $email->getValue(),
        'subject' => $subject,
        'message' => $message
      ]), [], '', $this->queue);

      return true;
    } catch(Exception $e) {
      return new Core\Common\Errors\InfraStructure($e->getMessage());
    }
  }
}
