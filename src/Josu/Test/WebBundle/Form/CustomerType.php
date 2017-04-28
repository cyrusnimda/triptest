<?php

namespace Josu\Test\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Josu\Test\WebBundle\Entity\Customer;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('name')
            ->add('address')
            ->add('city')
            ->add('country')
            ->add('save', 'submit', array('label' => 'Save'));
    }

    public function getName()
    {
        return 'passenger';
    }
}
