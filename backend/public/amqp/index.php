<?php declare(strict_types=1);

require '../../../vendor/autoload.php';

use Bunny\Channel;
use Bunny\Client;
use Bunny\Message;
use Dotenv\Dotenv;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

use Apps\AMQP\App;

Dotenv::createUnsafeImmutable(__DIR__.'/../../', '.env.amqp')->load();

$connection = [
  'host' => $_ENV["RB_HOST"],
  'vhost' => $_ENV["RB_VHOST"],
  'user' => $_ENV["RB_USER"],
  'password' => $_ENV["RB_PASSWORD"],
];

$mail = new PHPMailer();
$mail->IsSMTP();

$mail->SMTPDebug = true;
$mail->SMTPAuth = true;
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

$mail->Port = $_ENV["SMTP_PORT"];
$mail->Host = $_ENV["SMTP_HOST"];
$mail->Username = $_ENV["SMTP_USER"];
$mail->Password = $_ENV["SMTP_PASSWORD"];

$bunny = new Client($connection);
$bunny->connect();

$channel = $bunny->channel();
$channel->queueDeclare($_ENV["NOTIFYING_QUEUE"]);
$channel->queueDeclare($_ENV["LOGGING_QUEUE"]);

$app = new Apps\AMQP\App(
  $mail,
  $channel,
  $_ENV["NOTIFYING_QUEUE"],
  $_ENV["LOGGING_QUEUE"],
  $_ENV["SMTP_USER"],
  $_ENV['ID_APPLICATION']
);

$app->run();
