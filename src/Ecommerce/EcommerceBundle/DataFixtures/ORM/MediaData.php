<?php
/**
 * Created by PhpStorm.
 * User: matthieu
 * Date: 23/07/2018
 * Time: 22:44
 */

namespace Ecommerce\EcommerceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Ecommerce\EcommerceBundle\Entity\Media;

use Doctrine\Common\Persistence\ObjectManager;

class MediaData extends AbstractFixture implements OrderedFixtureInterface
{

    private function recupererMedia($dossier, ObjectManager $manager) // Fonction qui liste un dossier de façon récursive
    {
        if (is_dir($dossier)) {
            if ($dossierOuvert = opendir($dossier)) {
                while (($fichier = readdir($dossierOuvert)) !== false) {
                    $path = $dossier.DIRECTORY_SEPARATOR.$fichier;
                    if ($fichier === '.'or $fichier === '..' or $fichier === 'index.php'or $fichier === '.DS_Store') {
                        continue;
                    } elseif (is_dir($path)) {
                        $this->recupererMedia($path, $manager);
                    } else {
                        $media = new Media();
                        $path_parts = pathinfo($fichier);
                        $media->setAlt($path_parts['filename'])
                            ->setPath(substr($dossier . DIRECTORY_SEPARATOR . $fichier, strlen('/web')));
                        $this->addReference('media_'. $path_parts['filename'], $media);
                        $manager->persist($media);
                    }
                }
            }
        } else {
            die('Erreur, le paramètre précisé dans la fonction n\'est pas un dossier!');
        }
    }

    public function load(ObjectManager $manager)
    {
        $this->recupererMedia('web/images', $manager);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}