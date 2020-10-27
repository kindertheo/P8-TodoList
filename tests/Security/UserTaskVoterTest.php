<?php


use App\Entity\Task;
use App\Entity\User;
use App\Security\UserTaskVoter;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UserTaskVoterTest extends WebTestCase
{
    private $task;

    private $voter;

    public function setUp(): void
    {
        $this->task = new Task();
        $this->voter = new UserTaskVoter();
    }

    private function createUser(string $username): User
    {
        $user = new User();
        $user->setUsername($username);
        $user->setUserRole('ROLE_USER');

        return $user;
    }

    private function createUserRoles(string $username, string $roles): User
    {
        $user = new User();
        $user->setUsername($username);
        $user->setUserRole($roles);

        return $user;
    }

    private function createTask($user = null): Task
    {
        $task = new Task();
        $task->setAuthor($user);

        return $task;
    }

    /**
     * @dataProvider provideCases
     * @return Generator|null
     */
    public function provideCases(): ?\Generator
    {

        yield 'Admin peut supprimer n\'importe quelle tache' => [
            $this->createUserRoles("user2", 'ROLE_ADMIN'),
            $task = $this->createTask($this->createUser("user3")),
            $attribute = 'edit',
            UserTaskVoter::ACCESS_GRANTED,
        ];

        yield 'l\'admin peut supprimer une tache annonyme' => [
            $this->createUserRoles("user2", 'ROLE_ADMIN'),
            $task = $this->createTask(Null),
            $attribute = 'edit',
            UserTaskVoter::ACCESS_GRANTED,
        ];

        yield 'User peut supprimer sa tache' => [
            $user = $this->createUser("user2"),
            $task = $this->createTask($user),
            $attribute = 'edit',
            UserTaskVoter::ACCESS_GRANTED,
        ];

        yield 'User ne peut pas supprimer une task d\' une autre utilisateur ' => [
            $this->createUser("user2"),
            $task = $this->createTask($this->createUser("user4")),
            $attribute = 'edit',
            UserTaskVoter::ACCESS_DENIED,
        ];

        yield 'Utilisateur annonyme ne peut pas supprimer' => [
            $user = null,
            $task = $this->createTask(null),
            $attribute = 'edit',
            UserTaskVoter::ACCESS_DENIED,
        ];
    }

    /**
     * @dataProvider provideCases
     * @param $user
     * @param Task $task
     * @param string $attribute
     * @param int $expectedVote
     */

    public function testVote(
        $user,
        Task $task,
        string $attribute,
        int $expectedVote): void
    {

        $token = new AnonymousToken('secret', 'anonymous');
        if ($user) {
            $token = new UsernamePasswordToken(
                $user, 'password', 'memory'
            );
        }

        $this->assertSame($expectedVote, $this->voter->vote($token, $task, [$attribute]));
    }
}