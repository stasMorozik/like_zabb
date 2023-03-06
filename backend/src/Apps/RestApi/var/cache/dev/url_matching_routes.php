<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/api/users/authenticate' => [[['_route' => 'api_users_authenticate', '_controller' => ['Apps\\RestApi\\Modules\\User\\Controllers\\BaseController', 'authenticate']], null, ['POST' => 0], null, false, false, null]],
        '/api/users/authorize' => [[['_route' => 'api_users_authorize', '_controller' => ['Apps\\RestApi\\Modules\\User\\Controllers\\BaseController', 'authorize']], null, ['GET' => 0], null, false, false, null]],
        '/api/sessions/refresh' => [[['_route' => 'api_session_refresh', '_controller' => ['Apps\\RestApi\\Modules\\Session\\Controllers\\BaseController', 'refresh']], null, ['GET' => 0], null, false, false, null]],
        '/api/sessions/quit' => [[['_route' => 'api_session_quit', '_controller' => ['Apps\\RestApi\\Modules\\Session\\Controllers\\BaseController', 'quit']], null, ['DELETE' => 0], null, false, false, null]],
        '/api/sensors' => [[['_route' => 'api_sensors_create', '_controller' => ['Apps\\RestApi\\Modules\\Sensor\\Controllers\\BaseController', 'create']], null, ['POST' => 0], null, true, false, null]],
        '/api/confirmation-codes' => [
            [['_route' => 'api_confirmation_codes_create', '_controller' => ['Apps\\RestApi\\Modules\\ConfirmationCode\\Controllers\\BaseController', 'create']], null, ['POST' => 0], null, true, false, null],
            [['_route' => 'api_confirmation_codes_confirm', '_controller' => ['Apps\\RestApi\\Modules\\ConfirmationCode\\Controllers\\BaseController', 'confirm']], null, ['PUT' => 0], null, true, false, null],
        ],
        '/api/accounts' => [[['_route' => 'api_account_create', '_controller' => ['Apps\\RestApi\\Modules\\Account\\Controllers\\BaseController', 'create']], null, ['POST' => 0], null, true, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [
            [['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
