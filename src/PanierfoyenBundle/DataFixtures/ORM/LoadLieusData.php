<?php

namespace PanierfoyenBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use PanierfoyenBundle\Entity\Lieus;

class LoadLieusData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function load(ObjectManager $manager) {

        $import = $this->container->get('panierfoyen.importcsv');
        $fileContent = $import->CSV_to_array('lieux.csv');

        foreach ($fileContent as $numRow => $row) {
            if ($numRow != 1) {
                $entity = new Lieus();

                $entity->setLibelle($row[0]);
                $entity->setCodepostal($row[1]);
                $entity->setVille($row[2]);
                $entity->setAdr1($row[3]);
                $entity->setAdr2($row[4]);
                $entity->setAdr3($row[5]);

                $manager->persist($entity);
                $manager->flush();
            }
        }
    }

    public function getOrder() {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 11;
    }

}
