<?php

namespace PanierfoyenBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle')
            ->add('descriptif')
            ->add('image')
            ->add('category', null, array('attr' => array(
                        'class' => 'chosen-select'
            )))
            ->add('frequence', null, array('attr' => array(
                        'class' => 'chosen-select'
            )))
            ->add('producteur', null, array('attr' => array(
                        'class' => 'chosen-select'
            )))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PanierfoyenBundle\Entity\Produits'
        ));
    }

    public function getBlockPrefix()
    {
        return 'panierfoyenbundle_produits';
    }
}
