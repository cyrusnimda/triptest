<?php

namespace Josu\Test\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Josu\Test\WebBundle\Service\SessionHandler;

use Josu\Test\WebBundle\Entity\Passenger;

class PassengerController extends Controller
{
    /**
     * @Route("/passenger/delete/{passenger}", name="delete_passenger")
     */
    public function deletePassengerAction(Passenger $passenger)
    {
        // Check is the user is logged in the system
        $sessionHandler = $this->get("app.session_handle");
        $customer = $sessionHandler->loadSessionUser();
        if(!$customer){
            return $this->redirectToRoute('login_route');
        }

        // check the parameter.
        if(!$passenger){
            throw new Exception("Don't be evil");
        }

        //delete the passenger
        $em = $this->getDoctrine()->getManager();
        $em->remove($passenger);
        $em->flush();

        return $this->redirect($this->generateUrl('_details'));
    }

}
