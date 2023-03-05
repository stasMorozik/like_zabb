<?php

namespace ContainerB5IdfhL;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class get_ServiceLocator_Z5iDYiJService extends Apps_RestApi_KernelDevDebugContainer
{
    /**
     * Gets the private '.service_locator.Z5iDYiJ' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['.service_locator.Z5iDYiJ'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService, [
            '_confirming_service' => ['privates', 'Apps\\RestApi\\Modules\\ConfirmationCode\\Services\\Confirming', 'getConfirmingService', true],
        ], [
            '_confirming_service' => 'Apps\\RestApi\\Modules\\ConfirmationCode\\Services\\Confirming',
        ]);
    }
}
