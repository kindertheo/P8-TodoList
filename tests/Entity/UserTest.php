<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use phpDocumentor\Reflection\Types\Void_;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @covers \App\Entity\User
 * Class UserTest
 * @package App\Tests\Entity
 */
class UserTest extends WebTestCase
{
    private $user;

    private $task;

    public function setUp(): void
    {
        $this->user = new User();
        $this->task = new Task();
    }

    public function testId(): void
    {
        $this->assertSame(null, $this->user->getId());
    }

    public function testUsername(): void
    {
        $this->user->setUsername('Bob');
        $this->assertSame('Bob', $this->user->getUsername());
    }

    public function testPassword(): void
    {
        $this->user->setPassword('azertyui');
        $this->assertSame('azertyui', $this->user->getPassword());
    }

    public function testEmail(): void
    {
        $this->user->setEmail('root@root.fr');
        $this->assertSame('root@root.fr', $this->user->getEmail());
    }

    public function testRoles(): void
    {

        $this->assertEquals(true, in_array("ROLE_USER" , $this->user->getRoles()));
    }

    public function testTasks(): void
    {
        $tasks = $this->user->getTasks($this->task->getAuthor());
        $this->assertSame($this->user->getTasks(), $tasks);

        $this->user->addtask($this->task);
        $this->assertCount(1, $this->user->getTasks());

        $this->user->removeTask($this->task);
        $this->assertCount(0, $this->user->getTasks());
    }


    public function testSalt(): void
    {
        $this->assertEquals(null, $this->user->getSalt());
    }

    public function testEraseCredential(): void
    {
        $this->assertNull($this->user->eraseCredentials());
    }

    public function testSetUserRole(): void
    {
        $user = new User();
        $this->assertEquals("ROLE_USER", $user->setUserRole("ROLE_USER")->getUserRole());
    }

    public function testGetUserRole(): void
    {
        $user = new User();
        $user->setUserRole("ROLE_USER");
        $this->assertEquals("ROLE_USER", $user->getUserRole());
    }

}