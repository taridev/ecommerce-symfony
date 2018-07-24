<?php
/**
 * Created by PhpStorm.
 * User: matthieu
 * Date: 23/07/2018
 * Time: 22:44
 */

namespace Ecommerce\EcommerceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Utilisateurs\UtilisateursBundle\Entity\Utilisateurs;

use Doctrine\Common\Persistence\ObjectManager;

class UtilisateurData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function load(ObjectManager $manager)
    {
        $utilisateur1 = new Utilisateurs();
        $utilisateur1->setUsername('client');
        $utilisateur1->setEmail('client@mail.com');
        $utilisateur1->setEnabled(true);
        $password = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($utilisateur1)
            ->encodePassword('password', $utilisateur1->getSalt());
        $utilisateur1->setPassword($password);
        $this->setReference('utilisateur_client', $utilisateur1);
        $manager->persist($utilisateur1);
        $manager->flush();
    }

    public function getOrder()
    {
        return 5;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}