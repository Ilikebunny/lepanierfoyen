<?php

namespace PanierfoyenBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('codepostal')
            ->add('ville')
            ->add('adr1')
            ->add('adr2')
            ->add('adr3')
            ->add('tel')
            ->add('mobile')
            ->add('role')
            ->add('created')
            ->add('modified')
            ->add('coordinateur')
            ->add('groups')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PanierfoyenBundle\Entity\Users'
        ));
    }

    public function getBlockPrefix()
    {
        return 'panierfoyenbundle_users';
    }
}
