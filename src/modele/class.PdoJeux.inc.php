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
            PdoJeux::$monPdo = new PDO($_ENV['AGORA_DSN'],$_ENV['AGORA_DB_USER'],$_ENV['AGORA_DB_PWD'], $options);
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

    //region Genres
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
     * @return int l'identifiant du genre crée
     */
    public function ajouterGenre(string $libGenre): int
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("INSERT INTO genre "
                . "(idGenre, libGenre) "
                . "VALUES (0, :unLibGenre) ");
            $requete_prepare->bindParam(':unLibGenre', $libGenre, PDO::PARAM_STR);
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
    public function modifierGenre(int $idGenre, string $libGenre): void
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("UPDATE genre "
                . "SET libGenre = :unLibGenre "
                . "WHERE genre.idGenre = :unIdGenre");
            $requete_prepare->bindParam(':unIdGenre', $idGenre, PDO::PARAM_INT);
            $requete_prepare->bindParam(':unLibGenre', $libGenre, PDO::PARAM_STR);
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
    //endregion

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
    public function getUnMembre(string $loginMembre, string $mdpMembre): ?object {
        try {   
            // préparer la requête
            $requete_prepare = PdoJeux::$monPdo->prepare(
                'SELECT idMembre, prenomMembre, nomMembre, mdpMembre, selMembre  
                 FROM membre 
                 WHERE loginMembre = :loginMembre ');
            $requete_prepare->bindParam(':loginMembre', $loginMembre, PDO::PARAM_STR);
            $requete_prepare->execute();
            if ($utilisateur = $requete_prepare->fetch()) {
                $mdp=hash("SHA512", $mdpMembre.$utilisateur->selMembre);
                if ($utilisateur->mdpMembre==$mdp) {
                    return $utilisateur;
                }
                // vérifier le mot de passe
                // le mot de passe transmis par le formulaire est le hash du mot de passe saisi
                // le mot de passe enregistré dans la base doit correspondre au hash du (hash transmis concaténé au sel)
            }
            return null;
        }
        catch (PDOException $e) {  
            die('<div class = "erreur">Erreur dans la requête !<p>'
                .$e->getmessage().'</p></div>');
        }
    }


    //region Marque
    //==============================================================================
    //
    //	METHODES POUR LA GESTION DES MARQUES
    //
    //==============================================================================

    /**
     * Retourne tous les genres sous forme d'un tableau d'objets 
     * 
     * @return array le tableau d'objets  (Genre)
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
     * Ajoute un nouveau genre avec le libellé donné en paramètre
     * 
     * @param string $libGenre : le libelle du genre à ajouter
     * @return int l'identifiant du genre crée
     */
    public function ajouterMarque(string $nomMarque): int
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("INSERT INTO marque "
                . "(idMarque, nomMarque) "
                . "VALUES (0, :unNomMarque) ");
            $requete_prepare->bindParam(':unNomMarque', $nomMarque, PDO::PARAM_STR);
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
    public function modifierMarque(int $idMarque, string $nomMarque): void
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("UPDATE marque "
                . "SET nomMarque = :unNomMarque "
                . "WHERE marque.idMarque = :unIdMarque");
            $requete_prepare->bindParam(':unIdMarque', $idMarque, PDO::PARAM_INT);
            $requete_prepare->bindParam(':unNomMarque', $nomMarque, PDO::PARAM_STR);
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
    public function supprimerMarque(int $idMarque): void
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("DELETE FROM marque "
                . "WHERE marque.idMarque = :idMarque");
            $requete_prepare->bindParam(':idMarque', $idMarque, PDO::PARAM_INT);
            $requete_prepare->execute();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }
    //endregion

    //region plateforme
    //==============================================================================
    //
    //	METHODES POUR LA GESTION DES plateformes
    //
    //==============================================================================

    /**
     * Retourne tous les plateforme sous forme d'un tableau d'objets 
     * 
     * @return array le tableau d'objets  (Genre)
     */
    public function getLesPlateformes(): array
    {
        $requete =  'SELECT idPlateforme as identifiant, libPlateforme as libelle 
						FROM  plateforme
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
     * Ajoute un nouveau plateforme avec le libellé donné en paramètre
     * 
     * @param string $libplateforme : le libelle de la plateforme à ajouter
     * @return int l'identifiant de la plateforme crée
     */
    public function ajouterPlateforme(string $nomPlateforme): int
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("INSERT INTO plateforme "
                . "(idPlateforme, libPlateforme) "
                . "VALUES (0, :unLibPlateforme) ");
            $requete_prepare->bindParam(':unLibPlateforme', $nomPlateforme, PDO::PARAM_STR);
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
    public function modifierPlateforme(int $idPlateforme, string $nomPlateforme): void
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("UPDATE plateforme "
                . "SET libPlateforme = :unLibPlateforme "
                . "WHERE plateforme.idPlateforme = :unIdPlateforme");
            $requete_prepare->bindParam(':unIdPlateforme', $idPlateforme, PDO::PARAM_INT);
            $requete_prepare->bindParam(':unLibPlateforme', $nomPlateforme, PDO::PARAM_STR);
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
    public function supprimerPlateforme(int $idPlateforme): void
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("DELETE FROM plateforme "
                . "WHERE plateforme.idPlateforme = :idPlateforme");
            $requete_prepare->bindParam(':idPlateforme', $idPlateforme, PDO::PARAM_INT);
            $requete_prepare->execute();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }
    //endregion

    //region pegi
    //==============================================================================
    //
    //	METHODES POUR LA GESTION DES pegis
    //
    //==============================================================================

    /**
     * Retourne tous les pegi sous forme d'un tableau d'objets 
     * 
     * @return array le tableau d'objets  (Genre)
     */
    public function getLesPegis(): array
    {
        $requete =  'SELECT idPegi as identifiant, ageLimite as libelle , descPegi
						FROM  pegi
						ORDER BY ageLimite';
        try {
            $resultat = PdoJeux::$monPdo->query($requete);
            $tbPegis  = $resultat->fetchAll();
            return $tbPegis;
        } catch (PDOException $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }


    /**
     * Ajoute un nouveau pegi avec le libellé donné en paramètre
     * 
     * @param string $ageLimite : le libelle de la pegi à ajouter
     * @return int l'identifiant de la pegi crée
     */
    public function ajouterPegi(string $nomPegi): int
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("INSERT INTO pegi "
                . "(idPegi, ageLimite, descPegi) "
                . "VALUES (0, :unAgeLimite) ");
            $requete_prepare->bindParam(':unAgeLimite', $nomPegi, PDO::PARAM_STR);
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
    public function modifierPegi(int $idPegi, string $nomPegi): void
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("UPDATE pegi "
                . "SET ageLimite = :unAgeLimite "
                . "WHERE pegi.idPegi = :unIdPegi");
            $requete_prepare->bindParam(':unIdPegi', $idPegi, PDO::PARAM_INT);
            $requete_prepare->bindParam(':unAgeLimite', $nomPegi, PDO::PARAM_STR);
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
    public function supprimerPegi(int $idPegi): void
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("DELETE FROM pegi "
                . "WHERE pegi.idPegi = :unIdPegi");
            $requete_prepare->bindParam(':unIdPegi', $idPegi, PDO::PARAM_INT);
            $requete_prepare->execute();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }
    //endregion

    //region Jeux
    //==============================================================================
    //
    //	METHODES POUR LA GESTION DES JEUX
    //
    //==============================================================================

    /**
     * Retourne tous les genres sous forme d'un tableau d'objets 
     * 
     * @return array le tableau d'objets  (Genre)
     */
    public function getLesJeux(): array
    {
        $requete =  'SELECT refJeu AS identifiant, jeu_video.idPlateforme, libPlateforme, jeu_video.idPegi, ageLimite, jeu_video.idGenre as idGenre, libGenre, jeu_video.idMarque, nomMarque, nom, prix, dateParution FROM jeu_video INNER JOIN plateforme ON plateforme.idPlateforme = jeu_video.idPlateforme INNER JOIN pegi ON pegi.idPegi = jeu_video.idPegi INNER JOIN genre ON genre.idGenre = jeu_video.idGenre INNER JOIN marque ON marque.idMarque = jeu_video.idMarque ORDER BY nom;';
        try {
            $resultat = PdoJeux::$monPdo->query($requete);
            $tbJeux  = $resultat->fetchAll();
            return $tbJeux;
        } catch (PDOException $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }
    public function supprimerJeu(string $refJeu): void
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("DELETE FROM jeu_video WHERE refJeu = :refJeu");
            $requete_prepare->bindParam(':refJeu', $refJeu, PDO::PARAM_STR);
            $requete_prepare->execute();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }
    public function ajouterJeu(string $refJeu, int $idPlateforme, int $idPegi, int $idGenre, int $idMarque, string $nom, float $prix, string $dateParution): string
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("INSERT INTO jeu_video (refJeu, idPlateforme, idPegi, idGenre, idMarque, nom, prix, dateParution) VALUES (:refJeu, :idPlateforme, :idPegi, :idGenre, :idMarque, :nom, :prix, :dateParution ) ");
            $requete_prepare->bindParam(':refJeu', $refJeu, PDO::PARAM_STR);
            $requete_prepare->bindParam(':idPlateforme', $idPlateforme, PDO::PARAM_INT);
            $requete_prepare->bindParam(':idPegi', $idPegi, PDO::PARAM_INT);
            $requete_prepare->bindParam(':idGenre', $idGenre, PDO::PARAM_INT);
            $requete_prepare->bindParam(':idMarque', $idMarque, PDO::PARAM_INT);
            $requete_prepare->bindParam(':nom', $nom, PDO::PARAM_STR);
            $requete_prepare->bindParam(':prix', $prix);
            $requete_prepare->bindParam(':dateParution', $dateParution);
            $requete_prepare->execute();
            // récupérer l'identifiant crée
            return PdoJeux::$monPdo->lastInsertId();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }
    public function modifierJeu(string $refJeu, int $idPlateforme, int $idPegi, int $idGenre, int $idMarque, string $nom, float $prix, string $dateParution): void
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("UPDATE jeu_video SET idPlateforme = :idPlateforme, idPegi = :idPegi, idGenre = :idGenre, idMarque = :idMarque, nom = :nom, prix = :prix, dateParution = :dateParution WHERE refJeu = :refJeu");
            $requete_prepare->bindParam(':refJeu', $refJeu, PDO::PARAM_STR);
            $requete_prepare->bindParam(':idPlateforme', $idPlateforme, PDO::PARAM_INT);
            $requete_prepare->bindParam(':idPegi', $idPegi, PDO::PARAM_INT);
            $requete_prepare->bindParam(':idGenre', $idGenre, PDO::PARAM_INT);
            $requete_prepare->bindParam(':idMarque', $idMarque, PDO::PARAM_INT);
            $requete_prepare->bindParam(':nom', $nom, PDO::PARAM_STR);
            $requete_prepare->bindParam(':prix', $prix);
            $requete_prepare->bindParam(':dateParution', $dateParution);
            $requete_prepare->execute();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }
    //endregion
}
