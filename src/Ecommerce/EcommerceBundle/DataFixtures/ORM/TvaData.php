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
use Ecommerce\EcommerceBundle\Entity\Tva;
use Doctrine\Common\Persistence\ObjectManager;

class TvaData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $tva1 = new Tva();
        $tva1->setMultiplicate('0.982')
            ->setNom('TVA 1.75')
            ->setValeur('1.75');
        $manager->persist($tva1);

        $tva2 = new Tva();
        $tva2->setMultiplicate('0.833')
            ->setNom('TVA 20%')
            ->setValeur('20');
        $manager->persist($tva2);

        $manager->flush();

        $this->addReference('tva1', $tva1);
        $this->addReference('tva2', $tva2);
    }

    public function getOrder()
    {
        return 3;
    }
}