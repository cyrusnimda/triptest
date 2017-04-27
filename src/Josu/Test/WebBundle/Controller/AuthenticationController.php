<?php

namespace Josu\Test\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Josu\Test\WebBundle\Entity\Customer;
use Josu\Test\WebBundle\Form\LoginType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;

class AuthenticationController extends Controller
{
    /**
    * @Route("/login", name="login_route")
    * @Template()
    */
    public function loginAction(Request $request)
    {
    	//Create the customer form
    	$customer = new Customer();
    	$error = null;

        $loginForm = $this->createForm(new LoginType(), $customer);
        $loginForm->handleRequest($request);

        // if the form is sent, save the record
        if ($loginForm->isValid()) {
            $customer = $loginForm->getData();
            $customer = $this->loginSuccess($customer);
            if($customer != null){
                $session = new Session();

                // set and get session attributes
                $session->set('loggedCustomerId', $customer->getId() );
                return $this->redirectToRoute('_details');
            } else{
                $error= "User or password incorrect.";
            }
        }

	    return array('error'=> $error, 'form' => $loginForm->createView());
    }

    /*
	 * Check in the database is the customer exists.
	 * @param Customer $user -> the user to check
	 *
	 * return Customer object or null.
     */
    private function loginSuccess($user){
    	$em = $this->getDoctrine()->getManager();
    	$userLogin = $em->getRepository('JosuTestWebBundle:Customer')->findOneBy( array('email'=>$user->getEmail(), 'password'=>$user->getPassword() ) );
    	return $userLogin;
    }
}
