<?php

namespace Balibali\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Balibali\BlogBundle\Document\Post;
use Balibali\BlogBundle\Form\PostForm;
use Symfony\Component\Form\Form;

class PostController extends Controller
{
    public function indexAction()
    {
        return $this->render('index');
    }

    public function listAction()
    {
        $posts = $this->get('doctrine.odm.mongodb.document_manager')
            ->createQueryBuilder('BalibaliBlogBundle:Post')
            ->sort('publishedAt', 'desc')
            ->getQuery()->execute();

        return $this->render('list', array('posts' => $posts));
    }

    public function showAction($year, $month, $slug)
    {
        $post = $this->getPostBySlug($slug, $year, $month);

        $this->transformPostBody($post);

        return $this->render('show', array('post' => $post));
    }

    public function feedAction()
    {
        $posts = $this->get('doctrine.odm.mongodb.document_manager')
            ->createQueryBuilder('BalibaliBlogBundle:Post')
            ->sort('publishedAt', 'desc')
            ->limit(10)
            ->getQuery()->execute();

        foreach ($posts as $post) {
            $this->transformPostBody($post);
        }

        $response = $this->render('BalibaliBlogBundle:Post:feed.xml.twig', array('posts' => $posts));
        $response->headers->set('Content-Type', 'application/rss+xml');

        return $response;
    }

    public function manageAction()
    {
        $posts = $this->get('doctrine.odm.mongodb.document_manager')
            ->createQueryBuilder('BalibaliBlogBundle:Post')
            ->sort('publishedAt', 'desc')
            ->getQuery()->execute();
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
        $form->bind($this->get('request')->request->get($form->getName()));
        if (!$form->isValid()) {
            throw new AccessDeniedException('The CSRF token is invalid');
        }

        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $dm->remove($post);
        $dm->flush();

        return $this->redirect($this->generateUrl('balibali_blog_backend_index', array(), true));
    }

    public function previewAction()
    {
        $post = new Post();
        $post->setBody($this->get('request')->request->get('body'));
        $post->setFormat($this->get('request')->request->get('format'));

        $this->transformPostBody($post);

        $response = $this->get('response');
        $response->setContent($post->getBody());

        return $response;
    }

    public function render($view, array $parameters = array(), Response $response = null)
    {
        if (strpos($view, ':') === false) {
            $view = 'BalibaliBlogBundle:Post:'.$view.'.html.twig';
        }

        $parameters['config'] = array(
            'layout'      => $this->getParameter('layout', 'BalibaliBlogBundle::layout.html.twig'),
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
        $post = $this->get('doctrine.odm.mongodb.document_manager')
            ->find('BalibaliBlogBundle:Post', $id);

        if (!$post) {
            throw new NotFoundHttpException('The post does not exist.');
        }

        return $post;
    }

    protected function getPostBySlug($slug, $year, $month)
    {
        $start = new \MongoDate(strtotime(sprintf('%04d-%02d-01', $year, $month)));
        $end   = new \MongoDate(strtotime(sprintf('%04d-%02d-01 + 1month', $year, $month)));

        $post = $this->get('doctrine.odm.mongodb.document_manager')
            ->getRepository('BalibaliBlogBundle:Post')
            ->findOneBy(array('slug' => $slug, 'publishedAt' => array('$gte' => $start, '$lt' => $end)));

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

        $options = array(
            'data'       => $post,
            'use_format' => $this->has('markdownParser'),
        );

        return PostForm::create($this->get('form.context'), 'post', $options);
    }

    protected function processPostForm($form)
    {
        $form->bind($this->get('request')->request->get($form->getName()));

        if ($form->isValid()) {
            $post = $form->getData();

            if ($this->isDuplicatedSlug($post)) {
                $post->setSlug($post->getSlug().'-'.time());
            }

            $dm = $this->get('doctrine.odm.mongodb.document_manager');
            $dm->persist($post);
            $dm->flush();

            return true;
        }

        return false;
    }

    protected function isDuplicatedSlug($post)
    {
        $dup = $this->get('doctrine.odm.mongodb.document_manager')
            ->getRepository('BalibaliBlogBundle:Post')
            ->findOneBy(array('slug' => $post->getSlug()));

        return $dup && $dup->getId() !== $post->getId();
    }

    protected function getDeleteForm()
    {
        return Form::create($this->get('form.context'), 'delete');
    }

    protected function transformPostBody(Post $post)
    {
        if ($post->getFormat() === 'markdown' && $this->has('markdownParser')) {
            $body = $post->getBody();

            // Markdown
            $body = $this->get('markdownParser')->transform($body);

            // SyntaxHighlighter
            $body = preg_replace('!<pre><code>[/*#;\s]*(brush:.*?)\n(.*?)</code></pre>!s', '<pre class="$1">$2</pre>', $body);

            $post->setBody($body);
        }
    }
}
