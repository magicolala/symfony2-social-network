<?php

namespace TechCorp\FrontBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use TechCorp\FrontBundle\Entity\User;
use TechCorp\FrontBundle\Entity\Status;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    const MAX_NB_USERS = 10;

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        for ($i=0; $i<self::MAX_NB_USERS; $i++) {
            //On crée un nouvel utilisateur
            $user = new User();
            $user->setUsername($faker->username);
            $user->setEmail($faker->email);
            $user->setPassword($faker->password());

            $manager->persist($user);

            $this->addReference('user'.$i, $user);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}