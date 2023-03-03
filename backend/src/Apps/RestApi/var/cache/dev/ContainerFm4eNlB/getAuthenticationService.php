<?php

namespace ContainerFm4eNlB;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getAuthenticationService extends Apps_RestApi_KernelDevDebugContainer
{
    /**
     * Gets the private 'Apps\RestApi\Modules\User\Services\Authentication' shared autowired service.
     *
     * @return \Apps\RestApi\Modules\User\Services\Authentication
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/Modules/User/Services/Authentication.php';
        include_once \dirname(__DIR__, 6).'/Core/User/UseCases/Authentication.php';
        include_once \dirname(__DIR__, 6).'/Core/User/Ports/Getting.php';
        include_once \dirname(__DIR__, 6).'/MySQLAdapters/User/Getting.php';

        return $container->privates['Apps\\RestApi\\Modules\\User\\Services\\Authentication'] = new \Apps\RestApi\Modules\User\Services\Authentication(new \Core\User\UseCases\Authentication($container->getEnv('string:PASSWORD_SALT'), $container->getEnv('string:ACCESS_TOKEN_SALT'), $container->getEnv('string:REFRESH_TOKEN_SALT'), new \MySQLAdapters\User\Getting()));
    }
}
