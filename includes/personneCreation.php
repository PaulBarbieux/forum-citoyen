<?php
$codeConfirmation = time() . rand(1000,9999); // Id qui sert pour la confirmation
if (sqlExecute ("
	INSERT INTO personnes 
	(email, nom, prenom, telephone, code_postal, commune, rue, presentation, manifeste, passe, code_confirmation, date_inscription) 
	VALUES (".
	$db->quote($values['email']).",".
	$db->quote($values['nom']).",".
	$db->quote($values['prenom']).",".
	$db->quote($values['telephone']).",".
	$db->quote($values['code_postal']).",".
	$db->quote($values['commune']).",".
	$db->quote($values['rue']).",".
	$db->quote($values['presentation']).",".
	$values['manifesteBool'].",".
	$db->quote(crypt($values['passe'])).",".
	$db->quote($codeConfirmation).','.
	"NOW())"
) == DUPLICATE_KEY) {
	// Personne déjà connue
	$error = true;
	$message = "Cet email est d&eacute;j&agrave; connu dans notre syst&egrave;me : <A href='/connexion/?back=".$urlRetourApresConnexion."'>veuillez vous connecter</A>.";
} else {
	// Envoi d'un message au webmaster pour vérifier la procédure
	mail(WEBMASTER_EMAIL,
		"Site ".SITE_TITLE." : nouvel inscrit",
		utf8_decode("Personne nouvellement inscrite, à confirmer : ".$values['email']." : ".$values['prenom']." ".$values['nom']),
		"MIME-Version: 1.0
		Content-Type: text/html; charset=UTF-8
		From: ".EMAIL_SEND);
	// Envoi d'un message pour confirmer ce profil
	require_once ROOT.'/includes/phpmailer/PHPMailerAutoload.php';
	$mail = new PHPMailer;
	$mail->From = SEND_EMAIL;
	$mail->Fromname = SITE_TITLE;
	$body = utf8_decode("<P>Vous vous &ecirc;tes inscrit sur le site ".SITE_TITLE.".
		<A href='http://".$_SERVER['SERVER_NAME']."/connexion/?confirm=".$codeConfirmation."'>Veuillez confirmer votre email en cliquant sur ce lien</A>.</P>
		<P>Si vous n'&ecirc;tes pas l'auteur de cette inscription, ignorez ce message et, &eacute;ventuellement, signalez-nous ce vol d'identit&eacute; en nous transmettant cet email &agrave l'adresse ".ADMIN_EMAIL.".</P>");
	$mail->isHTML(true);
	$mail->Body = $body;
	$mail->addAddress($values['email'],$values['prenom']." ".$values['nom']);
	$mail->Subject = SITE_TITLE." : confirmez votre inscription";
	if (TESTING) {
		print "<DIV class='mail-body-test'>".$body."</DIV>";
	} else {
		if (!$mail->send()) {
			print "<P class='alert alert-danger'>Oups ! Erreur technique lors de l'envoi !<br>".$mail->ErrorInfo."</P>";
		}
	}
}
?>