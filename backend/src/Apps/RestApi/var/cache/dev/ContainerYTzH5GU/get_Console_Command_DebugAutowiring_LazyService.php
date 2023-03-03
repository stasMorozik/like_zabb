<?php

namespace ContainerYTzH5GU;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class get_Console_Command_DebugAutowiring_LazyService extends Apps_RestApi_KernelDevDebugContainer
{
    /**
     * Gets the private '.console.command.debug_autowiring.lazy' shared service.
     *
     * @return \Symfony\Component\Console\Command\LazyCommand
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['.console.command.debug_autowiring.lazy'] = new \Symfony\Component\Console\Command\LazyCommand('debug:autowiring', [], 'List classes/interfaces you can use for autowiring', false, #[\Closure(name: 'console.command.debug_autowiring', class: 'Symfony\\Bundle\\FrameworkBundle\\Command\\DebugAutowiringCommand')] function () use ($container): \Symfony\Bundle\FrameworkBundle\Command\DebugAutowiringCommand {
            return ($container->privates['console.command.debug_autowiring'] ?? $container->load('getConsole_Command_DebugAutowiringService'));
        });
    }
}
