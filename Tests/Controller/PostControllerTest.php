<?php

namespace Balibali\BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');

        $this->assertTrue($crawler->filter('html:contains("Recent Posts")')->count() > 0);
    }

    public function testManage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/backend/');

        $this->assertTrue($crawler->filter('html:contains("Manage Posts")')->count() > 0);
    }

    public function testNew()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/backend/posts/new');

        $this->assertTrue($crawler->filter('html:contains("New Post")')->count() > 0);
    }
}
