<?php

namespace Josu\Test\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('departureAirport')
            ->add('destinationAirport')
            ->add('departureDate', new DateTimeType(), array(
                'widget' => 'single_text',
                'attr' => ['class' => 'dateTimePicker']
                ))
            ->add('arrivalDate', new DateTimeType(), array(
                'widget' => 'single_text',
                'attr' => ['class' => 'dateTimePicker']
                ))
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
