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
    	$user = new Customer();
    	$error = null;

        $loginForm = $this->createForm(new LoginType(), $user);
        $loginForm->handleRequest($request);

        $loginForm->handleRequest($request);

        // if the form is sent, save the record
        if ($loginForm->isValid()) {
            $user = $loginForm->getData();
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

	    return array('error'=> $error, 'form' => $loginForm->createView());
    }
}
