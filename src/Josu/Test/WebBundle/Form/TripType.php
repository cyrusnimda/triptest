<?php

namespace Josu\Test\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('departureAirport')
            ->add('destinationAirport')
            ->add('departureDate')
            ->add('arrivalDate')
            ->add('passengers', 'entity', array(
                'class' => 'JosuTestWebBundle:Passenger',
                'property'     => 'name',
                'multiple'     => true,
                'expanded'     => true,
            ))
            ->add('save', 'submit');
    }

    public function getName()
    {
        return 'trip';
    }
}