<?php

namespace DigitalRespawn\BreadcrumbBundle\Tests\Breadcrumb;

use DigitalRespawn\BreadcrumbBundle\Tests\Fixtures\Model\Node;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Vincent MARIUS <vincent.marius@digitalrespawn.com>
 */
class BreadcrumbTest extends KernelTestCase
{
	private $container;
	private $breadcrumb;

	public function setUp()
	{
		self::bootKernel();

		$this->container = self::$kernel->getContainer();
		$this->breadcrumb = $this->container->get('digitalrespawn.breadcrumb.breadcrumb');
	}

	public function testBindValues()
	{
		$node = new Node();
		$node->setId(2);

		$request = $this->getMock(Request::class);
		$request->expects($this->once())
			->method('get')
			->with('node')
			->will($this->returnValue($node));

		$binding = array(
			'id' => 'node.parent.id'
		);

		$params = $this->breadcrumb->bindValues($request, $binding);
		$this->assertEquals($params['id'], 1);
	}
}