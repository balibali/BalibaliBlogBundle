<?php

namespace Bundle\Balibali\BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontendControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/');

        $this->assertTrue($crawler->filter('html:contains("frontend index")')->count() > 0);
    }

    public function testShow()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/show/1');

        $this->assertTrue($crawler->filter('html:contains("frontend show")')->count() > 0);
    }
}
