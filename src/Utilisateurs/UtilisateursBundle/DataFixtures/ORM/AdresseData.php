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
use Ecommerce\EcommerceBundle\Entity\Adresses;
use Doctrine\Common\Persistence\ObjectManager;

class AdresseData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $adresse1 = new Adresses();
        $adresse1->setUtilisateur($this->getReference('utilisateur_client'))
            ->setNom('Doe')
            ->setPrenom('John')
            ->setTelephone('0680394500')
            ->setAdresse('3 rue de l\'entreprise ')
            ->setCp('75001')
            ->setVille('Paris')
            ->setPays('France')
            ->setComplement('');
        $manager->persist($adresse1);

        $adresse2 = new Adresses();
        $adresse2->setUtilisateur($this->getReference('utilisateur_client'))
            ->setNom('Doe')
            ->setPrenom('John')
            ->setTelephone('0135448918')
            ->setAdresse('2 rue du domicile')
            ->setCp('75001')
            ->setVille('Paris')
            ->setPays('France')
            ->setComplement('');
        $manager->persist($adresse2);

        $manager->flush();
    }

    public function getOrder()
    {
        return 6;
    }
}