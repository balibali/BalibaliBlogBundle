<?php

namespace Bundle\BalibaliBlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BalibaliBlogBundle:Default:index');
    }
}
