<?php
// src/Controller/GenresController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
require_once 'modele/class.PdoJeux.inc.php';
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use PdoJeux;

class PlateformesController extends AbstractController
{
    /**
     * fonction pour afficher la liste des genres
     * @param $db
     * @param $idPlateformeModif  positionné si demande de modification
     * @param $idPlateformeNotif  positionné si mise à jour dans la vue
     * @param $notification  pour notifier la mise à jour dans la vue
     */
    private function afficherPlateforme(PdoJeux $db, int $idPlateformeModif, int $idPlateformeNotif, string $notification ) {
        $tbPlateforme  = $db->getLesPlateformes();
        return $this->render('lesPlateformes.html.twig', array(
            'menuActif' => 'Jeux',
            'tbPlateforme' => $tbPlateforme,
            'idPlateformeModif' => $idPlateformeModif,
            'idPlateformeNotif' => $idPlateformeModif,
            'notification' => $notification
        ));
    }

    /**
     * @Route("/plateformes", name="plateformes_afficher")
     */
    public function index(SessionInterface $session)
    {
        if ($session->has('idUtilisateur')) {
            $db = PdoJeux::getPdoJeux();
            return $this->afficherPlateforme($db, -1, -1, 'rien');
        } else {
            return $this->render('connexion.html.twig');
        }
    }

    /**
     * @Route("/plateformes/ajouter", name="plateformes_ajouter")
     */
    public function ajouter(SessionInterface $session, Request $request)
    {
        $db = PdoJeux::getPdoJeux();
        if (!empty($request->request->get('txtLibPlateforme'))) {
            $idGenreNotif = $db->ajouterPlateformes($request->request->get('txtLibPlateforme'));
            $notification = 'Ajouté';
        }
        return $this->afficherPlateforme($db, -1,  $idGenreNotif, $notification);
    }

    /**
     * @Route("/plateformes/demandermodifier", name="plateformes_demandermodifier")
     */
    public function demanderModifier(SessionInterface $session, Request $request)
    {
        $db = PdoJeux::getPdoJeux();
        return $this->afficherPlateforme($db, $request->request->get('txtIdPlateforme'),  -1, 'rien');
    }

    /**
     * @Route("/plateformes/validermodifier", name="plateformes_validermodifier")
     */
    public function validerModifier(SessionInterface $session, Request $request)
    {
        $db = PdoJeux::getPdoJeux();
        $db->modifierPlateformes(intval($request->request->get('txtIdPlateforme')), $request->request->get('txtLibPlateforme'));
        return $this->afficherPlateforme($db, -1,  $request->request->get('txtIdPlateforme'), 'Modifié');
    }

    /**
     * @Route("/plateformes/supprimer", name="plateformes_supprimer")
     */
    public function supprimer(SessionInterface $session, Request $request)
    {
        $db = PdoJeux::getPdoJeux();
        $db->supprimerPlateforme($request->request->get('txtIdPlateforme'));
        $this->addFlash(
            'success', 'La Plateforme a été supprimé'
        );

        return $this->afficherPlateforme($db, -1,  -1, 'rien');
    }
}
