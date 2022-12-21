<?php declare(strict_types=1);

require_once('./vendor/autoload_runtime.php');

use RestApi\Kernel;

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};