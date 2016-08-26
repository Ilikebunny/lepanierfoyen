<?php

namespace PanierfoyenBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContentDynamicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('contenue')
            ->add('summary')
            ->add('order')
            ->add('published')
            ->add('publicationDate')
            ->add('modified')
            ->add('created')
            ->add('slug')
            ->add('user')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PanierfoyenBundle\Entity\ContentDynamic'
        ));
    }

    public function getBlockPrefix()
    {
        return 'panierfoyenbundle_contentdynamic';
    }
}
