<?php

namespace Bundle\Balibali\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ForbiddenHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;
use Bundle\Balibali\BlogBundle\Document\Post;
use Bundle\Balibali\BlogBundle\Form\PostForm;

class PostController extends Controller
{
    public function indexAction()
    {
        return $this->render('index');
    }

    public function listAction()
    {
        $posts = $this['doctrine.odm.mongodb.document_manager']
            ->getRepository('BlogBundle:Post')->createQuery()
            ->sort('publishedAt', 'desc')
            ->execute();

        return $this->render('list', array('posts' => $posts));
    }

    public function showAction($year, $month, $slug)
    {
        $post = $this->getPostBySlug($slug, $year, $month);

        if ($post->getFormat() === 'markdown' && isset($this->container['markdownParser'])) {
            $post->setBody($this->container['markdownParser']->transform($post->getBody()));
        }

        return $this->render('show', array('post' => $post));
    }

    public function feedAction()
    {
        $posts = $this['doctrine.odm.mongodb.document_manager']
            ->getRepository('BlogBundle:Post')->createQuery()
            ->sort('publishedAt', 'desc')
            ->execute();

        return $this->render('Balibali/BlogBundle:Post:feed', array('posts' => $posts));
    }

    public function manageAction()
    {
        $posts = $this['doctrine.odm.mongodb.document_manager']
            ->getRepository('BlogBundle:Post')->createQuery()
            ->sort('publishedAt', 'desc')
            ->execute();
        $form = $this->getDeleteForm();

        return $this->render('manage', array('posts' => $posts, 'form' => $form));
    }

    public function newAction()
    {
        $form = $this->getPostForm();

        return $this->render('new', array('form' => $form));
    }

    public function createAction()
    {
        $form = $this->getPostForm();

        if ($this->processPostForm($form)) {
            return $this->redirect($this->generateUrl('balibali_blog_backend_index', array(), true));
        }

        return $this->render('new', array('form' => $form));
    }

    public function editAction($id)
    {
        $form = $this->getPostForm($id);

        return $this->render('edit', array('form' => $form));
    }

    public function updateAction($id)
    {
        $form = $this->getPostForm($id);

        if ($this->processPostForm($form)) {
            return $this->redirect($this->generateUrl('balibali_blog_backend_index', array(), true));
        }

        return $this->render('edit', array('form' => $form));
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

    public function render($view, array $parameters = array(), Response $response = null)
    {
        if (strpos($view, ':') === false) {
            $view = 'Balibali/BlogBundle:Post:'.$view.':twig';
        }

        $parameters['config'] = array(
            'layout'      => $this->getParameter('layout', 'Balibali\\BlogBundle::layout'),
            'title'       => $this->getParameter('title', ''),
            'description' => $this->getParameter('description', ''),
        );

        return parent::render($view, $parameters, $response);
    }

    protected function getParameter($name, $default = null)
    {
        if (strpos($name, '.') === false) {
            $name = 'balibali.blog.'.$name;
        }

        if ($this->container->hasParameter($name)) {
            return $this->container->getParameter($name);
        } else {
            return $default;
        }
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

    protected function getPostBySlug($slug, $year, $month)
    {
        $start = new \MongoDate(strtotime(sprintf('%04d-%02d-01', $year, $month)));
        $end   = new \MongoDate(strtotime(sprintf('%04d-%02d-01 + 1month', $year, $month)));

        $post = $this['doctrine.odm.mongodb.document_manager']->findOne('BlogBundle:Post',
                array('slug' => $slug, 'publishedAt' => array('$gte' => $start, '$lt' => $end)));

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

        $options = array('useFormat' => isset($this->container['markdownParser']));

        return new PostForm('post', $post, $this['validator'], $options);
    }

    protected function processPostForm($form)
    {
        $form->bind($this['request']->request->get($form->getName()));

        if ($form->isValid()) {
            $post = $form->getData();

            if ($this->isDuplicatedSlug($post)) {
                $post->setSlug($post->getSlug().'-'.time());
            }

            $dm = $this['doctrine.odm.mongodb.document_manager'];
            $dm->persist($post);
            $dm->flush();

            return true;
        }

        return false;
    }

    protected function isDuplicatedSlug($post)
    {
        $dup = $this['doctrine.odm.mongodb.document_manager']
            ->findOne('BlogBundle:Post', array('slug' => $post->getSlug()));

        return $dup && $dup->getId() !== $post->getId();
    }

    protected function getDeleteForm()
    {
        return new Form('delete', new \stdClass, $this['validator']);
    }
}
