<?php

namespace ContainerZISYkxh;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getBaseController4Service extends Apps_RestApi_KernelDevDebugContainer
{
    /**
     * Gets the public 'Apps\RestApi\Modules\Session\Controllers\BaseController' shared autowired service.
     *
     * @return \Apps\RestApi\Modules\Session\Controllers\BaseController
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/Modules/Session/Controllers/BaseController.php';

        $container->services['Apps\\RestApi\\Modules\\Session\\Controllers\\BaseController'] = $instance = new \Apps\RestApi\Modules\Session\Controllers\BaseController();

        $instance->setContainer(($container->privates['.service_locator.CshazM0'] ?? $container->load('get_ServiceLocator_CshazM0Service'))->withContext('Apps\\RestApi\\Modules\\Session\\Controllers\\BaseController', $container));

        return $instance;
    }
}
