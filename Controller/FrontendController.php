<?php

namespace Bundle\Balibali\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FrontendController extends Controller
{
    public function indexAction()
    {
        return $this->render('Balibali/BlogBundle:Frontend:index:twig');
    }

    public function showAction()
    {
        return $this->render('Balibali/BlogBundle:Frontend:show:twig');
    }
}
