<?php declare(strict_types=1);


use Apps\RestApi\Modules\User\Controllers\BaseController as UserBaseController;
use Apps\RestApi\Modules\Session\Controllers\BaseController as SessionBaseController;
use Apps\RestApi\Modules\Sensor\Controllers\BaseController as SensorBaseController;
use Apps\RestApi\Modules\ConfirmationCode\Controllers\BaseController as ConfirmationCodeBaseController;
use Apps\RestApi\Modules\Account\Controllers\BaseController as AccountBaseController;

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
  $routes->add('api_users_authenticate', '/api/users/authenticate')
    ->controller([UserBaseController::class, 'authenticate'])
    ->methods(['POST']);

  $routes->add('api_users_authorize', '/api/users/authorize')
    ->controller([UserBaseController::class, 'authorize'])
    ->methods(['GET']);

  $routes->add('api_session_refresh', '/api/sessions/refresh')
    ->controller([SessionBaseController::class, 'refresh'])
    ->methods(['GET']);

  $routes->add('api_session_quit', '/api/sessions/quit')
    ->controller([SessionBaseController::class, 'quit'])
    ->methods(['DELETE']);

  $routes->add('api_sensors_create', '/api/sensors/')
    ->controller([SensorBaseController::class, 'create'])
    ->methods(['POST']);

  $routes->add('api_confirmation_codes_create', '/api/confirmation-codes/')
    ->controller([ConfirmationCodeBaseController::class, 'create'])
    ->methods(['POST']);

  $routes->add('api_confirmation_codes_confirm', '/api/confirmation-codes/')
    ->controller([ConfirmationCodeBaseController::class, 'confirm'])
    ->methods(['PUT']);

  $routes->add('api_account_create', '/api/accounts/')
    ->controller([AccountBaseController::class, 'create'])
    ->methods(['POST']);
};

