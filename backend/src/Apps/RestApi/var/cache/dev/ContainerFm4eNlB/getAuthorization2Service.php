<?php

namespace ContainerFm4eNlB;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getAuthorization2Service extends Apps_RestApi_KernelDevDebugContainer
{
    /**
     * Gets the private 'Core\User\UseCases\Authorization' shared autowired service.
     *
     * @return \Core\User\UseCases\Authorization
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/Core/User/UseCases/Authorization.php';
        include_once \dirname(__DIR__, 6).'/Core/User/Ports/GettingById.php';
        include_once \dirname(__DIR__, 6).'/MySQLAdapters/User/GettingById.php';

        return $container->privates['Core\\User\\UseCases\\Authorization'] = new \Core\User\UseCases\Authorization($container->getEnv('string:ACCESS_TOKEN_SALT'), new \MySQLAdapters\User\GettingById());
    }
}
