<?php

namespace PanierfoyenBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use PanierfoyenBundle\Entity\Producteurs;
use PanierfoyenBundle\Entity\Categories;

class LoadProducteurData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function load(ObjectManager $manager) {

        $import = $this->container->get('panierfoyen.importcsv');
        $fileContent = $import->CSV_to_array('producteurs.csv');

        foreach ($fileContent as $numRow => $row) {
            if ($numRow != 1) {
                $entity = new Producteurs();
                $entity->setNom($row[0]);
                $entity->setCodepostal($row[1]);
                $entity->setVille($row[2]);
                $entity->setAdr1($row[3]);
                $entity->setAdr2($row[4]);
                $entity->setAdr3($row[5]);
                $entity->setTel($row[6]);
                $entity->setMobile($row[7]);
                $entity->setEmail($row[8]);
                $entity->setSiteInternet($row[9]);
                $entity->setChequeOrdre($row[10]);

                //link to coordianteur entity
                if ($row[11] != "") {
                    $entity->setCoordinateur($this->getReference($row[11]));
                }

                //links to categories entities
                if ($row[12] != "") {
                    $categoriesList = explode("/", $row[12]);
                    foreach ($categoriesList as $categorieLibelle) {
                        $entity->addCategory($this->getReference($categorieLibelle));
                    }
                }

                //import presentation
                $txtName = $row[13];
                if ($txtName != "") {
                    $txtContent = $import->TXT_to_String($txtName);
                    $entity->setPresentation($txtContent);
                }

                $entity->setContratFile($row[14]);
                
                $manager->persist($entity);
                $manager->flush();

                $this->addReference($entity->getNom(), $entity);
            }
        }
    }

    public function getOrder() {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 4;
    }

}
