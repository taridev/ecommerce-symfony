<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Ecommerce\EcommerceBundle\Entity\Commandes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class CommandesController extends Controller
{
    public function facture(Request $request)
    {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        // Pour l'utilisation d'une API de payment
        $generator = $this->container->get('security.secure_random');
        $adresse = $session->get('adresse');
        $panier = $session->get('panier');
        $commande = array();
        $totalHT = 0;
        $totalTTC = 0;

        $facturation = $em->getRepository('EcommerceBundle:Adresses')->find($adresse['facturation']);
        $livraison = $em->getRepository('EcommerceBundle:Adresses')->find($adresse['livraison']);
        $produits = $em->getRepository('EcommerceBundle:Produits')->findArray(array_keys($session->get('panier')));

        foreach ($produits as $produit) {
            $prixHT = ($produit->getPrix() * $panier[$produit->getId()]);
            $prixTTC = $prixHT / $produit->getTva()->getMultiplicate();
            $totalHT += $prixHT;
            $totalTTC += $prixTTC;

            if (!isset($commande['tva']['%'.$produit->getTva()->getValeur()])) {
                $commande['tva']['%'.$produit->getTva()->getValeur()] = round($prixTTC - $prixHT, 2);
            } else {
                $commande['tva']['%'.$produit->getTva()->getValeur()] += round($prixTTC - $prixHT, 2);
            }

            $commande['produits'][$produit->getId()] = array(
                'image' => $produit->getImage(),
                'reference' => $produit->getNom(),
                'quantite' => $panier[$produit->getId()],
                'prixHT' => round($produit->getPrix(), 2),
                'prixTTC' => round($produit->getPrix() / $produit->getTva()->getMultiplicate()));
        }
        $commande['livraison'] = array(
            'prenom' => $livraison->getPrenom(),
            'nom' => $livraison->getNom(),
            'telephone' => $livraison->getTelephone(),
            'adresse' => $livraison->getAdresse(),
            'cp' => $livraison->getCp(),
            'ville' => $livraison->getVille(),
            'pays' => $livraison->getPays(),
            'complement' => $livraison->getComplement());
        $commande['facturation'] = array(
            'prenom' => $facturation->getPrenom(),
            'nom' => $facturation->getNom(),
            'telephone' => $facturation->getTelephone(),
            'adresse' => $facturation->getAdresse(),
            'cp' => $facturation->getCp(),
            'ville' => $facturation->getVille(),
            'pays' => $facturation->getPays(),
            'complement' => $facturation->getComplement());
        $commande['prixHT'] = round($totalHT, 2);
        $commande['prixTTC'] = round($totalTTC, 2);
        $commande['token'] = bin2hex($generator->nextBytes(20));

        return $commande;
    }

    public function prepareCommandeAction(Request $request)
    {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();

        if (!$session->has('commande')) {
            $commande = new Commandes();
        } else {
            $commande = $em->getRepository('EcommerceBundle:Commandes')->find($session->get('commande'));
        }

        $commande->setDate(new \DateTime());
        $commande->setUtilisateur($this->container->get('security.token_storage')->getToken()->getUser());
        $commande->setValider(0);
        $commande->setReference(0);
        $commande->setCommande($this->facture($request));

        if (!$session->has('commande')) {
            $em->persist($commande);
            $session->set('commande', $commande);
        }
        $em->flush();
        return new Response($commande->getId());
    }

    /**
     * Méthode remplaçant l'API banque.
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function validationCommandeAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $commande = $em->getRepository('EcommerceBundle:Commandes')->find($id);

        if (!$commande or $commande->getValider() == true) {
            throw $this->createNotFoundException('La commande n\'existe pas.');
        }

        $commande->setValider(true);
        $commande->setReference($this->container->get('setNewReference')->reference()); // service
        $em->flush();

        $session = $request->getSession();
        $session->remove('adresse');
        $session->remove('panier');
        $session->remove('commande');

        $this->get('session')->getFlashBag()->add('success', 'Votre commande est validée avec succès.');
        return $this->redirect($this->generateUrl('factures'));
    }

}