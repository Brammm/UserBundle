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
        $builder->add('email');
        $builder->add('password', 'repeated', [
            'type' => 'password',
        ]);
        $builder->add('firstName');
        $builder->add('lastName');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Brammm\UserBundle\Entity\User',
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