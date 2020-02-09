<?php
// $values doit être un array comme celui envoyé par $_POST
$error = false;
$message = "";
foreach ($values as $input=>$value) {
	$value = strip_tags(trim($value));
	$values[$input] = $value;
	switch ($input) {
		case "passe" :
			if ($value == "" and !ADMIN) {
				$error = true;
				$message = "Veuillez remplir toutes les zones s.v.p.";
			}
			break;
		case "passe2" :
			if (ADMIN and $value == "" and $values['passe'] == "") {
				// L'administrateur peut laisser le mot de passe vide
			} elseif (!$error) {
				if ($value != $values['passe']) {
					$error = true;
					$message = "Les mots de passe ne sont pas pareils : veuillez les r&eacute;-encoder.";
				} elseif (strlen($value) < 8) {
					$error = true;
					$message = "Veuillez donner un mot de passe d'au moins 8 caract&egrave;res svp.";
				}
			}
			break;
		case "email" :
			if (!$error and !isItEmail($value)) {
				$error = true;
				$message = "Votre adresse email n'est pas valide";
			}
			break;
		case "telephone" :
			if (!$error and $value != "" and !isItPhone($value)) {
				$error = true;
				$message = "Votre téléphone n'est pas valide";
			}
			break;
		case "code_postal" :
			if (!$error and !isItPostCode($value)) {
				$error = true;
				$message = "Votre code postal n'est pas valide";
			}
			break;
		case "nom" :
		case "prenom" :
		case "commune" :
			if ($value == "") {
				$error = true;
				$message = "Veuillez remplir toutes les zones s.v.p. <!-- ".$input." -->";
			}
	}
}
if (!ADMIN and !isset($values['charte'])) {
	$error = true;
	$message = "Veuillez approuver notre charte s.v.p.";
	$values['charteBool'] = 0;
} else {
	$values['charteBool'] = 1;
}
// Soutient le manifeste ?
if (isset($values['manifeste']) and $values['manifeste'] == 1) {
	$values['manifesteBool'] = 1;
} else {
	$values['manifeste'] = 0;
	$values['manifesteBool'] = 0;
}
?>