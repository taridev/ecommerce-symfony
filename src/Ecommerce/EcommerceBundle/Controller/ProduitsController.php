<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProduitsController extends Controller
{
    public function produitsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('EcommerceBundle:Produits')->findAll();
        return $this->render('EcommerceBundle:Default:produits/layout/produits.html.twig', array('produits' => $produits));
    }

    public function presentationAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository('EcommerceBundle:Produits')->find($id);
        return $this->render('EcommerceBundle:Default:produits/layout/presentation.html.twig', array('produit' => $produit));
    }

    public function categorieAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('EcommerceBundle:Produits')
            ->findBy(array('categorie' => $id));
        return $this->render('EcommerceBundle:Default:produits/layout/produits.html.twig', array('produits' => $produits));
    }
}
