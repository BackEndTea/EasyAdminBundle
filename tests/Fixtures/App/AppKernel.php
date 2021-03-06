<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

/**
 * The kernel used in the application of most functional tests.
 */
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new EasyCorp\Bundle\EasyAdminBundle\EasyAdminBundle(),
            new EasyCorp\Bundle\EasyAdminBundle\Tests\Fixtures\AppTestBundle\AppTestBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');

        if ($this->requiresAssetsConfig()) {
            $loader->load(function (ContainerBuilder $container) {
                $container->loadFromExtension('framework', [
                    'assets' => null,
                ]);
            });
        }

        if ($this->requiresTemplatingConfig()) {
            $loader->load(function (ContainerBuilder $container) {
                $container->loadFromExtension('framework', [
                    'templating' => [
                        'engines' => ['twig'],
                    ],
                ]);
            });
        }

        if ($this->requiresLogoutOnUserChange()) {
            $loader->load(function (ContainerBuilder $container) {
                $container->loadFromExtension('security', [
                    'firewalls' => [
                        'main' => [
                            'logout_on_user_change' => true,
                        ],
                    ],
                ]);
            });
        }
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return __DIR__.'/../../../build/cache/'.$this->getEnvironment();
    }

    /**
     * @return string
     */
    public function getLogDir()
    {
        return __DIR__.'/../../../build/kernel_logs/'.$this->getEnvironment();
    }

    protected function requiresAssetsConfig()
    {
        return (int) Kernel::MAJOR_VERSION >= 3;
    }

    protected function requiresTemplatingConfig()
    {
        return 2 === (int) Kernel::MAJOR_VERSION && 3 === (int) Kernel::MINOR_VERSION;
    }

    protected function requiresLogoutOnUserChange()
    {
        return (int) Kernel::VERSION_ID >= 30400;
    }
}
