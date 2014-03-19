<?php

namespace Brammm\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegisterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'email', [
            'label' => 'email'
        ]);
        $builder->add('plainPassword', 'repeated', [
            'type' => 'password',
            'invalid_message' => 'match_passwords',
            'first_options' => [
                'label' => 'password',
            ],
            'second_options' => [
                'label' => 'password_confirm',
            ],
        ]);
        $builder->add('firstName', 'text', [
            'label' => 'first_name',
        ]);
        $builder->add('lastName', 'text', [
            'label' => 'last_name',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Brammm\UserBundle\Entity\User',
            'validation_groups' => array('register'),
            'intention' => 'register',
        ]);
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'register';
    }
}