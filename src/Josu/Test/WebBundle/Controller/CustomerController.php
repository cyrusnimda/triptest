<?php

namespace Josu\Test\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use Josu\Test\WebBundle\Entity\Passenger;
use Josu\Test\WebBundle\Entity\Trip;

use Josu\Test\WebBundle\Form\TripType;
use Josu\Test\WebBundle\Form\PassengerType;
use Josu\Test\WebBundle\Service\SessionHandler;

class CustomerController extends Controller
{

    /**
     * @Route("/details", name="_details")
     * @Route("/", name="homepage")
     * @Template()
     */
    public function detailsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // Check is the user is logged in the system
        $sessionHandler = $this->get("app.session_handle");
        $customer = $sessionHandler->loadSessionUser();
        if(!$customer){
            return $this->redirectToRoute('login_route');
        }

        //Create de passenger form
        $passenger = new Passenger();
        $passengerForm = $this->createForm(new PassengerType(), $passenger);
        $passengerForm->handleRequest($request);

        // if the passenger form is sent, save the record
        if ($passengerForm->isValid()) {
            $em->persist($passengerForm->getData());
            $em->flush();
            return $this->redirectToRoute('_details');
        }

        // Create the trip form
        $trip = new Trip();
        $tripForm = $this->createForm(new TripType(), $trip);
        $tripForm->handleRequest($request);

        // if the trip form is sent, save the record
        if ($tripForm->isValid()) {
            $trip = $tripForm->getData();
            $trip->setCustomer($customer);
            $em->persist($trip);
            $em->flush();
            return $this->redirectToRoute('_details');
        }

        //show all passengers
        $passengers = $em->getRepository('JosuTestWebBundle:Passenger')->findAll();

        //show all trips from the actual customer
        $trips = $em->getRepository('JosuTestWebBundle:Trip')->findByCustomer($customer);

        return array('tripForm'=>$tripForm->createView(), 'trips'=>$trips,'customer' => $customer, 'form' => $passengerForm->createView(), 'passengers' => $passengers);
    }
}
