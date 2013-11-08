<?php

namespace MY\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('attr' => array('placeholder' => 'Untitled')))
            ->add('content', 'textarea', array('required'  => false, 'attr' => array()))
            ->add('public', 'checkbox', array(
                'label'     => 'Show this entry publicly?',
                'required'  => false,
            ))
            ->add('save', 'submit', array())
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'MY\EntityBundle\Entity\Note',
            )
        );
    }

    public function getName()
    {
        return 'note';
    }
}
