<?php declare(strict_types=1);

if (PHP_SAPI == 'cli-server') {
  $url  = parse_url($_SERVER['REQUEST_URI']);
  $file = __DIR__ . $url['path'];
  if (is_file($file)) return false;
}

require "../../../vendor/autoload_runtime.php";

use Apps\RestApi\Kernel;
use MySQLAdapters\DBFactory;
use Dotenv\Dotenv;

Dotenv::createUnsafeImmutable(__DIR__ . '/../../', '.env.rest')->load();

$_SERVER['APP_RUNTIME_OPTIONS']['dotenv_path'] = '.env.rest';

new MySQLAdapters\DBFactory(
  $_ENV["DB_USER"],
  $_ENV["DB_PASSWORD"],
  $_ENV["DB_NAME"],
  $_ENV["DB_HOST"],
  (int) $_ENV["DB_PORT"]
);


return function (array $context) {
  return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
