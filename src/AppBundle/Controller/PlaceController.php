<?php

namespace AppBundle\Controller;

# les 2 lignes suivantes sont propres à symfony pour toute création d’un controller (ne jamais supprimer)
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
# les 2 lignes suivantes sont propres à FOSRestBundle
# la ligne ci-dessous permet via des annotations d’indiquer à symfony la route ainsi que la méthode http associées
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;

use AppBundle\Service\Place as PlaceService;
use AppBundle\Entity\Place as PlaceEntity;

class PlaceController extends Controller
{
	/**
	 * @Rest\View()
	 * @Rest\Get("/places")
	 *
	 */
	public function getAllAction(PlaceService $placeService){
	    try {
		return $placeService->getList();
	    } catch (\Exception $ex) {
		return View::create(['message' => $ex->getMessage()], 
		    $ex->getCode());
	    }
	}

	/**
	 * @Rest\View()
	 * @Rest\Get("/place/{id}")
	 *
	 */
	public function getOneAction($id, PlaceService $placeService){
	    try {
		return $placeService->getOne($id);
	    } catch (\Exception $ex) {
		return View::create(['message' => $ex->getMessage()], 
		    $ex->getCode());
	    }
	}

	/**
	 * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
	 * @Rest\Delete("/place/{id}")
	 */
	public function removeAction($id, PlaceService $placeService) {
	    try {
		$placeService->remove($id);
	    } catch (\Exception $ex) {
		return View::create(['message' => $ex->getMessage()], 
		    $ex->getCode());
	    }
	}

	/**
	 * @Rest\View(statusCode=Response::HTTP_CREATED)
	 * @Rest\Post("/place")
	 */
	public function addAction(Request $request, PlaceService $placeService) {
	    try {
		$json = json_decode($request->getContent());
		$place = new PlaceEntity();
		$place->setNom($json->nom);
		$placeService->add($place);
	    } catch (\Exception $ex) {
		return View::create(['message' => $ex->getMessage()],
		    $ex->getCode());
	    }
	}

	/**
	 * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
	 * @Rest\Put("/place")
	 */
	public function updateAction(Request $request, 
		                     PlaceService $placeService) {
	    try {
		$json = json_decode($request->getContent());
		$place = new PlaceEntity();
		$place->setId($json->id);
		$place->setNom($json->nom);
		$placeService->update($place);
	    } catch (\Exception $ex) {
		return View::create(['message' => $ex->getMessage()], 
		    $ex->getCode());
	    }
	}
}
