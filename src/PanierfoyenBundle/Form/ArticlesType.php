<?php

namespace PanierfoyenBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('contenue')
            ->add('publicationDate')
            ->add('modified')
            ->add('created')
//            ->add('slug')
            ->add('user')
            ->add('tag')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PanierfoyenBundle\Entity\Articles'
        ));
    }

    public function getBlockPrefix()
    {
        return 'panierfoyenbundle_articles';
    }
}
