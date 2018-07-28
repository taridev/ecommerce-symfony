<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Ecommerce\EcommerceBundle\Entity\Adresses;
use Ecommerce\EcommerceBundle\Form\AdressesType;
use function MongoDB\BSON\toJSON;
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

    public function adresseSuppressionAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('EcommerceBundle:Adresses')
            ->find($id);

        // Gestion d'erreurs : Utilisateur correspond à l'adresse et adresse existe
        if ($this->container->get('security.token_storage')->getToken()->getUser() === $entity->getUtilisateur() and is_object($entity)) {
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('livraison'));
    }

    public function livraisonAction()
    {
        $entity = new Adresses();
        $form = $this->createForm(new AdressesType(), $entity);
        // Récupération de l'utilisateur
        $utilisateur = $this->container->get('security.token_storage')->getToken()->getUser();

        if ($this->get('request')->getMethod() === 'POST') {
            $form->handleRequest($this->get('request'));
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $entity->setUtilisateur($utilisateur);
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('livraison'));
            }
        }

        return $this->render(
            'EcommerceBundle:Default:panier/layout/livraison.html.twig',
            array(
                'form' => $form->createView(),
                'utilisateur' => $utilisateur
            )
        );
    }

    public function validationAction()
    {
        return $this->render('EcommerceBundle:Default:panier/layout/validation.html.twig');
    }
}
