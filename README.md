README
======

Installation
------------

1.  Add this bundle to your src/Balibali dir:

        $ mkdir src/Balibali
        $ git submodule add git://github.com/balibali/BalibaliBlogBundle.git src/Balibali/BlogBundle

2. Add Balibali namespace to your autoloader:

        // app/autoload.php
        $loader->registerNamespaces(array(
            ...
            'Balibali' => __DIR__.'/../src',
        ));

3.  Add this bundle to your application kernel:

        // app/AppKernel.php
        public function registerBundles()
        {
            $bundles = array(
                ...
                new Balibali\BlogBundle\BalibaliBlogBundle(),
            );
        }

4.  Enable DoctrineMongoDBBundle:

        // app/AppKernel.php
        public function registerBundles()
        {
            $bundles = array(
                ...
                new Symfony\Bundle\DoctrineMongoDBBundle\DoctrineMongoDBBundle(),
                ...
            );
        }

        // app/config/config.yml
        doctrine_odm.mongodb:
            server: "mongodb://localhost:27017"
            default_database: symfony_%kernel.environment%
            auto_generate_hydrator_classes: true
            mappings:
                BalibaliBlogBundle: ~

5.  Add routing rules to your application:

        // app/config/routing.yml
        blog:
            resource: @BalibaliBlogBundle/Resources/config/routing.yml
            prefix:   /blog

6.  Install assets by assets:install command:

        $ app/console assets:install web
        ...
        Installing assets for Balibali\BlogBundle


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
        balibali.blog.layout: "::layout.html.twig"


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
