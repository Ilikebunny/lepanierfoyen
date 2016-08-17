<?php

namespace PanierfoyenBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TagsType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('title')
//            ->add('created')
//            ->add('modified')
//                ->add('article')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'PanierfoyenBundle\Entity\Tags'
        ));
    }

    public function getBlockPrefix() {
        return 'panierfoyenbundle_tags';
    }

}
