<?php declare(strict_types=1);

if (PHP_SAPI == 'cli-server') {
  $url  = parse_url($_SERVER['REQUEST_URI']);
  $file = __DIR__ . $url['path'];
  if (is_file($file)) return false;
}

require "../../../vendor/autoload_runtime.php";

use Apps\RestApi\Kernel;

$_SERVER['APP_RUNTIME_OPTIONS']['dotenv_path'] = '.env.rest';

return function (array $context) {
  return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
