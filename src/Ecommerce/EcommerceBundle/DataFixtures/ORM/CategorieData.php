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
use Ecommerce\EcommerceBundle\Entity\Categories;

use Doctrine\Common\Persistence\ObjectManager;

class CategorieData extends AbstractFixture implements OrderedFixtureInterface
{

    private function getReferences($dossier, ObjectManager $manager)
    {
        if (is_dir($dossier)) {
            if ($dossierOuvert = opendir($dossier)) {
                while (($fichier = readdir($dossierOuvert)) !== false) {
                    $path = $dossier . DIRECTORY_SEPARATOR . $fichier;
                    if ($fichier == ".." || $fichier == "." || $fichier == "index.php" || $fichier == "edit.php" || $fichier == '.DS_Store') {
                        continue;
                    } elseif (!is_dir($path)) {
                        $path_parts = pathinfo($fichier);
                        $category = new Categories();
                        $category->setNom(str_replace('_', ' ', ucfirst($path_parts['filename'])))
                            ->setImage($this->getReference('media_' . $path_parts['filename']));
                        $this->addReference('category_'. $path_parts['filename'], $category);
                        $manager->persist($category);
                    }
                }
            }
        }
    }

    public function load(ObjectManager $manager)
    {
        $this->getReferences('web/images/categories', $manager);
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}