<?php

namespace DigitalRespawn\BreadcrumbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class BreadcrumbController
 * @package DigitalRespawn\BreadcrumbBundle\Controller
 * @author  Vincent MARIUS <vincent.marius@digitalrespawn.com>
 */
class BreadcrumbController extends Controller
{
    /**
     * Render the breadcrumb
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function breadcrumbAction()
    {
        return $this->render(
            $this->getParameter('digitalrespawn.breadcrumb')['template'],
            array('breadcrumb' => $this->get('digitalrespawn.breadcrumb.breadcrumb')->getBreadcrumb())
        );
    }
}
