<?php
/**
 * Created by PhpStorm.
 * User: matthieu
 * Date: 25/07/2018
 * Time: 19:21
 */

namespace Ecommerce\EcommerceBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RechercheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'recherche',
            TextType::class,
            array(
                'label' => false,
                'attr' => array('class' => 'input-medium search-query')
            )
        );
    }
}