<?php

namespace PanierfoyenBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class ArticlesType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('titre')
                ->add('contenue', CKEditorType::class)
//                ->add('publicationDate')
//            ->add('modified')
//            ->add('created')
//            ->add('slug')
//                ->add('user')
                ->add('tag', null, array('attr' => array(
                        'class' => 'chosen-select'
            )))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'PanierfoyenBundle\Entity\Articles'
        ));
    }

    public function getBlockPrefix() {
        return 'panierfoyenbundle_articles';
    }

}
