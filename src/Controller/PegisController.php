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

class PegisController extends AbstractController{
       /**
     * fonction pour afficher la liste des genres
     * @param $db
     * @param $idPegisModif  positionné si demande de modification
     * @param $idPegiseNotif  positionné si mise à jour dans la vue
     * @param $notification  pour notifier la mise à jour dans la vue
     */
    private function afficherPegis(PdoJeux $db, int $idPegiModif, int $idPegiNotif, string $notification ) {
        $tbPegis  = $db->getLesPegi();
        return $this->render('lesPegis.html.twig', array(
            'menuActif' => 'Jeux',
            'idPegiModif' => $idPegiModif,
            'idPegiNotif' => $idPegiNotif,
            'notification' => $notification,
            'tbPegis' => $tbPegis
        ));
    }

    /**
    * @Route("/pegi", name="pegis_afficher")
    */
    public function index(SessionInterface $session)
    {
        if ($session->has('idUtilisateur')) {
            $db = PdoJeux::getPdoJeux();
            return $this->afficherPegis($db, -1, -1, 'rien');
        } else {
            return $this->render('connexion.html.twig');
        }
    }

     /**
     * @Route("/pegi/ajouter", name="pegis_ajouter")
     */
    public function ajouterPegi(SessionInterface $session, Request $request)
    {
        $db = PdoJeux::getPdoJeux();
        if (!empty($request->request->get('txtLibPegi'))) {
            $idPegiNotif = $db->ajouterPegi($request->request->get('txtAgePegi'), $request->request->get('txtLibPegi'));
            $notification = 'Ajouté';
        }
        return $this->afficherPegis($db, -1,  $idPegiNotif, $notification);
    }


      /**
     * @Route("/pegi/demandermodifier", name="pegis_demandermodifier")
     */
    public function modifierPegi(SessionInterface $session, Request $request)
    {
        $db = PdoJeux::getPdoJeux();
        return $this->afficherPegis($db, intval($request->request->get('unIdPegi')),  -1, 'modif');
    }


    /**
     * @Route("/pegis/validermodifier", name="pegis_validermodifier")
     */
    public function validerModifier(SessionInterface $session, Request $request)
    {
        $db = PdoJeux::getPdoJeux();
        $db->modifierPegi($request->request->get('unIdPegi'), $request->request->get('txtAgePegi'), $request->request->get('txtLibPegi'));
        return $this->afficherPegis($db, -1,  $request->request->get('unIdPegi'), 'Modifié');
    }


     /**
     * @Route("/pegi/supprimerPegi", name="pegis_supprimer")
     */
    public function supprimerPegi(SessionInterface $session, Request $request)
    {
        $db = PdoJeux::getPdoJeux();
        $db->supprimerPegi($request->request->get('unIdPegi'));
        $this->addFlash(
            'success', 'Le pegi a été supprimé'
        );

        return $this->afficherPegis($db, -1,   -1, "rien");
    }
}