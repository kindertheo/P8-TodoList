<?php

namespace Tests\App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class Utils extends WebTestCase
{

    private $client;

    public function __construct($client, $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->client = $client;
    }

    public function loginAdmin(){
        $this->client->request("GET", "/login");
        $this->client->submitForm("Se connecter", [
            '_username' => 'theo',
            '_password' => 'password'
        ]);
        $this->client->followRedirect();
    }

}