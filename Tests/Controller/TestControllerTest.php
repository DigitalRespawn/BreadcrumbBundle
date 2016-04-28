<?php

namespace DigitalRespawn\BreadcrumbBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @author Vincent MARIUS <vincent.marius@digitalrespawn.com>
 */
class TestControllerTest extends WebTestCase
{
	protected $client;

	public function __construct()
	{
		$this->client = self::createClient();
	}

	public function testGetHomeActionIsSuccessful()
	{
		$this->client->request('GET', '/home');

		$this->assertTrue($this->client->getResponse()->isSuccessful());

		$breadcrumb = json_decode($this->client->getResponse()->getContent(), true);

		$this->assertEquals($breadcrumb[0]['uri'], '/home');
		$this->assertEquals($breadcrumb[0]['label'], 'Home');
	}

	public function testGetNodeActionIsSuccessful()
	{
		$this->client->request('GET', '/get/1');

		$this->assertTrue($this->client->getResponse()->isSuccessful());

		$breadcrumb = json_decode($this->client->getResponse()->getContent(), true);

		$this->assertEquals($breadcrumb[0]['uri'], '/home');
		$this->assertEquals($breadcrumb[0]['label'], 'Home');

		$this->assertEquals($breadcrumb[1]['uri'], '/get/1');
		$this->assertEquals($breadcrumb[1]['label'], 'Node 1');
	}

	public function testGetParentNodeActionIsSuccessful()
	{
		$this->client->request('GET', '/get_parent/2');

		$this->assertTrue($this->client->getResponse()->isSuccessful());

		$breadcrumb = json_decode($this->client->getResponse()->getContent(), true);

		$this->assertEquals($breadcrumb[0]['uri'], '/home');
		$this->assertEquals($breadcrumb[0]['label'], 'Home');

		$this->assertEquals($breadcrumb[1]['uri'], '/get/1');
		$this->assertEquals($breadcrumb[1]['label'], 'Node 1');

		$this->assertEquals($breadcrumb[2]['uri'], '/get_parent/2');
		$this->assertEquals($breadcrumb[2]['label'], 'Node 2');
	}
}