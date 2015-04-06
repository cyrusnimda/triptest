<?php

namespace Josu\Test\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Josu\Test\WebBundle\Entity\Passenger;
use Josu\Test\WebBundle\Entity\Customer;
use Symfony\Component\HttpFoundation\Session\Session;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="_demo")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/details", name="_details")
     * @Template()
     */
    public function detailsAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	// Check is the user is logged in the system
    	$session = new Session();

    	$customer = $em->getRepository('JosuTestWebBundle:Customer')->find( $session->get('loginUser') );
    	if(!$customer){
    		return $this->redirect($this->generateUrl('login_route'));
    	}

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

        // if the form is sent, save the record
	    if ($form->isValid()) {
	       	
	       	$em->persist($form->getData());
       		$em->flush();
	        return $this->redirectToRoute('_details');
	    }

	    //show all passengers
	    $passengers = $em->getRepository('JosuTestWebBundle:Passenger')->findAll();

        return array('customer' => $customer, 'form' => $form->createView(), 'passengers' => $passengers);
    }

    /**
     * @Route("/passenger/delete/{passenger_id}", name="delete_passenger", requirements={"passenger_id" = "\d+"}, defaults={"passenger_id" = 0})
     */
    public function adminBorrarVentasAction($passenger_id)
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

    private function loginSuccess($user){
    	$em = $this->getDoctrine()->getManager();
    	$userLogin = $em->getRepository('JosuTestWebBundle:Customer')->findOneBy( array('name'=>$user->getName(), 'password'=>$user->getPassword() ) );
    	return $userLogin;
    }

}
