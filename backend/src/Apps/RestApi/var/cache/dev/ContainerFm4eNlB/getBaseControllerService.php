<?php

namespace ContainerFm4eNlB;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getBaseControllerService extends Apps_RestApi_KernelDevDebugContainer
{
    /**
     * Gets the public 'Apps\RestApi\Modules\Account\Controllers\BaseController' shared autowired service.
     *
     * @return \Apps\RestApi\Modules\Account\Controllers\BaseController
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/Modules/Account/Controllers/BaseController.php';

        $container->services['Apps\\RestApi\\Modules\\Account\\Controllers\\BaseController'] = $instance = new \Apps\RestApi\Modules\Account\Controllers\BaseController();

        $instance->setContainer($container);

        return $instance;
    }
}
