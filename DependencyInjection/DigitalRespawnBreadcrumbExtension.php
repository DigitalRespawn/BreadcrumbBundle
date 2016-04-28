<?php

namespace DigitalRespawn\BreadcrumbBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * @author Vincent MARIUS <vincent.marius@digitalrespawn.com>
 */
class DigitalRespawnBreadcrumbExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

		$container->setParameter('digitalrespawn.breadcrumb', $config);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

	public function getAlias()
	{
		return 'digitalrespawn_breadcrumb';
	}
}
