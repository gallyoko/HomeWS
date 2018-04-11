<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\Response;

class Recalbox {

    private $sshHost = '192.168.1.20';
    private $sshPort = '22';
    private $sshUser = 'root';
    private $sshPassword = 'recalboxroot';
    private $emulatorBin = ['retroarch', 'mupen64plus', 'PPSSPPSDL', 'reicast.elf'];

    private function sshConnectRecalbox() {
        try {
            $sshCon  = ssh2_connect($this->sshHost, $this->sshPort);
            if( !$sshCon ) {
                throw new \Exception("Erreur réseau", 500);
            } else {
                if( !ssh2_auth_password( $sshCon, $this->sshUser, $this->sshPassword ) ) {
                    throw new \Exception("Erreur de login / mot de passe sur recalbox", 500);
                } else {
                    return $sshCon;
                }
            }
        } catch (\Exception $ex) {
            throw new \Exception("Impossible de se connecter à recalbox : ".$ex->getMessage(), $ex->getCode());
        }
    }

    public function stopGame() {
        try {
            $sshCon = $this->sshConnectRecalbox();
            $searchType = $this->emulatorBin;
            foreach ($searchType as $search) {
                $cmd = 'ps -ef | grep '.$search.' | grep -v grep';
                $streamGrep = ssh2_exec($sshCon, $cmd);
                if( !$streamGrep ) {
                    throw new \Exception("Impossible de lancer la commande !", 500);
                } else {
                    $pid = null;
                    stream_set_blocking($streamGrep, true);
                    $streamLines = stream_get_contents($streamGrep);
                    $lines = explode("\n", $streamLines);
                    foreach ($lines as $line){
                        $field = explode(" ", trim($line));
                        $pid = $field[0];
                        if ($pid!=''){
                            $streamKill = ssh2_exec( $sshCon, 'kill '.$pid );
                            if( !$streamKill ) {
                                throw new \Exception("Impossible de tuer le processus".$pid." !", 500);
                            }
                        }
                    }
                }
            }
            return ['success' => true];
        } catch (\Exception $ex) {
            throw new \Exception("Erreur lors de l'arret du jeu : ".$ex->getMessage(), $ex->getCode());
        }

    }


}
