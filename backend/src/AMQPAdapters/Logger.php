<?php declare(strict_types=1);

namespace AMQPAdapters;

use Bunny\Client;
use Bunny\Channel;
use Exception;
use Core;

class Logger
{
  private string $queue;
  private string $id_app;
  private Channel $channel;
  private Client $client;

  public function __construct(
    string $host,
    string $virtual_host,
    string $user,
    string $password,
    string $queue,
    string $id_app
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
    $this->id_app = $id_app;
  }

  public function __destruct()
  {
    $this->client->disconnect();
  }

  public function info(array $args): void
  {
    $this->pulish(array_merge($args, ['type' => 'info']));
  }

  public function debug(array $args): void
  {
    $this->pulish(array_merge($args, ['type' => 'debug']));
  }

  public function warn(array $args): void
  {
    $this->pulish(array_merge($args, ['type' => 'warning']));
  }

  private function pulish(array $args): void
  {
    try {
      $this->channel->publish(json_encode([
        'type' => $args['type'],
        'message' => $args['message'],
        'date' => date('Y-m-d H:i:s'),
        'id_application' => $this->id_app,
        'payload' => $args['payload']
      ]), [], '', $this->queue);
    } catch(Exception $e) {

    }
  }
}
