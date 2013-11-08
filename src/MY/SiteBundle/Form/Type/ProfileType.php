<?php

namespace MY\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array('label' => 'Full name'));
        $builder->add('password', 'password', array('label' => 'Change password', 'required'=>false));
        $builder->add('birthday', 'birthday', array('label' => 'Birthday'));
        $builder->add('email', 'email', array('label' => 'Email'));
        $builder->add('city', 'text', array('label' => 'city'));
        $builder->add('live_in', 'text', array('label' => 'live in'));
        $builder->add('avatar', 'file', array(
                'label' => 'Change photos',
                'required' => false
            )
        );

        $builder->add('languages', 'entity', array(
                'label' => 'Career',
                'required' => false,
                'empty_value' => '-- Select languages (Others) --',
                'class' => 'MYEntityBundle:Languages',
                'property' => 'name',
            )
        );


        $builder->add('country', 'country', array('label' => 'Nationality'));
    }


    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'MY\EntityBundle\Entity\User',
            )
        );
    }

    public function getName()
    {
        return 'profile';
    }
}
