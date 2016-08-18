<?php

namespace PanierfoyenBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use PanierfoyenBundle\Entity\Users;

class LoadUsersData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function load(ObjectManager $manager) {

        $userManager = $this->container->get('fos_user.user_manager');

        $user = $userManager->createUser();

        $user
                ->setUsername('larrue.jerome')
                ->setEmail('larrue.jerome@gmail.com')
//                ->setFirstLogin(\DateTime::createFromFormat('j-M-Y', '15-Feb-2009'))
                ->setEnabled(true);

        $user->setPlainPassword('somepass');

        // Equivalent à :
//        $encoder = $this->container
//                ->get('security.encoder_factory')
//                ->getEncoder($user)
//            ;
//        $user->setPassword($encoder->encodePassword('somepass', $user->getSalt()));

        $user->addRole('ROLE_ADMIN');
        $userManager->updateUser($user);
        
        
    }

    public function getOrder() {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 10;
    }

}
