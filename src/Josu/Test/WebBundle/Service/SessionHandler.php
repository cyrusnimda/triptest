<?php

namespace Josu\Test\WebBundle\Service;

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;

class SessionHandler
{
    protected $session;
    protected $em;

    public function __construct(EntityManager $em, Session $session)
    {
        $this->session = $session;
        $this->em = $em;
    }

    /*
     * Get the customer from the session, if there is none,
     * redirect to the login page.
     *
     * return Customer object.
     */
    public function loadSessionUser(){
    	// if there is no user in session, redirect to login.
    	$sessionUser = $this->session->get('loggedCustomerId');
    	if(!$sessionUser){
    		return false;
    	}

    	// Load the session customer
    	$customer = $this->em->getRepository('JosuTestWebBundle:Customer')->find( $sessionUser );
    	if(!$customer){
            throw new NotFoundHttpException("Customer don't found");
    	}
    	return $customer;
    }


}
