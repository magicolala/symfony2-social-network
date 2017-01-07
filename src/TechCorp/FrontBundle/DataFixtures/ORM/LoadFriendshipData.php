<?php

namespace TechCorp\FrontBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use TechCorp\FrontBundle\Entity\User;

class LoadFriendshipData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    const MAX_FRIENDS_NB = 5;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < LoadUserData::MAX_NB_USERS; $i++) {
            $currentUser = $this->getReference('user' . $i);
            $j = 0;
            $nbFriends = rand(0, self::MAX_FRIENDS_NB);

            while ($j < $nbFriends) {
                $currentFriend = $this->getReference('user' . rand(0, 9));
                if ($currentUser->canAddFriend($currentFriend)) {
                    $currentUser->addFriend($currentFriend);
                    ++$j;
                }
            }

            $manager->flush();
        }
    }

    public function getOrder()
    {
        return 2;
    }
}
