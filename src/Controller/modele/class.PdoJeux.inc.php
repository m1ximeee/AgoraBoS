<?php

/**
 *  AGORA
 * 	©  Logma, 2019
 * @package default
 * @author MD
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 * 
 * Classe d'accès aux données. 
 * Utilise les services de la classe PDO
 * pour l'application AGORA
 * Les attributs sont tous statiques,
 * $monPdo de type PDO 
 * $monPdoJeux qui contiendra l'unique instance de la classe
 */
class PdoJeux
{

    private static $monPdo;
    private static $monPdoJeux = null;

    /**
     * Constructeur privé, crée l'instance de PDO qui sera sollicitée
     * pour toutes les méthodes de la classe
     */
    private function __construct()
    {
        // A) >>>>>>>>>>>>>>>   Connexion au serveur et à la base
        try {
            // encodage
            $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'');
            // Crée une instance (un objet) PDO qui représente une connexion à la base
            PdoJeux::$monPdo = new PDO($_ENV['AGORA_DSN'], $_ENV['AGORA_DB_USER'], $_ENV['AGORA_DB_PWD'], $options);
            // configure l'attribut ATTR_ERRMODE pour définir le mode de rapport d'erreurs 
            // PDO::ERRMODE_EXCEPTION: émet une exception 
            PdoJeux::$monPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // configure l'attribut ATTR_DEFAULT_FETCH_MODE pour définir le mode de récupération par défaut 
            // PDO::FETCH_OBJ: retourne un objet anonyme avec les noms de propriétés 
            //     qui correspondent aux noms des colonnes retournés dans le jeu de résultats
            PdoJeux::$monPdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        } catch (PDOException $e) {    // $e est un objet de la classe PDOException, il expose la description du problème
            die('<section id="main-content"><section class="wrapper"><div class = "erreur">Erreur de connexion à la base de données !<p>'
                . $e->getmessage() . '</p></div></section></section>');
        }
    }

    /**
     * Destructeur, supprime l'instance de PDO  
     */
    public function _destruct()
    {
        PdoJeux::$monPdo = null;
    }

    /**
     * Fonction statique qui crée l'unique instance de la classe
     * Appel : $instancePdoJeux = PdoJeux::getPdoJeux();
     * 
     * @return l'unique objet de la classe PdoJeux
     */
    public static function getPdoJeux()
    {
        if (PdoJeux::$monPdoJeux == null) {
            PdoJeux::$monPdoJeux = new PdoJeux();
        }
        return PdoJeux::$monPdoJeux;
    }

    //==============================================================================
    //
    //	METHODES POUR LA GESTION DES GENRES
    //
    //==============================================================================

    /**
     * Retourne tous les genres sous forme d'un tableau d'objets 
     * 
     * @return array le tableau d'objets  (Genre)
     */
    public function getLesGenres(): array
    {
        $requete =  'SELECT idGenre as identifiant, libGenre as libelle 
						FROM genre 
						ORDER BY libGenre';
        try {
            $resultat = PdoJeux::$monPdo->query($requete);
            $tbGenres  = $resultat->fetchAll();
            return $tbGenres;
        } catch (PDOException $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }


    /**
     * Ajoute un nouveau genre avec le libellé donné en paramètre
     * 
     * @param string $libGenre : le libelle du genre à ajouter
     * @param int $idSpecialiste : le spécialiste du genre à ajouter
     * @return l'identifiant du genre crée
     */
    public function ajouterGenre(string $libGenre, int $idSpecialiste)
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("INSERT INTO genre "
                . "(idGenre, libGenre, idSpecialiste) "
                . "VALUES (0, :unLibGenre, :unIdSpecialiste) ");
            $requete_prepare->bindParam(':unLibGenre', $libGenre, PDO::PARAM_STR);
            $requete_prepare->bindParam(':unIdSpecialiste', $idSpecialiste, PDO::PARAM_INT);
            $requete_prepare->execute();
            // récupérer l'identifiant crée
            return PdoJeux::$monPdo->lastInsertId();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Modifie le libellé du genre donné en paramètre
     * @param int $idGenre : l'identifiant du genre à modifier  
     * @param string $libGenre : le libellé modifié
     * @param int $idSpecialiste : le spécialiste du genre à ajouter
     */
    public function modifierGenre(int $idGenre, string $libGenre, int $idSpecialiste)
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("UPDATE genre "
                . "SET libGenre = :unLibGenre, idSpecialiste = :unIdSpecialiste "
                . "WHERE genre.idGenre = :unIdGenre");
            $requete_prepare->bindParam(':unIdGenre', $idGenre, PDO::PARAM_INT);
            $requete_prepare->bindParam(':unLibGenre', $libGenre, PDO::PARAM_STR);
            $requete_prepare->bindParam(':unIdSpecialiste', $idSpecialiste, PDO::PARAM_INT);
            $requete_prepare->execute();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }


    /**
     * Supprime le genre donné en paramètre
     * 
     * @param int $idGenre :l'identifiant du genre à supprimer 
     */
    public function supprimerGenre(int $idGenre): void
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("DELETE FROM genre "
                . "WHERE genre.idGenre = :unIdGenre");
            $requete_prepare->bindParam(':unIdGenre', $idGenre, PDO::PARAM_INT);
            $requete_prepare->execute();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }


    //==============================================================================
    //
    //	METHODES POUR LA GESTION DES PLATEFORMES
    //
    //==============================================================================

    /**
     * Retourne tous les genres sous forme d'un tableau d'objets 
     * 
     * @return array le tableau d'objets  (Genre)
     */
    public function getLesPlateformes(): array
    {
        $requete =  'SELECT idPlateforme as identifiant, libPlateforme as libelle 
                      FROM plateforme 
                      ORDER BY libPlateforme';
        try {
            $resultat = PdoJeux::$monPdo->query($requete);
            $tbPlateformes  = $resultat->fetchAll();
            return $tbPlateformes;
        } catch (PDOException $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }


    /**
     * Ajoute un nouveau genre avec le libellé donné en paramètre
     * 
     * @param string $libGenre : le libelle du genre à ajouter
     * @return int l'identifiant du genre crée
     */
    public function ajouterPlateformes(string $libPlateformes): int
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("INSERT INTO plateforme "
                . "(idPlateforme, libPlateforme) "
                . "VALUES (0, :unLibtbPlateformes) ");
            $requete_prepare->bindParam(':unLibPlateformes', $libPlateformes, PDO::PARAM_STR);
            $requete_prepare->execute();
            // récupérer l'identifiant crée
            return PdoJeux::$monPdo->lastInsertId();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }


    /**
     * Modifie le libellé du genre donné en paramètre
     * 
     * @param int $idGenre : l'identifiant du genre à modifier  
     * @param string $libGenre : le libellé modifié
     */
    public function modifierPlateformes(int $idPlateformes, string $libPlateformes): void
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("UPDATE plateforme "
                . "SET libPlateforme = :unLibPlateforme "
                . "WHERE plateforme.idPlateforme = :unIdPlateforme");
            $requete_prepare->bindParam(':unIdPlateformes', $idPlateformes, PDO::PARAM_INT);
            $requete_prepare->bindParam(':unLibPlateformes', $libPlateformes, PDO::PARAM_STR);
            $requete_prepare->execute();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }


    /**
     * Supprime le genre donné en paramètre
     * 
     * @param int $idGenre :l'identifiant du genre à supprimer 
     */
    public function supprimerPlateforme(int $idPlateformes): void
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("DELETE FROM plateforme "
                . "WHERE palteforme.idPlateforme = :unIdPlateformes");
            $requete_prepare->bindParam(':unIdPlateformes', $idPlateformes, PDO::PARAM_INT);
            $requete_prepare->execute();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }

    //==============================================================================
    //
    //	METHODES POUR LA GESTION DES MARQUES
    //
    //==============================================================================

    /**
     * Retourne tous les marques sous forme d'un tableau d'objets 
     * 
     * @return array le tableau d'objets  (Marque)
     */
    public function getLesMarques(): array
    {
        $requete =  'SELECT idMarque as identifiant, nomMarque as libelle 
                      FROM marque 
                      ORDER BY nomMarque';
        try {
            $resultat = PdoJeux::$monPdo->query($requete);
            $tbMarques  = $resultat->fetchAll();
            return $tbMarques;
        } catch (PDOException $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }


    /**
     * Ajoute une nouvelle marque avec le libellé donné en paramètre
     * 
     * @param string $libMarque : le libelle de la marque à ajouter
     * @return int l'identifiant de la marque crée
     */
    public function ajouterMarque(string $libMarque): int
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("INSERT INTO marque (idMarque, nomMarque) VALUES (0, :unLibMarque) ");
            $requete_prepare->bindParam(':unLibMarque', $libMarque, PDO::PARAM_STR);
            $requete_prepare->execute();
            // récupérer l'identifiant crée
            return PdoJeux::$monPdo->lastInsertId();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }


    /**
     * Modifie le libellé de la marque donné en paramètre
     * 
     * @param int $idMarque : l'identifiant de la marque à modifier  
     * @param string $libMarque : le nom de la marque à modifier 
     */
    public function modifierMarque(int $idMarque, string $libMarque): void
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("UPDATE marque SET nomMarque = :unLibMarque WHERE idMarque = :unIdMarque");
            $requete_prepare->bindParam(':unIdMarque', $idMarque, PDO::PARAM_INT);
            $requete_prepare->bindParam(':unLibMarque', $libMarque, PDO::PARAM_STR);
            $requete_prepare->execute();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }


    /**
     * Supprime la marque donné en paramètre
     * 
     * @param int $idGenre :l'identifiant du genre à supprimer 
     */
    public function supprimerMarque(int $idMarque): void
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("DELETE FROM marque "
                . "WHERE marque.idMarque = :unIdMarque");
            $requete_prepare->bindParam(':unIdMarque', $idMarque, PDO::PARAM_INT);
            $requete_prepare->execute();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }


    //==============================================================================
    //
    //	METHODES POUR LA GESTION DES PEGI
    //
    //==============================================================================

    /**
     * Retourne tous les genres sous forme d'un tableau d'objets 
     * 
     * @return array le tableau d'objets  (Genre)
     */
    public function getLesPegi(): array
    {
        $requete =  'SELECT idPegi as identifiant, ageLimite, descPegi as descriptionPegi 
                      FROM pegi 
                      ORDER BY ageLimite';
        try {
            $resultat = PdoJeux::$monPdo->query($requete);
            $tbPegi  = $resultat->fetchAll();
            return $tbPegi;
        } catch (PDOException $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }

    /**
     * Retourne tous les genres sous forme d'un tableau d'objets 
     * 
     * @return array le tableau d'objets  (Genre)
     */
    public function getLesPegi2(): array
    {
        $requete =  'SELECT idPegi as identifiant, ageLimite as libelle
                      FROM pegi 
                      ORDER BY ageLimite';
        try {
            $resultat = PdoJeux::$monPdo->query($requete);
            $tbPegi2  = $resultat->fetchAll();
            return $tbPegi2;
        } catch (PDOException $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }

    /**
     * Ajoute un nouveau genre avec le libellé donné en paramètre
     * 
     * @param string $libGenre : le libelle du genre à ajouter
     * @return int l'identifiant du genre crée
     */
    public function ajouterPegi(int $ageLimite, string $libPegi): int
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("INSERT INTO pegi (idPegi, ageLimite, descPegi) VALUES (0, :ageLimite, :descPegi)");
            $requete_prepare->bindParam(':ageLimite', $ageLimite, PDO::PARAM_STR);
            $requete_prepare->bindParam(':descPegi', $libPegi, PDO::PARAM_STR);
            $requete_prepare->execute();
            // récupérer l'identifiant crée
            return PdoJeux::$monPdo->lastInsertId();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }

    public function modifierPegi(int $idPegi, int $agePegi, string $descPegi): void
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("UPDATE pegi "
                . "SET ageLimite = :unAgeLimite, descPegi = :uneDescPegi "
                . "WHERE pegi.idPegi = :unIdPegi");
            $requete_prepare->bindParam(':unIdPegi', $idPegi, PDO::PARAM_INT);
            $requete_prepare->bindParam(':unAgeLimite', $agePegi, PDO::PARAM_INT);
            $requete_prepare->bindParam(':uneDescPegi', $descPegi, PDO::PARAM_STR);
            $requete_prepare->execute();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }


    /**
     * Supprime le genre donné en paramètre
     * 
     * @param int $idGenre :l'identifiant du genre à supprimer 
     */
    public function supprimerPegi(String $idPegi): void
    {
        $intIdPegi = intval($idPegi);
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("DELETE FROM pegi "
                . "WHERE pegi.idPegi = :unIdPegi");
            $requete_prepare->bindParam(':unIdPegi', $intIdPegi, PDO::PARAM_INT);
            $requete_prepare->execute();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }

    //==============================================================================
    //
    //	METHODES POUR LA GESTION DES JEUX
    //
    //==============================================================================

    /**
     * Retourne tous les jeux sous forme d'un tableau d'objets 
     * 
     * @return array le tableau d'objets  (Genre)
     */
    public function getLesJeux(): array
    {
        $requete =  'SELECT refJeu, nom as libelle, dateParution, prix, jeu_video.idGenre as idGenre, jeu_video.idPlateforme as idPlateforme, jeu_video.idPegi as idPegi, jeu_video.idMarque as idMarque, libGenre, libPlateforme, nomMarque, ageLimite
        FROM jeu_video 
        INNER JOIN plateforme ON jeu_video.idPlateforme = plateforme.idPlateforme 
        INNER JOIN pegi ON jeu_video.idPegi = pegi.idPegi 
        INNER JOIN genre ON jeu_video.idGenre = genre.idGenre 
        INNER JOIN marque ON jeu_video.idMarque = marque.idMarque
        ORDER BY nom';
        try {
            $resultat = PdoJeux::$monPdo->query($requete);
            $tbJeux  = $resultat->fetchAll();
            return $tbJeux;
        } catch (PDOException $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }

    public function ajouterJeux(string $uneRefJeu, string $unNom, string $dateParution, float $unPrix, int $unIdGenre, int $unIdPlateforme, int $unIdMarque, int $unIdPegi): int
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("INSERT INTO jeu_video (refJeu, nom, dateParution, prix, idGenre, idPlateforme, idMarque, idPegi) 
        VALUES (:refJeu, :nom, :dateParution, :prix, :idGenre, :idPlateforme, :idMarque, :idPegi)");
            $requete_prepare->bindParam(':refJeu', $uneRefJeu, PDO::PARAM_STR);
            $requete_prepare->bindParam(':nom', $unNom, PDO::PARAM_STR);
            $requete_prepare->bindParam(':dateParution', $dateParution);
            $requete_prepare->bindParam(':prix', $unPrix);
            $requete_prepare->bindParam(':idGenre', $unIdGenre, PDO::PARAM_INT);
            $requete_prepare->bindParam(':idPlateforme', $unIdPlateforme, PDO::PARAM_INT);
            $requete_prepare->bindParam(':idMarque', $unIdMarque, PDO::PARAM_INT);
            $requete_prepare->bindParam(':idPegi', $unIdPegi, PDO::PARAM_INT);

            $requete_prepare->execute();
            // récupérer l'identifiant crée
            return PdoJeux::$monPdo->lastInsertId();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }

    public function modifierJeux(string $uneRefJeu, string $unNom, string $dateParution, float $unPrix, int $unIdGenre, int $unIdPlateforme, int $unIdMarque, int $unIdPegi): void
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("UPDATE jeu_video "
                . "SET nom = :nom, dateParution = :dateParution, prix = :prix, idGenre = :idGenre, idPlateforme = :idPlateforme, idMarque = :idMarque, idPegi = :idPegi "
                . "WHERE jeu_video.refJeu = :refJeu");
            $requete_prepare->bindParam(':refJeu', $uneRefJeu, PDO::PARAM_STR);
            $requete_prepare->bindParam(':nom', $unNom, PDO::PARAM_STR);
            $requete_prepare->bindParam(':dateParution', $dateParution);
            $requete_prepare->bindParam(':prix', $unPrix);
            $requete_prepare->bindParam(':idGenre', $unIdGenre, PDO::PARAM_INT);
            $requete_prepare->bindParam(':idPlateforme', $unIdPlateforme, PDO::PARAM_INT);
            $requete_prepare->bindParam(':idMarque', $unIdMarque, PDO::PARAM_INT);
            $requete_prepare->bindParam(':idPegi', $unIdPegi, PDO::PARAM_INT);

            $requete_prepare->execute();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }

    /**
     * Supprime le genre donné en paramètre
     * 
     * @param int $idGenre :l'identifiant du genre à supprimer 
     */
    public function supprimerJeux(string $idJeux): void
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("DELETE FROM jeu_video WHERE refJeu = :uneRefJeu");
            $requete_prepare->bindParam(':uneRefJeu', $idJeux, PDO::PARAM_STR);
            $requete_prepare->execute();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }

    //==============================================================================
    //
    //  METHODES POUR LA GESTION DES MEMBRES
    //
    //==============================================================================
    /**
     * Retourne l'identifiant, le nom et le prénom de l'utilisateur correspondant au compte et mdp
     *       
     * @param string $compte  le compte de l'utilisateur    
     * @param string $mdp  le mot de passe de l'utilisateur    
     * @return @return ?object  l'objet ou null si ce membre n'existe pas
     */
    public function getUnMembre(string $loginMembre, string $mdpMembre): ?object
    {
        try {
            // préparer la requête
            $requete_prepare = PdoJeux::$monPdo->prepare(
                'SELECT idMembre, prenomMembre, nomMembre, mdpMembre, selMembre  
                 FROM membre 
                 WHERE loginMembre = :loginMembre'
            );
            // associer les valeurs aux paramètres
            $requete_prepare->bindParam(':loginMembre', $loginMembre, PDO::PARAM_STR);
            // exécuter la requête
            $requete_prepare->execute();
            // récupérer l'objet   
            if ($utilisateur = $requete_prepare->fetch()) {
                // vérifier le mot de passe
                // le mot de passe transmis par le formulaire est le hash du mot de passe saisi
                // le mot de passe enregistré dans la base doit correspondre au hash du (hash transmis concaténé au sel)
                $hashMDP = hash('SHA512', $mdpMembre . $utilisateur->selMembre);
                if ($hashMDP == $utilisateur->mdpMembre) {
                    return $utilisateur;
                }
            }
            return null;
        } catch (PDOException $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }


    /**
     * Retourne tous les genres sous forme d'un tableau d'objets
     *    avec également le nombre de jeux de ce genre
     *
     * @return le tableau d'objets  (Genre)
     */
    public function getLesGenresComplet()
    {
        $requete =  'SELECT G.idGenre as identifiant, G.libGenre as libelle, G.idSpecialiste AS idSpecialiste, CONCAT(M.prenomMembre, " ", M.nomMembre)  AS nomSpecialiste, 
         (SELECT COUNT(refJeu) FROM jeu_video AS J WHERE J.idGenre = G.idGenre) AS nbJeux 
      FROM genre AS G
      LEFT OUTER JOIN membre AS M ON G.idSpecialiste = M.idMembre
      ORDER BY G.libGenre';
        try {
            $resultat = PdoJeux::$monPdo->query($requete);
            $tbGenres  = $resultat->fetchAll();
            return $tbGenres;
        } catch (PDOException $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }

    /**
     * Retourne l'identifiant et le libellé de tous les MEMBRES sous forme d'un tableau d'objets 
     * 
     * @return le tableau d'objets  (Membre)
     */
    public function getLesMembres()
    {
        $requete = 'SELECT idMembre as identifiant, CONCAT(prenomMembre, " ", nomMembre)  AS libelle 
				  FROM membre 
				  ORDER BY nomMembre';
        try {
            $resultat = PdoJeux::$monPdo->query($requete);
            $tbGenres  = $resultat->fetchAll();
            return $tbGenres;
        } catch (PDOException $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }
}
