<?php

namespace Josu\Test\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class TripController extends Controller
{
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
}
