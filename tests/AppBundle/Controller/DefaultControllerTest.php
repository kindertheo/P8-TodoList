<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
        $crawler = $client->followRedirect();


        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertContains('Créer un utilisateur', $crawler->filter('.btn.btn-primary')->text());
    }
}
