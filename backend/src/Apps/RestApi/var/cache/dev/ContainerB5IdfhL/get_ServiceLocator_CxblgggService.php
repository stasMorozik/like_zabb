<?php

namespace ContainerB5IdfhL;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class get_ServiceLocator_CxblgggService extends Apps_RestApi_KernelDevDebugContainer
{
    /**
     * Gets the private '.service_locator.Cxblggg' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['.service_locator.Cxblggg'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService, [
            '_refreshing_service' => ['privates', 'Apps\\RestApi\\Modules\\Session\\Services\\Refreshing', 'getRefreshingService', true],
        ], [
            '_refreshing_service' => 'Apps\\RestApi\\Modules\\Session\\Services\\Refreshing',
        ]);
    }
}
