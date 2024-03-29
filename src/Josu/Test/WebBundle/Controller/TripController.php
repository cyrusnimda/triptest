<?php

namespace Josu\Test\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Josu\Test\WebBundle\Service\SessionHandler;

use Josu\Test\WebBundle\Entity\Trip;

class TripController extends Controller
{
    /**
     * @Route("/trip/delete/{trip}", name="delete_trip")
     */
    public function deleteTripAction(Trip $trip)
    {
        // Check is the user is logged in the system
        $sessionHandler = $this->get("app.session_handle");
        $customer = $sessionHandler->loadSessionUser();
        if(!$customer){
            return $this->redirectToRoute('login_route');
        }
        
        // check if it is a valid id, throw exception in other case.
        if(!$trip){
            throw new Exception("Don't be evil");
        }

        //delete the trip and redirect to details page
        $em = $this->getDoctrine()->getManager();
        $em->remove($trip);
        $em->flush();

        return $this->redirect($this->generateUrl('_details'));
    }
}
