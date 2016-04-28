<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * @author Vincent MARIUS <vincent.marius@digitalrespawn.com>
 */
class AppKernel extends Kernel
{
	public function registerBundles()
	{
		$bundles = array(
			new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
			new Symfony\Bundle\TwigBundle\TwigBundle(),
			new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
			new DigitalRespawn\BreadcrumbBundle\DigitalRespawnBreadcrumbBundle(),
			new DigitalRespawn\BreadcrumbBundle\Tests\Fixtures\DigitalRespawnBreadcrumbTestBundle(),
		);

		return $bundles;
	}

	public function registerContainerConfiguration(LoaderInterface $loader)
	{
		$loader->load(__DIR__.'/config/config.yml');
	}

	/**
	 * @return string
	 */
	public function getCacheDir()
	{
		return sys_get_temp_dir().'/'.Kernel::VERSION.'/digitalrespawn-breadcrumb/cache/test';
	}

	/**
	 * @return string
	 */
	public function getLogDir()
	{
		return sys_get_temp_dir().'/'.Kernel::VERSION.'/digitalrespawn-breadcrumb/logs';
	}
}