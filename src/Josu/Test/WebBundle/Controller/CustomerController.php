<?php

namespace Josu\Test\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use Josu\Test\WebBundle\Entity\Customer;
use Josu\Test\WebBundle\Entity\Passenger;
use Josu\Test\WebBundle\Entity\Trip;

use Josu\Test\WebBundle\Form\CustomerType;
use Josu\Test\WebBundle\Form\TripType;
use Josu\Test\WebBundle\Form\PassengerType;
use Josu\Test\WebBundle\Service\SessionHandler;

/**
 * @Route("/customer")
 */
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

        //Create customer form
        $customerForm = $this->createForm(new CustomerType(), $customer);

        // if the passenger form is sent, save the record
        $customerForm->handleRequest($request);
        if ($customerForm->isSubmitted() && $customerForm->isValid()) {
            $editedCustomer = $customerForm->getData();
            $em->persist($editedCustomer);
            $em->flush($editedCustomer);
            return $this->redirectToRoute('_details');
        }

        //Create passenger form
        $passenger = new Passenger();
        $passengerForm = $this->createForm(new PassengerType(), $passenger);

        // if the passenger form is sent, save the record
        $passengerForm->handleRequest($request);
        if ($passengerForm->isSubmitted() && $passengerForm->isValid()) {
            $passenger = $passengerForm->getData();
            $em->persist($passenger);
            $em->flush($passenger);
            return $this->redirectToRoute('_details');
        }

        // Create the trip form
        $trip = new Trip();
        $tripForm = $this->createForm(new TripType(), $trip);

        // if the trip form is sent, save the record
        $tripForm->handleRequest($request);
        if ($tripForm->isSubmitted() && $tripForm->isValid()) {
            $trip = $tripForm->getData();
            $trip->setCustomer($customer);
            $em->persist($trip);
            $em->flush($trip);
            return $this->redirectToRoute('_details');
        }

        //show all passengers
        $passengers = $em->getRepository('JosuTestWebBundle:Passenger')->findAll();

        //show all trips from the actual customer
        $trips = $em->getRepository('JosuTestWebBundle:Trip')->findByCustomer($customer);

        return array(
            'customerForm' => $customerForm->createView(),
            'passengerForm' => $passengerForm->createView(),
            'tripForm'=>$tripForm->createView(),
            'passengers' => $passengers,
            'trips'=>$trips,'customer' => $customer,
        );
    }
}
