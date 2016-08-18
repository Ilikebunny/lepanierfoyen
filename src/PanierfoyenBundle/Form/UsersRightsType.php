<?php

namespace PanierfoyenBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UsersRightsType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('username')
                ->add('coordinateur')
                ->add('groups')
                ->add('role', ChoiceType::class, array(
                    'mapped' => false,
                    'choices' => array(
                        'Administrateur' => 'ROLE_ADMIN',
                        'Publicateur' => 'ROLE_PUBLISHER',
                        'Coordinateur' => 'ROLE_COORDINATEUR',
            )))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'PanierfoyenBundle\Entity\Users'
        ));
    }

    public function getBlockPrefix() {
        return 'panierfoyenbundle_users';
    }

}
