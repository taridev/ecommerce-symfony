<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ecommerce\EcommerceBundle\Entity\Produits;

class TestController extends Controller
{
    public function ajoutAction()
    {
        $em = $this->getDoctrine()->getManager();

        $produit = new Produits();
        $produit->setCategorie('Legume');
        $produit->setDescription('La tomate se mange.');
        $produit->setDisponible('1');
        $produit->setImage('https://www.frutadelasarga.com/server/Portal_0008706/img/categories/Tomate.jpg');
        $produit->setNom('Tomate');
        $produit->setPrix('0.99');
        $produit->setTva('19.6');

        $em->persist($produit);
        $em->flush();

        return $this->render('EcommerceBundle:Default:produits/layout/produits.html.twig');
    }
}