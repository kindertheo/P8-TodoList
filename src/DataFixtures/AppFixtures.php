<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @codeCoverageIgnore
 * Class AppFixtures
 * @package App\DataFixtures
 */
class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        // $product = new Product();
        // $manager->persist($product);

        $userAdmin = new User();

        $userAdmin->setEmail("theo@theo.com");
        $userAdmin->setUsername("theo");
        $userAdmin->setPassword($this->encoder->encodePassword($userAdmin, "password"));
        $userAdmin->setUserRole("ROLE_ADMIN");

        $manager->persist($userAdmin);

        $userUser = new User();

        $userUser->setEmail("user@user.com");
        $userUser->setUsername("user");
        $userUser->setPassword($this->encoder->encodePassword($userUser, "password"));
        $userUser->setUserRole("ROLE_USER");

        $manager->persist($userUser);

        $users = [];
        array_push($users, $userAdmin, $userUser);
        //$users[] = $userAdmin $userUser;

        for($j = 0; $j < 5; $j++){
            $user = new User();

            $user->setUsername($faker->userName);
            $user->setPassword($this->encoder->encodePassword($user, "password"));
            $user->setEmail($faker->safeEmail);
            $user->setUserRole("ROLE_USER");

            $users[] = $user;

            $manager->persist($user);
        }

        for($i = 0; $i < 5; $i++){
            $task = new Task();

            $task->setAuthor( $users[random_int(0, count($users) - 1) ]);
            $task->setIsDone(false);
            $task->setCreatedAt( $faker->dateTime("-8 hours") );
            $task->setTitle($faker->word());
            $task->setContent($faker->paragraph(2));

            $manager->persist($task);
        }

        for($f = 0; $f < 3; $f++){
            $anonTask = new Task();

            $anonTask->setIsDone(false);
            $anonTask->setCreatedAt( $faker->dateTime("-8 hours") );
            $anonTask->setContent($faker->paragraph(2));
            $anonTask->setTitle($faker->word());
            $anonTask->setAuthor(null);

            $manager->persist($anonTask);
        }


        $manager->flush();
    }
}
