<?php

namespace PanierfoyenBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use PanierfoyenBundle\Entity\Group;

class LoadGroupData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function load(ObjectManager $manager) {

        $groupManager = $this->container->get('fos_user.group_manager');

        $group = $groupManager->createGroup('Coordinateurs');
        $group->addRole('ROLE_COORDINATEUR');
        $groupManager->updateGroup($group);
        
        $group = $groupManager->createGroup('Administrateurs');
        $group->addRole('ROLE_ADMIN');
        $groupManager->updateGroup($group);
        
        $group = $groupManager->createGroup('Rédacteurs');
        $group->addRole('ROLE_PUBLISHER');
        $groupManager->updateGroup($group);
        
    }

    public function getOrder() {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 9;
    }

}
