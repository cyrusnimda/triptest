<?php

namespace Josu\Test\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Josu\Test\WebBundle\Entity\Passenger;

use Symfony\Component\HttpFoundation\Session\Session;
use Josu\Test\WebBundle\Entity\Trip;

use Josu\Test\WebBundle\Form\TripType;
use Josu\Test\WebBundle\Form\PassengerType;

class DefaultController extends Controller
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
        $customer = $this->loadSessionUser();
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


    /**
     * @Route("/passenger/delete/{passenger_id}", name="delete_passenger", requirements={"passenger_id" = "\d+"}, defaults={"passenger_id" = 0})
     */
    public function deletePassengerAction($passenger_id)
    {
        // check the parameter.
        if($passenger_id == 0)
            throw new Exception("Don't be evil");

        $em = $this->getDoctrine()->getManager();
        $passenger = $em->getRepository('JosuTestWebBundle:Passenger')->find($passenger_id);
        if(!$passenger)
            throw new NotFoundHttpException("passenger don't found");

        //delete the passenger
        $em->remove($passenger);
        $em->flush();

        return $this->redirect($this->generateUrl('_details'));
    }

    /**
     * @Route("/trip/delete/{trip_id}", name="trip_id", requirements={"trip_id" = "\d+"}, defaults={"trip_id" = 0})
     */
    public function deleteTripAction($trip_id)
    {
        // check if is a valid id, throw exception in other case.
        if($trip_id == 0)
            throw new Exception("Don't be evil");

        $em = $this->getDoctrine()->getManager();
        $trip = $em->getRepository('JosuTestWebBundle:Trip')->find($trip_id);
        if(!$trip)
            throw new NotFoundHttpException("trip don't found");

        //delete the trip and redirect to details page
        $em->remove($trip);
        $em->flush();

        return $this->redirect($this->generateUrl('_details'));
    }

    /*
     * Get the customer from the session, if there is none,
     * redirect to the login page.
     *
     * return Customer object.
     */
    private function loadSessionUser(){
    	$em = $this->getDoctrine()->getManager();

    	// if there is no user in session, redirect to login.
    	$session = new Session();
    	$sessionUser = $session->get('loginUser');
    	if(!$sessionUser){
    		return false;
    	}

    	// Load the session customer
    	$customer = $em->getRepository('JosuTestWebBundle:Customer')->find( $sessionUser );
    	if(!$customer){
            throw new NotFoundHttpException("Customer don't found");
    	}
    	return $customer;
    }

    /*
	 * Check in the database is the customer exists.
	 * @param Customer $user -> the user to check
	 *
	 * return Customer object or null.
     */
    private function loginSuccess($user){
    	$em = $this->getDoctrine()->getManager();
    	$userLogin = $em->getRepository('JosuTestWebBundle:Customer')->findOneBy( array('name'=>$user->getName(), 'password'=>$user->getPassword() ) );
    	return $userLogin;
    }

}
