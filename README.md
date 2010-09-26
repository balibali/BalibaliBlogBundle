README
======

Installation
------------

1.  Add this bundle to your src/Bundle/Balibali dir:

        $ mkdir src/Bundle/Balibali
        $ git submodule add git://github.com/balibali/BalibaliBlogBundle.git src/Bundle/Balibali/BlogBundle

2.  Add this bundle and bundle dir to your application kernel:

        // app/AppKernel.php
        public function registerBundles()
        {
            $bundles = array(
                ...
                new Bundle\Balibali\BlogBundle\BalibaliBlogBundle(),
            );
        }

        public function registerBundleDirs()
        {
            return array(
                ...
                'Bundle\\Balibali' => __DIR__.'/../src/Bundle/Balibali',
            );
        }

3.  Enable DoctrineMongoDBBundle and TwigBundle:

        // app/AppKernel.php
        public function registerBundles()
        {
            $bundles = array(
                ...
                new Symfony\Bundle\DoctrineMongoDBBundle\DoctrineMongoDBBundle(),
                new Symfony\Bundle\TwigBundle\TwigBundle(),
                ...
            );
        }

        // app/config/config.yml
        twig.config:
            auto_reload: true

        doctrine_odm.mongodb:
            server: "mongodb://localhost:27017"
            default_database: symfony_%kernel.environment%

4.  Add routing rules to your application:

        // app/config/routing.yml
        blog:
            resource: Balibali/BlogBundle/Resources/config/routing.yml
            prefix:   /blog

5.  Install assets by assets:install command:

        $ app/console assets:install web
        Installing assets for Symfony\Bundle\FrameworkBundle
        Installing assets for Bundle\Balibali\BlogBundle
        Installing assets for Symfony\Bundle\WebProfilerBundle


Configuration
-------------

Set your blog title and description:

    // app/config/config.yml
    parameters:
        balibali.blog.title: "Balibali Blog"
        balibali.blog.description: "Blog written by Rimpei Ogawa."

Here is an example of using global layout:

    // app/config/config.yml
    parameters:
        balibali.blog.layout: "::layout"


Markdown Format
---------------

Use knplabs' MarkdownBundle http://github.com/knplabs/MarkdownBundle.

1.  Add this bundle to your src/Bundle dir:

        $ git submodule add git://github.com/knplabs/MarkdownBundle.git src/Bundle/MarkdownBundle

2.  Add this bundle and bundle dir to your application kernel:

        // app/AppKernel.php
        public function registerBundles()
        {
            $bundles = array(
                ...
                new Bundle\MarkdownBundle\MarkdownBundle(),
                ...
            );
        }

3.  Enable the markdown parser service:

        // app/config/config.yml
        markdown.parser: ~
