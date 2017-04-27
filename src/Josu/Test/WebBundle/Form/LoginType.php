<?php

namespace Josu\Test\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('password', 'password')
            ->add('submit', 'submit', array('label' => 'Login'));
    }

    public function getName()
    {
        return 'login';
    }
}
