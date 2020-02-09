<?php
define("SITE_TITLE","Forum Citoyen"); // Nom du site
define("SITE_SLOGAN","Slogan du site"); // Slogan en page d'accueil
define("ASBL","ASBL Forum Citoyen");
define("ADMIN_EMAIL","admin@forumcitoyen.be"); // email d'administration
define("WEBMASTER_EMAIL","info@forumcitoyen.be"); // email webmaster
define("SEND_EMAIL","noreply@forumcitoyen.be"); // email d'envoi par le site
define("ADMIN_FOLDER","/_admin/"); // Dossier d'administration
define("MEDIAS_FOLDER","/_medias/"); // Dossier des médias uploader par les citoyens
if ($domain[0] == "local") {
	define("TESTING",true); // Si en local : test
	define("ROOT",$_SERVER['DOCUMENT_ROOT']);
} else {
	define("TESTING",false);
	define("ROOT",$_SERVER['DOCUMENT_ROOT']);
}
define("DB_HOST","mysql:host=localhost;dbname=host");
define("DB_USER","user");
define("DB_PSW","psw");
?>