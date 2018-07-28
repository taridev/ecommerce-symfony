<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Ecommerce\EcommerceBundle\Entity\Categories;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ecommerce\EcommerceBundle\Form\RechercheType;

class ProduitsController extends Controller
{
    public function produitsAction(Categories $categorie = null)
    {
        $session = $this->get('request')->getSession();
        $em = $this->getDoctrine()->getManager();
        if ($categorie != null) {
            $produits = $em->getRepository('EcommerceBundle:Produits')
                ->findBy(
                    array(
                        'categorie' => $categorie,
                        'disponible' => 1
                    )
                );
        } else {
            $produits = $em->getRepository('EcommerceBundle:Produits')
                ->findBy(array('disponible' => 1));
        }

        if ($session->has('panier')) {
            $panier = $session->get('panier');
        } else {
            $panier = false;
        }

        return $this->render(
            'EcommerceBundle:Default:produits/layout/produits.html.twig',
            array(
                'produits' => $produits,
                'panier' => $panier,
            )
        );
    }

    public function presentationAction($id)
    {
        $session = $this->get('request')->getSession();
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository('EcommerceBundle:Produits')->find($id);

        if (!$produit) {
            throw $this->createNotFoundException('Le produit n\'existe pas.');
        }

        if ($session->has('panier')) {
            $panier = $session->get('panier');
        } else {
            $panier = false;
        }

        return $this->render(
            'EcommerceBundle:Default:produits/layout/presentation.html.twig',
            array(
                'produit' => $produit,
                'panier' => $panier,
            )
        );
    }

    public function rechercheAction()
    {
        $form = $this->createForm(new RechercheType());
        return $this->render('EcommerceBundle:Default/recherche/modulesUsed:recherche.html.twig', array('form' => $form->createView()));
    }

    public function rechercheTraitementAction()
    {
        $form = $this->createForm(new RechercheType());

        if ($this->get('request')->getMethod() === 'POST') {
            // $form->bind($this->get('request')); <- DEPRECATED
            $form->handleRequest($this->get('request'));
            $em = $this->getDoctrine()->getManager();
            $produits = $em->getRepository('EcommerceBundle:Produits')->recherche($form['recherche']->getData());
            return $this->render('EcommerceBundle:Default:produits/layout/produits.html.twig', array('produits' => $produits));
        } else {
            throw $this->createNotFoundException('La page n\'existe pas.');
        }

    }
}
