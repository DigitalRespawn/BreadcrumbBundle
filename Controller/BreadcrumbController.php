<?php

namespace DigitalRespawn\BreadcrumbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @author Vincent MARIUS <vincent.marius@digitalrespawn.com>
 */
class BreadcrumbController extends Controller
{
    public function breadcrumbAction()
    {
        return $this->render($this->getParameter('digitalrespawn.breadcrumb')['template'],
			array('breadcrumb' => $this->get('digitalrespawn.breadcrumb.breadcrumb')->getBreadcrumb()));
    }
}
