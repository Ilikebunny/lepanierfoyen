<?php

namespace PanierfoyenBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use PanierfoyenBundle\Entity\AbstractAdresses;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DoctrineListener {

    private $container;

    public function __construct(ContainerInterface $container = null) {
        $this->container = $container;
    }

    /** @ORMPrePersist */
    public function prePersist(LifecycleEventArgs $event) {

//        die('Something is being inserted!');

        $entity = $event->getEntity();

        $geocoder = $this->container->get('bazinga_geocoder.geocoder');

        // only act on some entity
        if ($entity instanceof AbstractAdresses) {
//            die('Something is being inserted!');
            $geoResult = $geocoder->geocode($entity->getAdressComplete());
            if ($geoResult->count() > 0) {
                $address = $geoResult->first();
                $entity->setLatitude($address->getLatitude());
                $entity->setLongitude($address->getLongitude());
            } else {
                $geoResult = $geocoder->geocode($entity->getCodepostal() . ' ' . $entity->getVille());
                if ($geoResult->count() > 0) {
                    $address = $geoResult->first();
                    $entity->setLatitude($address->getLatitude());
                    $entity->setLongitude($address->getLongitude());
                }
            }
        }
    }

}
