<?php

namespace PanierfoyenBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProducteursType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('nom')
                ->add('codepostal')
                ->add('ville')
                ->add('adr1')
                ->add('adr2')
                ->add('adr3')
                ->add('tel')
                ->add('mobile')
                ->add('siteInternet')
                ->add('chequeOrdre')
                ->add('email')
                ->add('coordinateur', null, array('attr' => array(
                        'class' => 'chosen-select'
            )))
                ->add('category', null, array('attr' => array(
                        'class' => 'chosen-select'
            )))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'PanierfoyenBundle\Entity\Producteurs'
        ));
    }

    public function getBlockPrefix() {
        return 'panierfoyenbundle_producteurs';
    }

}
