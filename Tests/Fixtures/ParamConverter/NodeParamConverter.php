<?php

namespace DigitalRespawn\BreadcrumbBundle\Tests\Fixtures\ParamConverter;

use DigitalRespawn\BreadcrumbBundle\Tests\Fixtures\Model\Node;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Vincent MARIUS <vincent.marius@digitalrespawn.com>
 */
class NodeParamConverter implements ParamConverterInterface
{

	/**
	 * {@inheritdoc}
	 *
	 * @param ParamConverter $configuration Should be an instance of ParamConverter
	 *
	 * @return bool True if the object is supported, else false
	 */
	public function supports(ParamConverter $configuration)
	{
		return ('DigitalRespawn\BreadcrumbBundle\Tests\Fixtures\Model\Node' === $configuration->getClass());
	}

	/**
	 * {@inheritdoc}
	 *
	 * Applies converting
	 *
	 * @throws \InvalidArgumentException When route attributes are missing
	 * @throws NotFoundHttpException     When object not found
	 */
	public function apply(Request $request, ParamConverter $configuration)
	{
		$id = $request->attributes->get('id');

		if (null === $id) {
			throw new \InvalidArgumentException('Route attribute is missing');
		}

		$node = new Node();
		$node->setId($id);

		$request->attributes->set($configuration->getName(), $node);
	}
}