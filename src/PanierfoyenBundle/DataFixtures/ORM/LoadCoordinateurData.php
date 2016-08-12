<?php

namespace PanierfoyenBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use PanierfoyenBundle\Entity\Coordinateurs;

class LoadCoordinateurData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function load(ObjectManager $manager) {

        $import = $this->container->get('panierfoyen.importcsv');
        $fileContent = $import->CSV_to_array('coordinateurs.csv');

        foreach ($fileContent as $numRow => $row) {
            if ($numRow != 1) {
                $coordinateur = new Coordinateurs();
                $coordinateur->setNom($row[0]);
                $coordinateur->setCodepostal($row[1]);
                $coordinateur->setVille($row[2]);
                $coordinateur->setAdr1($row[3]);
                $coordinateur->setAdr2($row[4]);
                $coordinateur->setAdr3($row[5]);
                $coordinateur->setTel($row[6]);
                $coordinateur->setMobile($row[7]);
                $coordinateur->setEmail($row[8]);

                $manager->persist($coordinateur);
                $manager->flush();
            }
        }
    }

    public function getOrder() {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 3;
    }

}
