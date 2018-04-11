<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;

use AppBundle\Service\Light as LightService;

class LightController extends Controller
{
	/**
	 * @Rest\View()
	 * @Rest\Get("/api/lights")
	 *
	 */
	public function getAllAction(LightService $lightService){
	    try {
		    return $lightService->getList();
	    } catch (\Exception $ex) {
		return View::create(['message' => $ex->getMessage()], 
		    $ex->getCode());
	    }
	}

	/**
	 * @Rest\View()
	 * @Rest\Get("/api/light/put/{action}/{id}")
	 *
	 */
	public function PutAction($action, $id, LightService $lightService){
	    try {
		    return $lightService->put($action, $id);
	    } catch (\Exception $ex) {
		return View::create(['message' => $ex->getMessage()], 
		    $ex->getCode());
	    }
	}

}
