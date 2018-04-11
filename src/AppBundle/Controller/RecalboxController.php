<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;

use AppBundle\Service\Recalbox as RecalboxService;

class RecalboxController extends Controller
{
	/**
	 * @Rest\View()
	 * @Rest\Get("/api/recalbox/game/stop")
	 *
	 */
	public function stopGameAction(RecalboxService $recalboxService){
	    try {
		    return $recalboxService->stopGame();
	    } catch (\Exception $ex) {
		return View::create(['message' => $ex->getMessage()], 
		    $ex->getCode());
	    }
	}

}
