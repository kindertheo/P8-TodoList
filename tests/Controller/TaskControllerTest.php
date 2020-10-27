<?php


namespace Tests\App\Controller;


use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Link;

class TaskControllerTest extends WebTestCase
{

    /**
     * @covers \App\Controller\TaskController::listAction
     */
    public function testListAction(){
        $client = static::createClient();

        $this->loginAdmin($client);

        $client->request("GET", "/tasks");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    /**
     * @covers \App\Controller\TaskController::createAction
     */
    public function testCreateActionSuccess(){
        $client = static::createClient();

        $this->loginAdmin($client);

        $client->request("GET", "/tasks/create");

        $client->submitForm("Ajouter", [
           "task[title]" => "Task",
            "task[content]" => "Content"
        ]);

        $client->followRedirect();

        $this->assertSelectorTextContains("div.alert", "Superbe !");
    }
    /**
     * @covers \App\Controller\TaskController::createAction
     */
    public function testCreateActionFailure(){
        $client = static::createClient();

        $this->loginAdmin($client);

        $client->request("GET", "/tasks/create");

        $client->submitForm("Ajouter", [
            "task[title]" => "Task",
            "task[content]" => ""
        ]);

        $uri = $client->getRequest()->getRequestUri();
        $this->assertEquals("/tasks/create", $uri);
        $this->assertSelectorNotExists("div.alert", "Superbe !");
    }
    /**
     * @covers \App\Controller\TaskController::editAction
     */
    public function testEditActionSuccess(){
        $client = static::createClient();

        $this->loginAdmin($client);

        $crawler = $client->request("GET", "/tasks");

        $task = $crawler->filter("div.col-sm-4:nth-child(1) > div:nth-child(1) > div:nth-child(1) > h4:nth-child(2) > a:nth-child(1)")->link();

        $client->click($task);

        $client->submitForm("Modifier", [
            "task[title]" => "Modifié",
            "task[content]" => "Modifié"
        ]);

        $client->followRedirect();

        $uri = $client->getRequest()->getRequestUri();
        $this->assertEquals("/tasks", $uri);
        $this->assertSelectorTextContains("div.alert", "Superbe !");
    }
    /**
     * @covers \App\Controller\TaskController::editAction
     */
    public function testEditActionFailure(){
        $client = static::createClient();

        $this->loginAdmin($client);

        $crawler = $client->request("GET", "/tasks");

        $task = $crawler->filter("div.col-sm-4:nth-child(1) > div:nth-child(1) > div:nth-child(1) > h4:nth-child(2) > a:nth-child(1)")->link();

        $this->assertNotEquals(null, $task);

        $client->click($task);

        $client->submitForm("Modifier", [
            "task[title]" => "Modifié",
            "task[content]" => ""
        ]);

        $this->assertSelectorNotExists("div.alert", "Superbe !");
    }
    /**
     * @covers \App\Controller\TaskController::toggleTaskAction
     */
    public function testToggleTaskAction(){
        $client = static::createClient();

        $this->loginAdmin($client);

        $crawler = $client->request("GET", "/tasks");

        $form = $crawler
            ->selectButton("Marquer comme faite")
            ->eq(0)
            ->form();

        /*Get ID of the task*/
        preg_match('/[0-9]{1,3}/', $form->getUri(), $taskId);

        $client->submit($form);

        $client->followRedirect();

        $entityManager = $client->getContainer()
            ->get('doctrine')
            ->getManager();

        $task = $entityManager->getRepository(Task::class)
            ->findOneBy(['id' => $taskId[0]]);

        $this->assertNotEquals(null, $task);
        $this->assertEquals(true, $task->isDone());
        $this->assertSelectorTextContains("div.alert", "Superbe !");
    }
    /**
     * @covers \App\Controller\TaskController::deleteTaskAction
     */
    public function testDeleteTaskAction(){
        $client = static::createClient();

        $this->loginAdmin($client);

        $crawler = $client->request("GET", "/tasks");

        $form = $crawler
            ->selectButton("Supprimer")
            ->eq(2)
            ->form();

        preg_match('/[0-9]{1,3}/', $form->getUri(), $taskId);

        $client->submit($form);
        $client->followRedirect();

        $entityManager = $client->getContainer()
            ->get('doctrine')
            ->getManager();

        $task = $entityManager->getRepository(Task::class)
            ->findOneBy(['id' => $taskId[0]]);

        $this->assertEquals(null, $task);
        $this->assertSelectorTextContains("div.alert", "Superbe !");
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