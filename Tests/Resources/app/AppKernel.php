<?php

use Symfony\Cmf\Component\Testing\HttpKernel\TestKernel;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Class AppKernel
 *
 * @package UniqueLibs\EmberDataSerializerBundle\Tests\Resources\app
 * @author  Marvin Rind <marvin.rind@uniquelibs.com>
 */
class AppKernel extends TestKernel
{
    public function configure()
    {
        $this->addBundles(array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new UniqueLibs\EmberDataSerializerBundle\UniqueLibsEmberDataSerializerBundle(),
        ));
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config.php');
    }
}