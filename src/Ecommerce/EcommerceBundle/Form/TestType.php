<?php
/**
 * Created by PhpStorm.
 * User: matthieu
 * Date: 19/07/2018
 * Time: 20:30
 */

namespace Ecommerce\EcommerceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class TestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // ici nous allons faire notre formulaire en php
        $builder
            ->add('email', 'email', array('required' => false))
            ->add('nom')
            ->add('prenom')
            ->add('utilisateurs', 'entity', array('class' => 'Utilisateurs\UtilisateursBundle\Entity\Utilisateurs'))
            ->add('contenu', 'textarea')
            ->add('date','datetime')
            ->add('pays', 'country')
            ->add('envoyer', 'submit');
    }

    public function getName()
    {
        return 'ecommerce_ecommercebundle_test';
    }
}