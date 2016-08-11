<?php

namespace PanierfoyenBundle\ImportCSV;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ImportCSV {

    private $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    /**
     * Get csv directory
     *
     */
    private function getDirectoryCSV() {
        $root = $this->container->getParameter('kernel.root_dir');
        $root .= "/csv/";
        return $root;
    }

    /**
     * Test import
     *
     */
    public function CSV_to_array($filename) {
        $row = 1;
        $csvDirectory = $this->getDirectoryCSV();
        $csvContent = array();
        if (($handle = fopen($csvDirectory . $filename, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $csvContent[$row] = $data;
                $row++;
            }
            fclose($handle);
        }
        return $csvContent;
    }

}
