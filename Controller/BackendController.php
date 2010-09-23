<?php

namespace Bundle\Balibali\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Bundle\Balibali\BlogBundle\Document\Post;
use Bundle\Balibali\BlogBundle\Form\PostForm;

class BackendController extends Controller
{
    public function indexAction()
    {
        $posts = $this['doctrine.odm.mongodb.document_manager']
            ->getRepository('Bundle\Balibali\BlogBundle\Document\Post')
            ->createQuery()
            ->sort('publishedAt', 'desc')
            ->execute();

        return $this->render('Balibali/BlogBundle:Backend:index:twig', array('posts' => $posts));
    }

    public function newAction()
    {
        $post = new Post();
        $form = new PostForm('post', $post, $this['validator']);

        return $this->render('Balibali/BlogBundle:Backend:new:twig', array('form' => $form));
    }

    public function createAction()
    {
        $post = new Post();
        $form = new PostForm('post', $post, $this['validator']);

        $form->bind($this['request']->request->get($form->getName()));

        if ($form->isValid()) {
            $dm = $this['doctrine.odm.mongodb.document_manager'];
            $dm->persist($post);
            $dm->flush();

            return $this->redirect($this->generateUrl('balibali_blog_backend_index', array(), true));
        }

        return $this->render('Balibali/BlogBundle:Backend:new:twig', array('form' => $form));
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
