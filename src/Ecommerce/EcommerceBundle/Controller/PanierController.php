<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PanierController extends Controller
{
    public function menuAction()
    {
        $session = $this->get('request')->getSession();
        if (!$session->has('panier')) {
            $session->set('panier', array());
        }

        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('EcommerceBundle:Produits')->findArray(array_keys($session->get('panier')));

        return $this->render(
            ':modulesUsed:panier.html.twig',
            array(
                'produits' => $produits,
                'panier' => $session->get('panier')
            )
        );
    }

    public function ajouterAction($id)
    {
        $session = $this->get('request')->getSession();

        if (!$session->has('panier')) {
            $session->set('panier', array());
        }

        $panier = $session->get('panier');

        if (array_key_exists($id, $panier)) {
            if ($this->get('request')->query->get('qte') != null)
                $panier[$id] = $this->get('request')->query->get('qte');
            $this->get(('session'))->getFlashBag()->add('success', 'La quantité a bien été modifiée.');
        } else {
            if ($this->get('request')->query->get('qte') != null) {
                $panier[$id] = $this->get('request')->query->get('qte');
            } else {
                $panier[$id] = "1";
            }
            $this->get(('session'))->getFlashBag()->add('success', 'Article Ajouté avec succès');
        }
        $session->set('panier', $panier);

        return $this->redirect($this->generateUrl('panier'));
    }

    public function supprimerAction($id)
    {
        $session = $this->get('request')->getSession();
        $panier = $session->get('panier');

        if (array_key_exists($id, $panier)) {
            unset($panier[$id]);
            $session->set('panier', $panier);
            $this->get(('session'))->getFlashBag()->add('success', 'Article supprimé avec succès');
        }

        return $this->redirect($this->generateUrl('panier'));
    }

    public function panierAction()
    {
        $session = $this->get('request')->getSession();
        if (!$session->has('panier')) {
            $session->set('panier', array());
        }

        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('EcommerceBundle:Produits')->findArray(array_keys($session->get('panier')));

        return $this->render(
            'EcommerceBundle:Default:panier/layout/panier.html.twig',
            array(
                'produits' => $produits,
                'panier' => $session->get('panier')
            )
        );
    }

    public function livraisonAction()
    {
        return $this->render('EcommerceBundle:Default:panier/layout/livraison.html.twig');
    }

    public function validationAction()
    {
        return $this->render('EcommerceBundle:Default:panier/layout/validation.html.twig');
    }
}
