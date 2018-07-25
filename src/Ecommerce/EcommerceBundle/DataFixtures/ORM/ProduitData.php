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
use Ecommerce\EcommerceBundle\Entity\Produits;
use Doctrine\Common\Persistence\ObjectManager;

class ProduitData extends AbstractFixture implements OrderedFixtureInterface
{

    private function recupererProduit($dossier, ObjectManager $manager) // Fonction qui liste un dossier de façon récursive
    {
        if (is_dir($dossier)) {
            if ($dossierOuvert = opendir($dossier)) {
                while (($fichier = readdir($dossierOuvert)) !== false) {
                    $path = $dossier.DIRECTORY_SEPARATOR.$fichier;
                    if ($fichier === '.'or $fichier === '..' or $fichier === 'index.php' or $fichier === '.DS_Store' or $fichier === 'categories') {
                        continue;
                    } elseif (is_dir($path)) {
                        $this->recupererProduit($path, $manager);
                    } else {
                        $produit = new Produits();
                        $path_parts = pathinfo($fichier);
                        $produit->setNom(str_replace('_', ' ', ucfirst($path_parts['filename'])))
                            ->setImage($this->getReference('media_' . $path_parts['filename']))
                            ->setTva($this->getReference('tva2'))
                            ->setCategorie($this->getReference('category_'. basename($dossier)))
                            ->setDescription('Tunc rationabili cum gravius ideo constantia sub responderunt inopia efferatus orientis inopia et cum vilitatem et vertices iussit fixa unum.')
                            ->setPrix('3.99')
                            ->setStock(10)
                            ->setDisponible(true);
                        $this->addReference('produit_'. basename($fichier), $produit);
                        $manager->persist($produit);
                    }
                }
            }
        } else {
            die('Erreur, le paramètre précisé dans la fonction n\'est pas un dossier!');
        }
    }

    public function load(ObjectManager $manager)
    {
        $this->recupererProduit('web/images', $manager);
        $manager->flush();
    }

    public function getOrder()
    {
        return 4;
    }
}