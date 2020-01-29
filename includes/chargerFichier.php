<?php
/*
	$idProprietaire = id du projet ou idée ou...
	$fichierEnvoye = $_FILES[input]
	$fichiersCharges = array des fichiers chargés
	$fichierDocPermis = le media peut être un document ?
*/
if (!isset($fichierDocPermis)) $fichierDocPermis = true;
if ($fichierEnvoye['error'] != 0) {
	$error = true;
	$message = "D&eacute;sol&eacute;, le fichier ".$fichierEnvoye['name']." n'a pas pu &ecirc;tre charg&eacute;. Les raisons peuvent &ecirc;tre : fichier trop gros, format non accept&eacute;, fichier inaccessible sur votre ordinateur.";
} else {
	$typeFichier = "";
	switch (strtolower(pathinfo($fichierEnvoye['name'],PATHINFO_EXTENSION))) {
		case "gif" :
		case "jpg" :
		case "jpeg" :
		case "png" :
			$typeFichier = "image";
			break;
		case "pdf" :
			if ($fichierDocPermis) {
				$typeFichier = "document";
			} else {
				$error = true;
				$message = "D&eacute;sol&eacute;, le fichier <strong>".$fichierEnvoye['name']."</strong> n'est pas une image;";
			}
			break;
		default :
			$error = true;
			$message = "D&eacute;sol&eacute;, le fichier <strong>".$fichierEnvoye['name']."</strong> n'est pas dans un format autoris&eacute;";
	}
}
if (!$error) {
	// Chargement du fchier
	$nomFichier = $idProprietaire."_".goodFileName($fichierEnvoye['name']);
	if (move_uploaded_file($fichierEnvoye['tmp_name'],$_SERVER['DOCUMENT_ROOT'].MEDIAS_FOLDER.$nomFichier) === false) {
		// Erreur au chargement
		$error = true;
		switch ($fichierEnvoye['error']) {
			case UPLOAD_ERR_INI_SIZE :
			case UPLOAD_ERR_FORM_SIZE :
				$message = "D&eacute;sol&eacute;, le fichier <strong>".$fichierEnvoye['name']."</strong> est trop gros.";
				break;
			default :
				$message = "Une erreur technique est arriv&eacute;e au chargement du fichier <strong>".$fichierEnvoye['name']."</strong> (erreur ".$fichierEnvoye['error'].").";
		}
	} else {
		// Chargement réussi
		$fichiersCharges[$nomFichier] = $typeFichier;
	}
}
?>