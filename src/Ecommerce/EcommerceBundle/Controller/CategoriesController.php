<?php
/**
 * Created by PhpStorm.
 * User: matthieu
 * Date: 24/07/2018
 * Time: 23:22
 */

namespace Ecommerce\EcommerceBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoriesController extends  Controller
{
    public function menuAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('EcommerceBundle:Categories')->findAll();
        return $this->render('EcommerceBundle:Default/categories/layout:menu.html.twig', array('categories' => $categories));
    }
}