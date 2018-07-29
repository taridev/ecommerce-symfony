<?php
/**
 * Created by PhpStorm.
 * User: matthieu
 * Date: 28/07/2018
 * Time: 00:13
 */

namespace Ecommerce\EcommerceBundle\Services;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class GetReference
{
    public function __construct($security_context, $entityManager)
    {
        $this->securityContext = $security_context;
        $this->em = $entityManager;
    }

    public function reference()
    {
        $reference = $this->em->getRepository('EcommerceBundle:Commandes')->findOneBy(array('valider' => 1), array('id' => 'DESC'),1,1);

        if (!$reference)
            return 1;
        return $reference->getReference() + 1;
    }
}