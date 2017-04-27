<?php

namespace Josu\Test\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Josu\Test\WebBundle\Entity\Passenger;

class PassengerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',
                'choice',
                array( 'choices' => Passenger::getTitles() )
                )
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
