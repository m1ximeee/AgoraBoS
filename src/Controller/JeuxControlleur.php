<?php
// src/Controller/JeuxControleur.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
require_once 'modele/class.PdoJeux.inc.php';
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use PdoJeux;

class JeuxControleur extends AbstractController{
    /**
     * fonction pour afficher la liste des jeux
     * 
     * @param $db   base de donné
     */
    private function afficherJeux(PdoJeux $db, int $idJeuxModif, int $idJeuxNotif, string $notification){
        $tbJeux = $db->getLesJeux;
        $tbPlateforme = $db->getLesPlateformes;
        $tbPegi = $db->getLesPegi;
        $tbGenre = $db->getLesGenres;
        $tbMarque = $db->getLesMarques;

        return $this->render('lesJeux.html.twig', array(
            'menuActif' => 'Jeux',
            'tbJeux'=>$tbJeux,
            'tbPlateforme'=>$tbPlateforme,
            'tbPegi'=>$tbPegi,
            'tbGenre'=>$tbGenre,
            'idJeuxModif'=>$idJeuxModif,
            'idJeuxNotif'=>$idJeuxNotif,
            'notification'=>$notification,
        ));
    }

    
    /**
    * @Route("/jeux", name="jeux_afficher")
    */
    public function index(SessionInterface $session)
    {
        if ($session->has('idUtilisateur')) {
            $db = PdoJeux::getPdoJeux();
            return $this->afficherJeux($db, -1,-1,"rien");
        }else{
            return $this->render('connexion.html.twig');
        }
    }
    
}
