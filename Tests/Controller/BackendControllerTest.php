<?php

namespace Bundle\Balibali\BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BackendControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/backend/');

        $this->assertTrue($crawler->filter('html:contains("backend index")')->count() > 0);
    }

    public function testNew()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/backend/new');

        $this->assertTrue($crawler->filter('html:contains("backend new")')->count() > 0);
    }

    public function testCreate()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/backend/create');

        $this->assertTrue($crawler->filter('html:contains("backend new")')->count() > 0);
    }

    public function testEdit()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/backend/edit/1');

        $this->assertTrue($crawler->filter('html:contains("backend edit")')->count() > 0);
    }

    public function testUpdate()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/backend/update/1');

        $this->assertTrue($crawler->filter('html:contains("backend edit")')->count() > 0);
    }

    public function testDelete()
    {
        $client = $this->createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/backend/delete/1');

        $this->assertTrue($crawler->filter('html:contains("backend index")')->count() > 0);
    }
}
