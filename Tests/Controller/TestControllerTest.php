<?php

namespace DigitalRespawn\BreadcrumbBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class TestControllerTest
 * @package DigitalRespawn\BreadcrumbBundle\Tests\Controller
 * @author  Vincent MARIUS <vincent.marius@digitalrespawn.com>
 */
class TestControllerTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client;

    /**
     * TestControllerTest constructor.
     */
    public function __construct()
    {
        $this->client = self::createClient();
    }

    /**
     * /home route test
     */
    public function testGetHomeActionIsSuccessful()
    {
        $this->client->request('GET', '/home');

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $breadcrumb = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals($breadcrumb[0]['uri'], '/home');
        $this->assertEquals($breadcrumb[0]['label'], 'Home');
    }

    /**
     * /get/{id} route test
     */
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

    /**
     * /get/{id}/{param} route test
     */
    public function testGetNodeMultiParamsActionIsSuccessful()
    {
        $this->client->request('GET', '/get/1/param');

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $breadcrumb = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals($breadcrumb[0]['uri'], '/home');
        $this->assertEquals($breadcrumb[0]['label'], 'Home');

        $this->assertEquals($breadcrumb[1]['uri'], '/get/1/param');
        $this->assertEquals($breadcrumb[1]['label'], 'Node 1');
    }

    /**
     * /get_parent/{id} route test
     */
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