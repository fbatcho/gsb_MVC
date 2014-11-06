<?php

include("vues/v_sommaireC.php");


$action = $_REQUEST['action'];
//$idComptable = $_SESSION['idComptable'];


switch ($action) {
    case 'voirVisiteur': {
            $lesVisiteurs = $pdo->getLesVisiteursetMois();
            $Key = array_keys($lesVisiteurs);
            $selectionnerVisiteur = $Key[0];

            include("vues/v_voirVisiteur.php");
            break;
        }
    case 'LaFicheduVisiteur': {
            $leMois = $_REQUEST['lstMois'];
            $_SESSION['leMois'] = $leMois;
            $leVisiteur = $_REQUEST['lstVisiteur'];
            $_SESSION['leVisiteur'] = $leVisiteur;
            $lesVisiteurs = $pdo->getLesVisiteursetMois();
            $selectionnerVisiteur = $leVisiteur;
            include("vues/v_voirVisiteur.php");
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($leVisiteur, $leMois);
            $lesFraisForfait = $pdo->getLesFraisForfait($leVisiteur, $leMois);
            $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($leVisiteur, $leMois);
            $numAnnee = substr($leMois, 0, 4);
            $numMois = substr($leMois, 4, 2);
            $libEtat = $lesInfosFicheFrais['libEtat'];
            $montantValide = $lesInfosFicheFrais['montantValide'];
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            $dateModif = $lesInfosFicheFrais['dateModif'];
            $dateModif = dateAnglaisVersFrancais($dateModif);
            include("vues/v_voirFiche.php");

            break;
        }
    case 'ModifFiche': {
            $leVisiteur = $_SESSION['leVisiteur'];
            $leMois = $_SESSION['leMois'];
            $lesFrais = $_REQUEST['lesFrais'];
            $lesVisiteurs = $pdo->getLesVisiteursetMois();
            $selectionnerVisiteur = $leVisiteur;
            include("vues/v_voirVisiteur.php");
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($leVisiteur, $leMois);
            $lesFraisForfait = $pdo->getLesFraisForfait($leVisiteur, $leMois);
            $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($leVisiteur, $leMois);
            $numAnnee = substr($leMois, 0, 4);
            $numMois = substr($leMois, 4, 2);
            $libEtat = $lesInfosFicheFrais['libEtat'];
            $montantValide = $lesInfosFicheFrais['montantValide'];
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            $dateModif = $lesInfosFicheFrais['dateModif'];
            $dateModif = dateAnglaisVersFrancais($dateModif);
            include("vues/v_voirFiche.php");
            if (lesQteFraisValides($lesFrais)) {
                $pdo->majFraisForfait($leVisiteur, $leMois, $lesFrais);

                echo ' les éléments forfaitisés on été modifiée!';
            } else {
                ajouterErreur("Les valeurs des frais doivent être numériques");
                include("vues/v_erreurs.php");
            }
            include ("vues/v_ModFicheFrais.php");
            break;
        }
    case 'supprimerFrais': {
            $idFrais = $_REQUEST['idFrais'];
            $rs = $pdo->ModifFraisHorsForfait($idFrais);
            if($rs==0){
             ajouterErreur('La fiche frais a été supprimé');
             $type=1;
             include("vues/v_erreurs.php");
            }
 else {
     ajouterErreur("La ligne n'a pas été supprimé");
     include("vues/v_erreurs.php");
 }
            
            break;
        }
    case "reporterFrais": {
           $mois=$_SESSION['leMois'];
           var_dump($mois);
           $unmois= substr($mois,4,2);
           var_dump($mois);
        break; }
    case "validerFrais": {
            $leVisiteur = $_SESSION['leVisiteur'];
            $leMois = $_SESSION['leMois'];
            $nbJustificatifs = $_REQUEST['nbJustificatifs'];
            $pdo->majEtatFicheFrais($leVisiteur, $leMois, "VA", $nbJustificatifs);
            ajouterErreur('La Fiche frais a bien été validé!');
            $type=1;
            include("vues/v_erreurs.php");
        }


        break;
}
?>
