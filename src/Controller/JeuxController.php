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

class JeuxController extends AbstractController{
       /**
     * fonction pour afficher la liste des genres
     * @param $db
     * @param $idJeuxModif  positionné si demande de modification
     * @param $idJeuxeNotif  positionné si mise à jour dans la vue
     * @param $notification  pour notifier la mise à jour dans la vue
     */
    private function afficherJeux(PdoJeux $db, string $idJeuxModif, string $idJeuxNotif, string $notification ) {
        $tbJeux  = $db->getLesJeux();
        $tbPlateformes = $db->getLesPlateformes();
        $tbMarques = $db->getLesMarques();
        $tbPegis = $db->getLesPegi2();
        $tbGenres = $db->getLesGenres();
        return $this->render('lesJeux.html.twig', array(
            'menuActif' => 'Jeux',
            'idJeuxModif' => $idJeuxModif,
            'idJeuxNotif' => $idJeuxNotif,
            'notification' => $notification,
            'tbJeux' => $tbJeux,
            'tbPlateforme' => $tbPlateformes,
            'tbMarque' =>$tbMarques,
            'tbPegi' => $tbPegis,
            'tbGenre' => $tbGenres
        ));
    }

    /**
    * @Route("/jeux", name="jeux_afficher")
    */
    public function index(SessionInterface $session)
    {
        if ($session->has('idUtilisateur')) {
            $db = PdoJeux::getPdoJeux();
            return $this->afficherJeux($db, -1, -1, 'rien');
        } else {
            return $this->render('connexion.html.twig');
        }
    }

     /**
     * @Route("/jeux/ajouter", name="jeux_ajouter")
     */
    public function ajouterJeux(SessionInterface $session, Request $request)
    {
        $db = PdoJeux::getPdoJeux();
        if (!empty($request->request->get('txtRefJeu'))) {
            $idJeuxNotif = $db->ajouterJeux($request->request->get('txtRefJeu'), $request->request->get('txtNomJeu'), $request->request->get('dateParutionJeu'), strval($request->request->get('txtPrixJeu')), intval($request->request->get('strIdGenre')), intval($request->request->get('strIdPlateforme')), intval($request->request->get('strIdMarque')), intval($request->request->get('strIdPegi')));
            $notification = 'Ajouté';
        }
        return $this->afficherJeux($db, -1,  $idJeuxNotif, $notification);
    }


      /**
     * @Route("/jeux/demandermodifier", name="jeux_demandermodifier")
     */
    public function modifierJeux(SessionInterface $session, Request $request)
    {
        $db = PdoJeux::getPdoJeux();
        return $this->afficherJeux($db, $request->request->get('txtRefJeu'),  -1, 'modif');
    }


    /**
     * @Route("/jeux/validermodifier", name="jeux_validermodifier")
     */
    public function validerModifier(SessionInterface $session, Request $request)
    {
        $db = PdoJeux::getPdoJeux();
        $db->modifierJeux($request->request->get('txtRefJeu'), $request->request->get('txtNomJeu'), $request->request->get('dateParutionJeu'), strval($request->request->get('txtPrixJeu')), intval($request->request->get('strIdGenre')), intval($request->request->get('strIdPlateforme')), intval($request->request->get('strIdMarque')), intval($request->request->get('strIdPegi')));
        return $this->afficherJeux($db, -1,  $request->request->get('txtRefJeu'), 'Modifié');
    }


     /**
     * @Route("/jeux/supprimerJeu", name="jeux_supprimer")
     */
    public function supprimerJeux(SessionInterface $session, Request $request)
    {
        $db = PdoJeux::getPdoJeux();
        $db->supprimerJeux($request->request->get('txtIdJeu'));
        $this->addFlash(
            'success', 'Le jeu a été supprimé'
        );

        return $this->afficherJeux($db, -1,   -1, "rien");
    }
}