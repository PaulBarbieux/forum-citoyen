<?php
session_start();
header( 'content-type: text/html; charset=utf-8' );

/*
	Redirection en cas de maintenance (ferme le site et nécessite une identifiation des admins via la porte)
*/
#if (!isset($_SESSION['entree'])) header('location:/closed.php');

/*
	Cookies autoriés ?
	En arrivant dans une page qui ne peut être appelée qu'au travers d'une autre, 
	tester l'existence de se cookie permet de savoir s'ils ne sont pas bloqués.
*/
setcookie("welcome", "true", time() + 3600, '/');

/*
	À propos de cette page
*/
$THIS_PAGE['name'] = basename($_SERVER['PHP_SELF']);
$THIS_PAGE['path'] = $_SERVER['PHP_SELF'];
$domain = explode(".",$_SERVER['SERVER_NAME']);

/*
	Constantes liées au site
*/
require_once "config.php";

/*
	Connecté ?
*/
if (isset($_SESSION['mon_profil'])) {
	$CONNECTED = true;
} else {
	$CONNECTED = false;
}

if (strpos($THIS_PAGE['path'],ADMIN_FOLDER) !== false) {
	if ($CONNECTED and $_SESSION['mon_profil']['role'] == "admin") {
		define("ADMIN",true);
	} else {
		// Accès interdit ! Faire semblant que c'est une page inconnue.
		header("Location: /error404");
	}
} else {
	define("ADMIN",false);
}

/*
	Variables utilisées dans la plupart des pages
*/
$error = false; // True quand il y a une erreur
$message = ""; // Message affiché en cas de bonne fin ou d'erreur
$messageWarning = ""; // Rempli s'il faut aficher un message d'avertissement (en plus du message de bonne fin)
/*
	La plupart des actions se font par l'envoi d'un paramètre "action" avec sa valeur associée (en GET ou POST)
*/
if (isset($_POST['action'])) {
	$action = $_POST['action'];
} elseif (isset($_GET['action'])) {
	$action = $_GET['action'];
} else {
	$action = "init";
}

/*
	SQL
*/
if (TESTING) {
	$dbHost = "mysql:host=localhost;dbname=recit";
	$dbUser = "root";
	$dbPsw = "";
} else {
	$dbHost = DB_HOST;
	$dbUser = DB_USER;
	$dbPsw = DB_PSW;
}
try {
	$db = new PDO($dbHost,$dbUser,$dbPsw);
} catch (Exception $e) {
	die ("new PDO error : " . $e->getMessage());
}

define ("DUPLICATE_KEY","ERROR_1062");

function sqlExecute($query) {
	global $db, $domain;
	if (TESTING) {
		print "<!--\n".$query."\n-->";
	}
	$results = $db->query($query);
	if ($results === false) {
		$sqlError = $db->errorInfo();
		if ($sqlError[1] == 1062) {
			return DUPLICATE_KEY;
		} else {
			print "<P class='alert alert-danger'>Erreur SQL : ".$query."<BR>";
			print_r ($db->errorInfo());
			print "</P>";
			exit;
		}
	}
	return $results;
}

/*
	Liste des projets en cours
*/
$PROJETS = array();
$rows = sqlExecute("
	SELECT * FROM projets, personnes 
	WHERE statut='accepte' AND demandeur=email
	ORDER BY id DESC");
while ($row = $rows->fetch()) {
	$PROJETS[$row['id']] = $row;
	if (isset($_COOKIE["forum_citoyen_namur_".$row['id']])) {
		// Vote déjà fait ou interdit
		$PROJETS[$row['id']]['vote_ouvert'] = false;
	} else {
		$PROJETS[$row['id']]['vote_ouvert'] = true;
	}
	$PROJETS[$row['id']]['adhesions'] = array();
	$PROJETS[$row['id']]['medias'] = array();
}

/*
	Liste des forums en cours
*/
$FORUMS = array();
$rows = sqlExecute("SELECT * FROM forums WHERE statut='actif' ORDER BY titre");
while($row = $rows->fetch()) {
	$FORUMS[$row['projet']] = $row; // L'id forum est utilisé comme index : c'est plus facile pour ensuite ajouter les adhérents
	$FORUMS[$row['projet']]['adhesions'] = array();
}

/*
	Souscriptions : les sélectionner tous et les assigner aux projets ou forums suivant le statut
*/
$rows = sqlExecute("
	SELECT A.email, A.profil, P.id, P.statut, R.prenom
	FROM projets P, adhesions A, personnes R
	WHERE A.projet=P.id AND A.email=R.email AND (R.code_confirmation='' OR R.code_confirmation is NULL) 
	ORDER BY id");
while($row = $rows->fetch()) {
	switch ($row['statut']) {
		case "accepte" :
			// Adhésion à un projet accepté
			$PROJETS[$row['id']]['adhesions'][$row['email']] = array('prenom'=>$row['prenom'],'profil'=>$row['profil']);
			break;
		case "forum" :
			// Adhésion à un projet devenu forum
			if (isset($FORUMS[$row['id']])) {
				$FORUMS[$row['id']]['adhesions'][$row['email']] = array('prenom'=>$row['prenom'],'profil'=>$row['profil']);
			}
			break;
	}
}

/*
	Medias à ajouter aux projets
*/
$rows = sqlExecute("SELECT * FROM medias ORDER BY proprietaire");
while($row = $rows->fetch()) {
	if (isset($PROJETS[$row['proprietaire']])) {
		if ($row['role'] == "illustration") {
			$PROJETS[$row['proprietaire']]['illustration'] = $row;
		} else {
			$PROJETS[$row['proprietaire']]['medias'][$row['fichier']] = $row;
		}
	}
}

/*
	Classes pour le BODY
*/
$bodyClasses = "";
if (ADMIN) {
	$bodyClasses .= "admin";
}

/*
	Retour de valeurs par rapport au statut
*/
function classStatus($statut) {
	switch ($statut) {
		case "attente" : return "default";
		case "accepte" : return "success";
		case "actif" : return "success";
		case "nouveau" : return "warning";
		case "inactif" : return "warning";
		case "rejete" : return "danger";
		case "forum" : return "primary";
	}
}
function labelStatus($statut)  {
	switch ($statut) {
		case "attente" : return "Non confirm&eacute;";
		case "nouveau" : return "En attente d'approbation";
		case "accepte" : return "Ouvert";
		case "rejete" : return "Rejet&eacute;";
		case "forum" : return "&Eacute;lu en forum";
	}
}

// Quelques transformations de date
function transformDate($date,$format) {
	switch ($format) {
		case "dd/mm/yyyy" :
			return substr($date,8,2)."/".substr($date,5,2)."/".substr($date,0,4);
		case "yyyy/mm/dd" :
			return substr($date,6,4)."/".substr($date,3,2)."/".substr($date,0,2);
	}
}

// Original PHP code by Chirp Internet: www.chirp.com.au
// Please acknowledge use of this code by including this header.
// http://www.the-art-of-web.com/php/truncate/
function truncateText($string, $limit, $break=".", $pad="...") {
	// return with no change if string is shorter than $limit
	if (strlen($string) <= $limit) return $string;
	// is $break present between $limit and the end of the string?
	if (false !== ($breakpoint = strpos($string, $break, $limit))) {
		if ($breakpoint < strlen($string) - 1) {
			$string = substr($string, 0, $breakpoint) . $pad;
		}
	}
	return $string;
}

// Transformer le nom d'un fichier en non correct
function goodFileName($fileName) {
	$fileName = str_replace(array(" ","%"),"_",$fileName);
	$search = explode(",","ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,ø,Ø,Å,Á,À,Â,Ä,È,É,Ê,Ë,Í,Î,Ï,Ì,Ò,Ó,Ô,Ö,Ú,Ù,Û,Ü,Ÿ,Ç,Æ,Œ");
	$replace = explode(",","c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,o,O,A,A,A,A,A,E,E,E,E,I,I,I,I,O,O,O,O,U,U,U,U,Y,C,AE,OE");
	return str_replace($search, $replace, $fileName);
}

// Générer une image de fond aléatoire
function randomStripImage() {
	echo 'style="background-image:url(\'/img/strips/strip-'.rand(2,5).'.jpg\')"';
}

/*
	Fonctions pour vériffier les données envoyées
*/
function isItEmail($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}
function isItPhone($phone) {
	if (preg_match("/^\+?\d{2}[\s-]?\d{2}[\s-]?\d{4}[\s-]?\d{4}$/", $phone)) {
		return false;
	} else {
		return true;
	}	
}
function isItNumeric($str) {
	if (is_numeric($str)) {
	 	return(true);
	} else {
		return(false);
	}
}
function isItPostCode($str) {
	if (!isItNumeric($str)) {
		return(false);
	} elseif (strlen($str) < 4 or strlen($str) > 5) {
		return(false);
	} else {
		return(true);
	}
}
// Détecter s'il s'agit d'un robot : le formulaire doit contenir un input "start" (chrono) et un "last_name" (piège)
function isItHuman() {
	if (!isset($_POST['start']) or !isset($_POST['last_name'])) {
		return false;
	}
	if (time() - $_POST['start'] < 5) {
		return false;
	} elseif ($_POST['last_name'] != "") {
		return false;
	}
	return (time() - $_POST['start']);
}

?>