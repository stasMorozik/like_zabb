<?php

namespace ContainerFm4eNlB;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getCreating3Service extends Apps_RestApi_KernelDevDebugContainer
{
    /**
     * Gets the private 'Apps\RestApi\Modules\Account\Services\Creating' shared autowired service.
     *
     * @return \Apps\RestApi\Modules\Account\Services\Creating
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/Modules/Account/Services/Creating.php';
        include_once \dirname(__DIR__, 6).'/Core/Account/UseCases/Creating.php';
        include_once \dirname(__DIR__, 6).'/Core/Account/Ports/Changing.php';
        include_once \dirname(__DIR__, 6).'/MySQLAdapters/Account/Creating.php';
        include_once \dirname(__DIR__, 6).'/Core/Role/Ports/Getting.php';
        include_once \dirname(__DIR__, 6).'/MySQLAdapters/Role/Getting.php';
        include_once \dirname(__DIR__, 6).'/Core/ConfirmationCode/Ports/Getting.php';
        include_once \dirname(__DIR__, 6).'/MySQLAdapters/ConfirmationCode/Getting.php';

        return $container->privates['Apps\\RestApi\\Modules\\Account\\Services\\Creating'] = new \Apps\RestApi\Modules\Account\Services\Creating(new \Core\Account\UseCases\Creating($container->getEnv('string:PASSWORD_SALT'), new \MySQLAdapters\Account\Creating(), ($container->privates['MySQLAdapters\\ConfirmationCode\\Getting'] ??= new \MySQLAdapters\ConfirmationCode\Getting()), new \MySQLAdapters\Role\Getting()));
    }
}
