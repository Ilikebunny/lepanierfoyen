<?php

namespace PanierfoyenBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class ContentDynamicType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('titre')
                ->add('contenue', CKEditorType::class)
//            ->add('summary')
                ->add('order',null,array('label'=>"Ordre"))
//            ->add('published')
//            ->add('publicationDate')
//            ->add('modified')
//            ->add('created')
//            ->add('slug')
//            ->add('user')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'PanierfoyenBundle\Entity\ContentDynamic'
        ));
    }

    public function getBlockPrefix() {
        return 'panierfoyenbundle_contentdynamic';
    }

}
