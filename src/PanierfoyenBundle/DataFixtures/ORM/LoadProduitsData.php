<?php

namespace PanierfoyenBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use PanierfoyenBundle\Entity\Produits;
use PanierfoyenBundle\Entity\Producteurs;
use PanierfoyenBundle\Entity\Categories;
use PanierfoyenBundle\Entity\Frequences;

class LoadProduitsData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function load(ObjectManager $manager) {

        $import = $this->container->get('panierfoyen.importcsv');
        $fileContent = $import->CSV_to_array('produits.csv');

        foreach ($fileContent as $numRow => $row) {
            if ($numRow != 1) {
                $entity = new Produits();
                $entity->setProducteur($this->getReference($row[0]));
                $entity->setCategory($this->getReference($row[1]));
                $entity->setFrequence($this->getReference($row[2]));
                $entity->setLibelle($row[3]);
                $entity->setDescriptif($row[4]);
                $entity->setImage($row[5]);

                $manager->persist($entity);
                $manager->flush();
            }
        }
    }

    public function getOrder() {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 5;
    }

}
