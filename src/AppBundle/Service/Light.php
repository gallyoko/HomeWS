<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\Response;

class Light {

	/**
	 * Retourne la liste des lumières
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function getList() {
	    try {
            $output = [];
            exec('tdtool --list-devices', $output);
            $listeDevice = [];
            foreach ($output as $line) {
                $lineDevice = [];
                $lineInfo = explode("\t", $line);
                foreach ($lineInfo as $info) {
                    $infoElement = explode("=", $info);
                    $lineDevice[$infoElement[0]] = strtolower($infoElement[1]);
                }
                $listeDevice[] = $lineDevice;
            }
            return $listeDevice;
	    } catch (\Exception $ex) {
            throw new \Exception("Erreur de récupération de la liste des lumières : ".
                $ex->getMessage(), $ex->getCode());
	    }
	}

    public function put($action, $id) {
        try {
            $output = [];
            exec('tdtool -'.substr($action, -1, 1).' '.$id, $output);
            $statut = false;
            if (count($output) > 0) {
                if (substr($output[0], - 7, 7) == 'Success') {
                    $statut = true;
                }
            }
            return ['success' => $statut];
        } catch (\Exception $ex) {
            throw new \Exception("Erreur de récupération de la liste des lumières : ".
                $ex->getMessage(), $ex->getCode());
        }
    }
}
