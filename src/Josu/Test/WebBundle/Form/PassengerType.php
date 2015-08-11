<?php

namespace Josu\Test\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PassengerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('title', 'text')
          ->add('firstname', 'text')
          ->add('surname', 'text')
          ->add('passportid', 'text')
          ->add('save', 'submit', array('label' => 'Add'));
    }

    public function getName()
    {
        return 'passenger';
    }
}
