<?php

namespace Josu\Test\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PassengerController extends Controller
{
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

}
