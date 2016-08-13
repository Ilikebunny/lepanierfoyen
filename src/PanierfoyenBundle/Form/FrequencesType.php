<?php

namespace PanierfoyenBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FrequencesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle')
            ->add('details')
            ->add('jours')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PanierfoyenBundle\Entity\Frequences'
        ));
    }

    public function getBlockPrefix()
    {
        return 'panierfoyenbundle_frequences';
    }
}
