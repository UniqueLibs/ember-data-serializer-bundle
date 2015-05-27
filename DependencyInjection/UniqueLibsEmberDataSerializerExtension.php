<?php

namespace UniqueLibs\EmberDataSerializerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class UniqueLibsEmberDataSerializerExtension
 *
 * @package UniqueLibs\EmberDataSerializerBundle\DependencyInjection
 * @author  Marvin Rind <marvin.rind@uniquelibs.com>
 */
class UniqueLibsEmberDataSerializerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
