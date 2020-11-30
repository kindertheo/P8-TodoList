<?php


namespace Tests\App\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{

    /**
     * @covers \App\Controller\SecurityController::loginAction
     */
    public function testLoginActionSuccess(){

        $client = static::createClient();
        $client->request('GET', '/login');


        $client->submitForm("Se connecter", [
            '_username' => 'theo',
            '_password' => 'password'
        ]);

        $client->followRedirect();
        // test e.g. the profile page
        $this->assertSelectorTextContains("a.pull-right", 'Se déconnecter');

    }
    /**
     * @covers \App\Controller\SecurityController::loginAction
     */
    public function testLoginActionFailure(){
        $client = static::createClient();
        $client->request('GET', '/login');


        $client->submitForm("Se connecter", [
            '_username' => 'theo',
            '_password' => 'dzpozdkopzodkzdpozd'
        ]);

        $client->followRedirect();
        // test e.g. the profile page
        $this->assertSelectorNotExists("a.pull-right", 'Se déconnecter');
    }
    /**
     * @covers \App\Controller\SecurityController::logoutCheck
     */
    public function testLogoutAction(){

        $client = static::createClient();
        $client->request('GET', '/login');


        $client->submitForm("Se connecter", [
            '_username' => 'theo',
            '_password' => 'password'
        ]);

        $client->followRedirect();

        $client->clickLink("Se déconnecter");
        $client->followRedirect();

        $this->assertSelectorExists("a.btn-success", "Se connecter");
    }
}