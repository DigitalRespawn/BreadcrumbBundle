<?php

namespace DigitalRespawn\BreadcrumbBundle\Tests\Fixtures\Controller;

use DigitalRespawn\BreadcrumbBundle\Tests\Fixtures\Model\Node;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TestController
 * @package DigitalRespawn\BreadcrumbBundle\Tests\Fixtures\Controller
 * @author  Vincent MARIUS <vincent.marius@digitalrespawn.com>
 */
class TestController extends Controller
{
    /**
     * Home route defining breadcrumb's root
     *
     * @return Response
     */
    public function homeAction()
    {
        $response = new Response(json_encode($this->get('digitalrespawn.breadcrumb.breadcrumb')->getBreadcrumb()));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Example of route with ParamConverter
     *
     * @param Node $node : Example of object to be converted
     *
     * @return Node Response
     *
     * @ParamConverter("node", converter="node_param_converter")
     */
    public function getAction(Node $node)
    {
        $response = new Response(json_encode($this->get('digitalrespawn.breadcrumb.breadcrumb')->getBreadcrumb()));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}