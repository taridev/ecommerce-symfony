<?php

namespace Utilisateurs\UtilisateursBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Spipu\Html2Pdf\Html2Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UtilisateursController extends Controller
{
    public function factureAction()
    {
        $em = $this->getDoctrine()->getManager();
        $factures = $em->getRepository('EcommerceBundle:Commandes')->byFacture($this->getUser());

        return $this->render('UtilisateursBundle:Default:layout/facture.html.twig', array('factures' => $factures));
    }

    public function facturePdfAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $facture = $em->getRepository('EcommerceBundle:Commandes')
            ->findOneBy(array(
                'utilisateur' => $this->getUser(),
                'valider' => true,
                'id' => $id));
        if (!$facture) {
            $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
            return $this->redirect($this->generateUrl('factures'));
        }

        $html = $this->renderView('UtilisateursBundle:Default/layout:facturePDF.html.twig', array('facture' => $facture));

        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->writeHTML($html);
        $html2pdf->output('Facture.pdf');

        $response = new Response();
        $response->headers->set('Content-Type', 'application/pdf');
        return $response;
    }
}
