<?php

namespace Tests\App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class DefaultControllerTest extends WebTestCase
{
    /**
     * @covers \App\Controller\DefaultController::indexAction
     */
    public function testIndexAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertStringContainsString("Bienvenue sur Todo List", $crawler->filter("h1")->text() );
        //$this->assertStringContainsString('Créer un utilisateur', $crawler->filter('.btn.btn-primary')->text());
    }
}
