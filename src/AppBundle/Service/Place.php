<?php

namespace AppBundle\Service;

use AppBundle\Entity\Place as PlaceEntity; // entité Place
use Symfony\Component\HttpFoundation\Response;
use \Doctrine\Common\Persistence\ManagerRegistry; // Doctrine

class Place {
	private $doctrine;
   
	public function __construct( ManagerRegistry $doctrine) {
		$this->doctrine = $doctrine;
	}

	/**
	 * Retourne la liste des places
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function getList() {
	    try {
		$repo = $this->doctrine->getRepository(PlaceEntity::class);
		$data = $repo->findAll();
		if(count($data) > 0){
		    return $data;
		} else {
		    throw new \Exception("Aucune place.", Response::HTTP_NOT_FOUND);
		}
	    } catch (\Exception $ex) {
		throw new \Exception("Erreur de récupération de la liste des places : ". 
		    $ex->getMessage(), $ex->getCode());
	    }
	}

	/**
	 * Retourne une place en fonction de l'identifiant fourni
	 *
	 * @param $id
	 * @return object
	 * @throws \Exception
	 */
	public function getOne($id) {
	    try {
		$repo = $this->doctrine->getRepository(PlaceEntity::class);
		$data = $repo->find($id);
		if($data != null){
		    return $data;
		} else {
		    throw new \Exception("Place introuvable", Response::HTTP_NOT_FOUND);
		}
	    } catch (\Exception $ex) {
		throw new \Exception("Erreur de récupération de la place : ". 
		    $ex->getMessage(), $ex->getCode());
	    }
	}

	/**
	 * Ajoute une place
	 *
	 * @param PlaceEntity $place
	 * @throws \Exception
	 */
	public function add(PlaceEntity $place) {
	    try {
		$em = $this->doctrine->getManager();
		$em->persist($place);
		$em->flush();
	    } catch (\Exception $ex) {
		throw new \Exception("Erreur d'enregistrement de la place : ". 
		    $ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
	    }
	}

	/**
	 * Supprime une place en fonction de l'identifiant fourni
	 *
	 * @param $id
	 * @throws \Exception
	 */
	public function remove($id) {
	    try {
		$em = $this->doctrine->getManager();
		$repo = $em->getRepository('AppBundle:Place');
		$place = $repo->find($id);
		if($place != null){
		    $em->remove($place);
		    $em->flush();
		} else {
		    throw new \Exception("Entité à supprimer introuvable", 
		        Response::HTTP_NOT_FOUND);
		}
	    } catch (\Exception $ex) {
		throw new \Exception("Erreur de suppression de la place : ". 
		    $ex->getMessage(), $ex->getCode());
	    }
	}

	/**
	 * Met à jour une place
	 *
	 * @param PlaceEntity $place
	 * @throws \Exception
	 */
	public function update(PlaceEntity $place) {
	    try {
		$em = $this->doctrine->getManager();
		$em->merge($place);
		$em->flush();
	    } catch (\Exception $ex) {
		throw new \Exception("Erreur de mise à jour de la place : ". 
		    $ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
	    }
	}
}
