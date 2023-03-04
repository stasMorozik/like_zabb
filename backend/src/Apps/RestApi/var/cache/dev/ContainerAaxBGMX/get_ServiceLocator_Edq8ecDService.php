<?php

namespace ContainerAaxBGMX;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class get_ServiceLocator_Edq8ecDService extends Apps_RestApi_KernelDevDebugContainer
{
    /**
     * Gets the private '.service_locator.edq8ecD' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['.service_locator.edq8ecD'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService, [
            '_authentication_service' => ['privates', 'Apps\\RestApi\\Modules\\User\\Services\\Authentication', 'getAuthenticationService', true],
        ], [
            '_authentication_service' => 'Apps\\RestApi\\Modules\\User\\Services\\Authentication',
        ]);
    }
}