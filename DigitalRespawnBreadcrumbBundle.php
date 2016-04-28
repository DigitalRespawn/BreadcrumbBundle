<?php

namespace DigitalRespawn\BreadcrumbBundle;

use DigitalRespawn\BreadcrumbBundle\DependencyInjection\DigitalRespawnBreadcrumbExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Vincent MARIUS <vincent.marius@digitalrespawn.com>
 */
class DigitalRespawnBreadcrumbBundle extends Bundle
{
	public function getContainerExtension()
	{
		return new DigitalRespawnBreadcrumbExtension();
	}
}
