<?php

namespace PanierfoyenBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use PanierfoyenBundle\Entity\Conditionnements;

class LoadConditionnementsData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function load(ObjectManager $manager) {

        $import = $this->container->get('panierfoyen.importcsv');
        $fileContent = $import->CSV_to_array('conditionnements.csv');

        foreach ($fileContent as $numRow => $row) {
            if ($numRow != 1) {
                $entity = new Conditionnements();
                
                $entity->setProduit($this->getReference($row[0]));
                $entity->setLibelle($row[1]);
                $entity->setPrixUnitaire($row[2]);

                $manager->persist($entity);
                $manager->flush();
            }
        }
    }

    public function getOrder() {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 6;
    }

}
