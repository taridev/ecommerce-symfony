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
use Ecommerce\EcommerceBundle\Entity\Commandes;
use Doctrine\Common\Persistence\ObjectManager;

class CommandeData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $commande1 = new Commandes();
        $commande1->setUtilisateur($this->getReference('utilisateur_client'))
            ->setValider(true)
            ->setDate(new \DateTime())
            ->setReference('2832467')
            ->setProduits(array(
                '0' => array('1' => '2'),
                '1' => array('2' => '1'),
                '2' => array('4' => '5'),
            ));
        $manager->persist($commande1);
        $manager->flush();
    }

    public function getOrder()
    {
        return 7;
    }
}