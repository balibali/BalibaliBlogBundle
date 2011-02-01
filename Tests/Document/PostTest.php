<?php

namespace Balibali\BlogBundle\Tests\Document;

use Balibali\BlogBundle\Document\Post;

class PostTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Balibali\BlogBundle\Document\Post::slugify()
     */
    public function testSlugify()
    {
        $post = new Post();

        $this->assertEquals('blog-post', $post->slugify(''));
        $this->assertEquals('default', $post->slugify('', 'default'));
        $this->assertEquals('hello-world', $post->slugify('Hello, world!'));
        $this->assertEquals('a-b', $post->slugify('  a  b  '));
        $this->assertEquals('a-b', $post->slugify('a- b'));
        $this->assertEquals('blog-post', $post->slugify('日本語'));
        $this->assertEquals('a-b', $post->slugify('日a本b語'));
    }
}
