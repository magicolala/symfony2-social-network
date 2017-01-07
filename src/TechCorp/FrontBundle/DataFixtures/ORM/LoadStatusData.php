<?php

namespace TechCorp\FrontBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use TechCorp\FrontBundle\Entity\Status;

class LoadStatusData extends AbstractFixture implements OrderedFixtureInterface
{
    const MAX_NB_STATUS = 50;

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        for ($i=0; $i<self::MAX_NB_STATUS; ++$i) {
            $status = new Status();
            $status->setContent($faker->text(250));
            $status->setDeleted($faker->boolean);

            $user = $this->getReference('user'.rand(0,9));
            $status->setUser($user);

            $manager->persist($status);
            $this->addReference('status'.$i, $status);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}
