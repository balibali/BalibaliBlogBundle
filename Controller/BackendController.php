<?php

namespace Bundle\Balibali\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BackendController extends Controller
{
    public function indexAction()
    {
        return $this->render('Balibali/BlogBundle:Backend:index:twig');
    }

    public function newAction()
    {
        return $this->render('Balibali/BlogBundle:Backend:new:twig');
    }

    public function createAction()
    {
        return $this->render('Balibali/BlogBundle:Backend:new:twig');
    }

    public function editAction()
    {
        return $this->render('Balibali/BlogBundle:Backend:edit:twig');
    }

    public function updateAction()
    {
        return $this->render('Balibali/BlogBundle:Backend:edit:twig');
    }

    public function deleteAction()
    {
        return $this->redirect($this->generateUrl('balibali_blog_backend_index', array(), true));
    }
}
