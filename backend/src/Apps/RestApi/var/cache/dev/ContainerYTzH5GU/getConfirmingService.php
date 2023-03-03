<?php

namespace ContainerYTzH5GU;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getConfirmingService extends Apps_RestApi_KernelDevDebugContainer
{
    /**
     * Gets the private 'Apps\RestApi\Modules\ConfirmationCode\Services\Confirming' shared autowired service.
     *
     * @return \Apps\RestApi\Modules\ConfirmationCode\Services\Confirming
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/Modules/ConfirmationCode/Services/Confirming.php';
        include_once \dirname(__DIR__, 6).'/Core/ConfirmationCode/UseCases/Confirming.php';
        include_once \dirname(__DIR__, 6).'/Core/ConfirmationCode/Ports/Changing.php';
        include_once \dirname(__DIR__, 6).'/MySQLAdapters/ConfirmationCode/Confirming.php';
        include_once \dirname(__DIR__, 6).'/Core/ConfirmationCode/Ports/Getting.php';
        include_once \dirname(__DIR__, 6).'/MySQLAdapters/ConfirmationCode/Getting.php';

        return $container->privates['Apps\\RestApi\\Modules\\ConfirmationCode\\Services\\Confirming'] = new \Apps\RestApi\Modules\ConfirmationCode\Services\Confirming(new \Core\ConfirmationCode\UseCases\Confirming(new \MySQLAdapters\ConfirmationCode\Confirming(), ($container->privates['MySQLAdapters\\ConfirmationCode\\Getting'] ??= new \MySQLAdapters\ConfirmationCode\Getting())));
    }
}
