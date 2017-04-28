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
    * @Route("/")
    */
    public function defaultAction(Request $request)
    {
        return $this->redirectToRoute('_details');
    }
    
    /**
    * @Route("/login", name="login")
    * @Template()
    */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return [
            'last_username' => $lastUsername,
            'error'         => $error,
        ];

    }

}
