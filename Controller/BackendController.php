<?php

namespace Bundle\Balibali\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ForbiddenHttpException;
use Symfony\Component\Form\Form;
use Bundle\Balibali\BlogBundle\Document\Post;
use Bundle\Balibali\BlogBundle\Form\PostForm;

class BackendController extends Controller
{
    public function indexAction()
    {
        $posts = $this['doctrine.odm.mongodb.document_manager']
            ->getRepository('BlogBundle:Post')->createQuery()
            ->sort('publishedAt', 'desc')
            ->execute();
        $form = $this->getDeleteForm();

        return $this->render('Balibali/BlogBundle:Backend:index:twig', array('posts' => $posts, 'form' => $form));
    }

    public function newAction()
    {
        $form = $this->getPostForm();

        return $this->render('Balibali/BlogBundle:Backend:new:twig', array('form' => $form));
    }

    public function createAction()
    {
        $form = $this->getPostForm();

        if ($this->processPostForm($form)) {
            return $this->redirect($this->generateUrl('balibali_blog_backend_index', array(), true));
        }

        return $this->render('Balibali/BlogBundle:Backend:new:twig', array('form' => $form));
    }

    public function editAction($id)
    {
        $form = $this->getPostForm($id);

        return $this->render('Balibali/BlogBundle:Backend:edit:twig', array('form' => $form));
    }

    public function updateAction($id)
    {
        $form = $this->getPostForm($id);

        if ($this->processPostForm($form)) {
            return $this->redirect($this->generateUrl('balibali_blog_backend_index', array(), true));
        }

        return $this->render('Balibali/BlogBundle:Backend:edit:twig', array('form' => $form));
    }

    public function deleteAction($id)
    {
        $post = $this->getPostById($id);

        // CSRF protection
        $form = $this->getDeleteForm();
        $form->bind($this['request']->request->get($form->getName()));
        if (!$form->isValid()) {
            throw new ForbiddenHttpException('CSRF token is not matched.');
        }

        $dm = $this['doctrine.odm.mongodb.document_manager'];
        $dm->remove($post);
        $dm->flush();

        return $this->redirect($this->generateUrl('balibali_blog_backend_index', array(), true));
    }

    protected function getPostById($id)
    {
        $post = $this['doctrine.odm.mongodb.document_manager']
            ->find('BlogBundle:Post', $id);

        if (!$post) {
            throw new NotFoundHttpException('The post does not exist.');
        }

        return $post;
    }

    protected function getPostForm($id = null)
    {
        if ($id === null) {
            $post = new Post();
        } else {
            $post = $this->getPostById($id);
        }

        return new PostForm('post', $post, $this['validator']);
    }

    protected function processPostForm($form)
    {
        $form->bind($this['request']->request->get($form->getName()));

        if ($form->isValid()) {
            $dm = $this['doctrine.odm.mongodb.document_manager'];
            $dm->persist($form->getData());
            $dm->flush();

            return true;
        }

        return false;
    }

    protected function getDeleteForm()
    {
        return new Form('delete', new \stdClass, $this['validator']);
    }
}
