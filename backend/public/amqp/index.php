<?php declare(strict_types=1);

require '../../../vendor/autoload.php';

use Bunny\Channel;
use Bunny\Client;
use Dotenv\Dotenv;

use Apps\AMQP\App;
use SMTPAdapters\Notifying;

Dotenv::createUnsafeImmutable(__DIR__.'/../../', '.env.amqp')->load();

$connection = [
  'host' => $_ENV["RB_HOST"],
  'vhost' => $_ENV["RB_VHOST"],
  'user' => $_ENV["RB_USER"],
  'password' => $_ENV["RB_PASSWORD"],
];


$bunny = new Client($connection);
$bunny->connect();

$channel = $bunny->channel();
$channel->queueDeclare($_ENV["NOTIFYING_QUEUE"]);
$channel->queueDeclare($_ENV["LOGGING_QUEUE"]);

$app = new Apps\AMQP\App(
  new Notifying(
    $_ENV['SMTP_HOST'],
    (int) $_ENV['SMTP_PORT'],
    $_ENV['SMTP_USER'],
    $_ENV['SMTP_PASSWORD'],
    $_ENV['SMTP_EMAIL']
  ),
  $channel,
  $_ENV["NOTIFYING_QUEUE"],
  $_ENV["LOGGING_QUEUE"],
  $_ENV["SMTP_USER"],
  $_ENV['ID_APPLICATION']
);

$app->run();
