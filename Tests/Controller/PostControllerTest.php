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

        $form = $crawler->selectButton('Post')->form();
        $crawler = $client->submit($form, array());

        $this->assertTrue($crawler->filterXPath('//*[@id="post_title"]/preceding-sibling::ul/li[.="This value should not be blank"]')->count() > 0);
        $this->assertTrue($crawler->filterXPath('//*[@id="post_body"]/preceding-sibling::ul/li[.="This value should not be blank"]')->count() > 0);

        $form = $crawler->selectButton('Post')->form();
        $crawler = $client->submit($form, array('post[title]' => '', 'post[body]' => 'not empty'));

        $this->assertTrue($crawler->filterXPath('//*[@id="post_title"]/preceding-sibling::ul/li[.="This value should not be blank"]')->count() > 0);
        $this->assertFalse($crawler->filterXPath('//*[@id="post_body"]/preceding-sibling::ul/li[.="This value should not be blank"]')->count() > 0);

        $form = $crawler->selectButton('Post')->form();
        $crawler = $client->submit($form, array('post[title]' => 'not empty', 'post[body]' => ''));

        $this->assertFalse($crawler->filterXPath('//*[@id="post_title"]/preceding-sibling::ul/li[.="This value should not be blank"]')->count() > 0);
        $this->assertTrue($crawler->filterXPath('//*[@id="post_body"]/preceding-sibling::ul/li[.="This value should not be blank"]')->count() > 0);
    }
}
