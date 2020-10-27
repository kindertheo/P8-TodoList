<?php


namespace Tests\App\Controller;


use Faker;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private $faker;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->faker = Faker\Factory::create();
    }
    /**
     * @covers \App\Controller\UserController::listAction
     */
    public function testListAction(){
        $client = static::createClient();

        $this->loginAdmin($client);

        $client->request('GET', '/users');

        $this->assertSelectorTextContains("h1", "Liste des utilisateurs");
    }

    /**
     * @covers \App\Controller\UserController::createAction
     */
    public function testCreateActionSuccess(){

        $client = static::createClient();

        $this->loginAdmin($client);

        $client->request('GET', '/users/create');

        $client->submitForm("Ajouter", [
            "user[username]" => $this->faker->userName,
            "user[password][first]" => "password",
            "user[password][second]" => "password",
            "user[email]" => $this->faker->safeEmail
        ]);

        $client->followRedirect();
        $this->assertSelectorTextContains("div.alert", "Superbe !");
    }

    /**
     * @covers \App\Controller\UserController::createAction
     */
    public function testCreateActionFailure(){

        $client = static::createClient();

        $this->loginAdmin($client);

        $client->request('GET', '/users/create');

        $client->submitForm("Ajouter", [
            "user[username]" => $this->faker->userName,
            "user[password][first]" => $this->faker->password(6),
            "user[password][second]" => $this->faker->password(5),
            "user[email]" => $this->faker->safeEmail
        ]);

        $this->assertSelectorNotExists("div.alert", "Superbe !");
    }

    /**
     * @covers \App\Controller\UserController::editAction
     */
    public function testEditActionSuccess(){
        $client = static::createClient();

        $this->loginAdmin($client);

        $client->request('GET', '/users/1/edit');

        $client->submitForm("Modifier", [
            "user[password][first]" => "password",
            "user[password][second]" => "password",
            "user[email]" => $this->faker->safeEmail
        ]);

        $client->followRedirect();

        $this->assertSelectorTextContains("div.alert", "Superbe !");
    }
    /**
     * @covers \App\Controller\UserController::editAction
     */
    public function testEditActionFailure(){
        $client = static::createClient();

        $this->loginAdmin($client);

        $client->request('GET', '/users/1/edit');

        $client->submitForm("Modifier", [
            "user[password][first]" => "password",
            "user[password][second]" => "pass",
            "user[email]" => $this->faker->safeEmail
        ]);

        $this->assertSelectorNotExists("div.alert", "Superbe !");
    }

    private function loginAdmin($client){
        $client->request("GET", "/login");
        $client->submitForm("Se connecter", [
            '_username' => 'theo',
            '_password' => 'password'
        ]);
        $client->followRedirect();
    }

}