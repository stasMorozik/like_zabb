<?php

namespace ContainerZISYkxh;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getCreatingService extends Apps_RestApi_KernelDevDebugContainer
{
    /**
     * Gets the private 'Apps\RestApi\Modules\ConfirmationCode\Services\Creating' shared autowired service.
     *
     * @return \Apps\RestApi\Modules\ConfirmationCode\Services\Creating
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/Modules/ConfirmationCode/Services/Creating.php';
        include_once \dirname(__DIR__, 6).'/Core/ConfirmationCode/UseCases/Creating.php';
        include_once \dirname(__DIR__, 6).'/Core/ConfirmationCode/Ports/Changing.php';
        include_once \dirname(__DIR__, 6).'/MySQLAdapters/ConfirmationCode/Creating.php';
        include_once \dirname(__DIR__, 6).'/Core/Common/Ports/Notifying.php';
        include_once \dirname(__DIR__, 6).'/AMQPAdapters/Notifying.php';
        include_once \dirname(__DIR__, 6).'/Core/ConfirmationCode/Ports/Getting.php';
        include_once \dirname(__DIR__, 6).'/MySQLAdapters/ConfirmationCode/Getting.php';

        return $container->privates['Apps\\RestApi\\Modules\\ConfirmationCode\\Services\\Creating'] = new \Apps\RestApi\Modules\ConfirmationCode\Services\Creating(new \Core\ConfirmationCode\UseCases\Creating(new \MySQLAdapters\ConfirmationCode\Creating(), ($container->privates['MySQLAdapters\\ConfirmationCode\\Getting'] ??= new \MySQLAdapters\ConfirmationCode\Getting()), new \AMQPAdapters\Notifying($container->getEnv('string:RB_HOST'), $container->getEnv('string:RB_VHOST'), $container->getEnv('string:RB_USER'), $container->getEnv('string:RB_PASSWORD'), $container->getEnv('string:NOTIFYING_QUEUE'))), ($container->privates['AMQPAdapters\\Logger'] ?? $container->load('getLoggerService')));
    }
}
