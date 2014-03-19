<?php

namespace Brammm\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LoginType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'email', [
            'label' => 'email',
        ]);
        $builder->add('password', 'password', [
            'label' => 'password',
        ]);
        $builder->add('rememberMe', 'checkbox', [
            'required' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Brammm\UserBundle\Form\Model\Login',
            'intention'  => 'authenticate',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'login';
    }
}