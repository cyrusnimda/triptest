<?php

namespace Josu\Test\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Josu\Test\WebBundle\Entity\Passenger;
use Josu\Test\WebBundle\Entity\Customer;
use Symfony\Component\HttpFoundation\Session\Session;
use Josu\Test\WebBundle\Entity\Trip;

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

    	//Create de passenger form
    	$passenger = new Passenger();

        $form = $this->createFormBuilder($passenger)
            ->add('title', 'text')
            ->add('firstname', 'text')
            ->add('surname', 'text')
            ->add('passportid', 'text')
            ->add('save', 'submit', array('label' => 'Add'))
            ->getForm();

        $form->handleRequest($request);

        // if the passenger form is sent, save the record
	    if ($form->isValid()) {
	       	
	       	$em->persist($form->getData());
       		$em->flush();
	        return $this->redirectToRoute('_details');
	    }

	    //show all passengers
	    $passengers = $em->getRepository('JosuTestWebBundle:Passenger')->findAll();

	    //show all trips from the actual customer
	    $trips = $em->getRepository('JosuTestWebBundle:Trip')->findByCustomer($customer);


        return array('trips'=>$trips,'customer' => $customer, 'form' => $form->createView(), 'passengers' => $passengers);
    }


    /**
     * @Route("/passenger/delete/{passenger_id}", name="delete_passenger", requirements={"passenger_id" = "\d+"}, defaults={"passenger_id" = 0})
     */
    public function deletePassengerAction($passenger_id)
    {
        // comprobar que es un id válido, sino mostrar una excepcion.
        if($passenger_id == 0)
            throw new Exception("Don't be evil");

        $em = $this->getDoctrine()->getManager();
        $passenger = $em->getRepository('JosuTestWebBundle:Passenger')->find($passenger_id);
        if(!$passenger)
            throw new NotFoundHttpException("passenger don't found");

        //borrar la venta y redireccionar a la pagina de inicio de admin
        $em->remove($passenger);
        $em->flush();

        return $this->redirect($this->generateUrl('_details'));
    }

     /**
     * @Route("/login", name="login_route")
     * @Template()
     */
    public function loginAction(Request $request)
    {
    	//Create de passenger form
    	$user = new Customer();
    	$error = null;

        $form = $this->createFormBuilder($user)
            ->add('name', 'text')
            ->add('password', 'password')
            ->add('submit', 'submit', array('label' => 'Login'))
            ->getForm();

        $form->handleRequest($request);

        // if the form is sent, save the record
	    if ($form->isValid()) {
	       	$user = $form->getData();
	       	$userLogin = $this->loginSuccess($user);
	       	if($userLogin != null){
				$session = new Session();

				// set and get session attributes
				$session->set('loginUser', $userLogin->getId() );

	       		return $this->redirectToRoute('_details');
	       	} else{
	       		$error= "User or password incorrect.";
	        }
	    }

	    return array('error'=> $error, 'form' => $form->createView());

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

    /**
     * @Route("/trip/add", name="addTrip")
     */
    public function addTripAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();

    	// check the session user
    	$customer = $this->loadSessionUser();

    	$trip = new Trip;
    	$trip->setDepartureAirport($request->get("from"));
    	$trip->setDestinationAirport($request->get("to"));

		$departureDate = new \DateTime( $request->get("departure") );
		$arrivalDate = new \DateTime( $request->get("arrival") );

    	$trip->setDepartureDate($departureDate);
    	$trip->setArrivalDate($arrivalDate);
    	$trip->setCustomer($customer);

    	// for each passenger checked, add them to the list.
    	if($request->get("passenger")){
    		foreach ($request->get("passenger") as $passenger_id ) {
	    		$passenger = $em->getRepository('JosuTestWebBundle:Passenger')->find($passenger_id);
	    		if($passenger){
	    			$trip->addPassenger($passenger);
	    		}
	    	}
    	}
    	

    	// save the trip and their passenger.
		$em->persist($trip);
       	$em->flush();
    	
    	// If all correct, return to details page.
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
    		return $this->redirect($this->generateUrl('login_route'));
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
