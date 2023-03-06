<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Core;
use MySQLAdapters;
use AMQPAdapters;
use Apps;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

return function(ContainerConfigurator $containerConfigurator) {
  $services = $containerConfigurator
    ->services()
    ->defaults()
    ->autowire()
    ->autoconfigure();

  $services
    ->load('Apps\\RestApi\\Modules\\Common\\Controllers\\', '../Modules/Common/Controllers/*')
    ->tag('controller.service_arguments');

  $services
    ->load('Apps\\RestApi\\Modules\\Account\\Controllers\\', '../Modules/Account/Controllers/*')
    ->tag('controller.service_arguments');

  $services
    ->load('Apps\\RestApi\\Modules\\ConfirmationCode\\Controllers\\', '../Modules/ConfirmationCode/Controllers/*')
    ->tag('controller.service_arguments');

  $services
    ->load('Apps\\RestApi\\Modules\\Sensor\\Controllers\\', '../Modules/Sensor/Controllers/*')
    ->tag('controller.service_arguments');

  $services
    ->load('Apps\\RestApi\\Modules\\Session\\Controllers\\', '../Modules/Session/Controllers/*')
    ->tag('controller.service_arguments');

  $services
    ->load('Apps\\RestApi\\Modules\\User\\Controllers\\', '../Modules/User/Controllers/*')
    ->tag('controller.service_arguments');

  $services->set(MySQLAdapters\Account\Creating::class);

  $services->set(MySQLAdapters\ConfirmationCode\Confirming::class);

  $services->set(MySQLAdapters\ConfirmationCode\Creating::class);

  $services->set(MySQLAdapters\ConfirmationCode\Getting::class);

  $services->set(MySQLAdapters\Role\Getting::class);

  $services->set(MySQLAdapters\Sensor\Creating::class);

  $services->set(MySQLAdapters\User\Getting::class);

  $services->set(MySQLAdapters\User\GettingById::class);

  $services->set(AMQPAdapters\Notifying::class)
    ->autowire(true)
    ->args([
      '%env(string:RB_HOST)%',
      '%env(string:RB_VHOST)%',
      '%env(string:RB_USER)%',
      '%env(string:RB_PASSWORD)%',
      '%env(string:NOTIFYING_QUEUE)%'
    ]);

  $services->set(AMQPAdapters\Logger::class)
    ->args([
      '%env(string:RB_HOST)%',
      '%env(string:RB_VHOST)%',
      '%env(string:RB_USER)%',
      '%env(string:RB_PASSWORD)%',
      '%env(string:LOGGING_QUEUE)%',
      '%env(string:ID_APPLICATION)%',
    ]);

  $services->set(Core\Account\UseCases\Creating::class)
    ->args([
      '%env(string:PASSWORD_SALT)%',
      service(MySQLAdapters\Account\Creating::class),
      service(MySQLAdapters\ConfirmationCode\Getting::class),
      service(MySQLAdapters\Role\Getting::class)
    ]);

  $services->set(Core\ConfirmationCode\UseCases\Confirming::class)
    ->args([
      service(MySQLAdapters\ConfirmationCode\Confirming::class),
      service(MySQLAdapters\ConfirmationCode\Getting::class)
    ]);

  $services->set(Core\ConfirmationCode\UseCases\Creating::class)
    ->args([
      service(MySQLAdapters\ConfirmationCode\Creating::class),
      service(MySQLAdapters\ConfirmationCode\Getting::class),
      service(AMQPAdapters\Notifying::class),
    ]);

  $services->set(Core\Sensor\UseCases\Creating::class)
    ->args([
      service(MySQLAdapters\Sensor\Creating::class),
      service(Core\User\UseCases\Authorization::class)
    ]);

  $services->set(Core\Session\UseCases\Refreshing::class)
    ->args([
      '%env(string:ACCESS_TOKEN_SALT)%',
      '%env(string:REFRESH_TOKEN_SALT)%'
    ]);

  $services->set(Core\User\UseCases\Authentication::class)
    ->args([
      '%env(string:PASSWORD_SALT)%',
      '%env(string:ACCESS_TOKEN_SALT)%',
      '%env(string:REFRESH_TOKEN_SALT)%',
      service(MySQLAdapters\User\Getting::class)
    ]);

  $services->set(Core\User\UseCases\Authorization::class)
    ->args([
      '%env(string:ACCESS_TOKEN_SALT)%',
      service(MySQLAdapters\User\GettingById::class)
    ]);

  $services->set(Apps\RestApi\Modules\Account\Services\Creating::class)
    ->args([
      service(Core\Account\UseCases\Creating::class),
      service(AMQPAdapters\Logger::class)
    ]);

  $services->set(Apps\RestApi\Modules\ConfirmationCode\Services\Confirming::class)
    ->args([
      service(Core\ConfirmationCode\UseCases\Confirming::class),
      service(AMQPAdapters\Logger::class)
    ]);

  $services->set(Apps\RestApi\Modules\ConfirmationCode\Services\Creating::class)
    ->args([
      service(Core\ConfirmationCode\UseCases\Creating::class),
      service(AMQPAdapters\Logger::class)
    ]);

  $services->set(Apps\RestApi\Modules\Sensor\Services\Creating::class)
    ->args([
      service(Core\Sensor\UseCases\Creating::class),
      service(AMQPAdapters\Logger::class)
    ]);

  $services->set(Apps\RestApi\Modules\Session\Services\Refreshing::class)
    ->args([
      service(Core\Session\UseCases\Refreshing::class),
      service(AMQPAdapters\Logger::class)
    ]);

  $services->set(Apps\RestApi\Modules\User\Services\Authentication::class)
    ->args([
      service(Core\User\UseCases\Authentication::class),
      service(AMQPAdapters\Logger::class)
    ]);

  $services->set(Apps\RestApi\Modules\User\Services\Authorization::class)
    ->args([
      service(Core\User\UseCases\Authorization::class),
      service(AMQPAdapters\Logger::class)
    ]);
};
