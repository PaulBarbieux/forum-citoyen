<?php
require $_SERVER['DOCUMENT_ROOT']."/includes/init.php";

$error = false;
$messageWarning = "";
$fichiersCharges = array();

if ($action == "nouveauProjet") {
	// Traiter les autres données (y compris les données personnelles déjà été traitées plus haut : pas grave !)
	foreach ($_POST as $input=>$value) {
		switch ($input) {
			case "last_name" :
			case "start" :
				// Anti-spam : voir fonction isItHuman plus bas
				break;
			case "image" :
			case "media" :
				// Fichiers : traités plus loin
				break;
			default :
				$value = strip_tags(trim($value));
				if ($value == "") {
					$error = true;
					$message = "Veuillez encoder toutes les informations s.v.p.";
				}
				$values[$input] = $value;
		}
	}
	// Robot ?
	if (isItHuman() === false) {
		// Oui, un robot ! Ne rien signaler, juste rafraîchir la page
		header("Location: soumettre.php");
		exit;
	}
	if (!$error) {
		// Données personnelles
		if ($CONNECTED) {
			$values['email'] = $_SESSION['mon_profil']['email'];
		} else {
			// Non connecté : traiter les données personnelles
			require $_SERVER['DOCUMENT_ROOT']."/includes/personneValidation.php";
			if (!$error) {
				// Créer la personne
				$urlRetourApresConnexion = "/projet/soumettre.php";
				require $_SERVER['DOCUMENT_ROOT']."/includes/personneCreation.php";
			}
		}
	}
	if (!$error) {
		// Traiter les fichiers
		$id = time();
		$idProprietaire = $id;
		// Vérifier et charger les fichiers
		if ($_FILES['image']['name'] != "") {
			// Image d'illustration
			$fichierDocPermis = false;
			$fichierEnvoye = $_FILES['image'];
			require $_SERVER['DOCUMENT_ROOT']."/includes/chargerFichier.php";
			if (!$error) {
				// Modifier son rôle
				$fichiersCharges[$nomFichier] = "illustration";
			}
		}
	}
	if (!$error) {
		// Charger les fichiers media
		$fichierDocPermis = true;
		for ($iFile=0; $iFile<=2; $iFile++) {
			// Autres médias
			if ($_FILES['media']['name'][$iFile] != "") {
				$fichierEnvoye = array(
					'name' => $_FILES['media']['name'][$iFile],
					'error' => $_FILES['media']['error'][$iFile],
					'tmp_name' => $_FILES['media']['tmp_name'][$iFile]
				);
				require $_SERVER['DOCUMENT_ROOT']."/includes/chargerFichier.php";
			}
		}
	}
	if ($error) {
		// Supprimer les fichiers chargés
		foreach ($fichiersCharges as $nomFichier=>$typeFichier) {
			unlink($_SERVER['DOCUMENT_ROOT'].MEDIAS_FOLDER.$nomFichier);
		}
	} else {
		// Créer le projet
		sqlExecute ("INSERT INTO projets VALUES (".
			$db->quote($id).",".
			$db->quote($values['titre']).",".
			$db->quote($values['email']).",".
			$db->quote($values['description']).",".
			$db->quote($values['attente']).",".
			$db->quote($values['ressources']).",".
			$db->quote($values['initiative']).",".
			$db->quote($values['demarches']).
			",'nouveau',0)"
		);
		// Créer les fichiers
		foreach ($fichiersCharges as $nomFichier=>$typeFichier) {
			sqlExecute("INSERT INTO medias (fichier,proprietaire,role) VALUES (".$db->quote($nomFichier).",'".$id."','".$typeFichier."')");
		}
		$action = "completed";
		$message = "Votre projet a été envoyé. Vous serez prochainement informé de la suite.";
		// Message aux administrateurs
		require_once $_SERVER['DOCUMENT_ROOT'].'/includes/phpmailer/PHPMailerAutoload.php';
		$mail = new PHPMailer;
		$mail->From = SEND_EMAIL;
		$mail->FromName = SITE_TITLE;
		$body = utf8_decode("<P>Un nouveau projet a &eacute;t&eacute; soumis sur le site. <A href='http://".$_SERVER['SERVER_NAME']."/_admin/projet.php?action=edit&id=".$id."'>Veuillez le traiter</A>.</P>");
		$mail->isHTML(true);
		$mail->Body = $body;
		$mail->addAddress(ADMIN_EMAIL,SITE_TITLE);
		$mail->Subject = SITE_TITLE." : nouveau projet ".$values['titre'];
		if (TESTING) {
			print "<DIV class='mail-body-test'>".$body."</DIV>";
		} else {
			if (!$mail->send()) {
				print "Oups ! Erreur technique lors de l'envoi !<br>".$mail->ErrorInfo;
				exit;
			}
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nous soumettre un projet | <?= SITE_TITLE ?></title>
    
	<link rel="icon shortcut" href="/img/favicon.png" type="image/png">
    <!-- Bootstrap -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="/js/jquery-1.10.1.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
	<script src="/js/pgwcookie.min.js"></script>
    <link href="/css/default.css" rel="stylesheet">
    <link href="/css/font-awesome.min.css" rel="stylesheet">
</head>

<body class="<?php echo $bodyClasses ?>">

<?php include $_SERVER['DOCUMENT_ROOT']."/includes/nav.php"; ?>

<DIV class="strip strip-banner">
	<DIV class="container">
		<DIV class="row">
			<A href="/" class="strip-brand"><IMG src="/img/logo_ombre-50.png"></A>
			<DIV class="strip-title">
				<H1>Soumettre votre projet</H1>
			</DIV>
		</DIV>
	</DIV>
</DIV>
<DIV class="strip">
	<DIV class="container">
		<DIV class="row">
			<DIV class="col-sm-12">
				<?php require $_SERVER['DOCUMENT_ROOT']."/includes/alerts.php" ?>
				<P>Le Forum Citoyen soutient, aide, offre une visibilité, partage son expérience avec les personnes souhaitant ouvrir le débat sur un sujet local (strictement limité aux ######## sauf exception) et cela dans la stricte limite de ses moyens. Le Forum n'a ni les capacités humaines ni la volonté de porter seul tous les sujets proposés.</P>
				<P>Il appartiendra donc à chaque initiateur de projet de s'entourer de personnes motivées pour porter celui-ci.</P>
				<P>Les projets feront l'objet d'une attention de la part du Forum Citoyen  et seront soutenus en fonction de leur importance, du nombre de personnes impactées, des capacités d'influer sur la prise de décision, de l'intérêt général, du nombre de personnes motivées à le porter.</P>
				<?php if (!$CONNECTED) { ?>
				<P class="alert alert-warning">Si vous êtes déjà enregistré sur notre site, <A href='/connexion/?back=/projet/soumettre.php'>veuillez vous connecter</A> avant de nous soumettre un projet.</P>
				<?php } ?>
			</DIV>
	<?php if ($action != "completed") { ?>
			<form method="post" enctype="multipart/form-data">
				<DIV class="col-md-6 col-sm-12">
					<DIV class="panel panel-default">
						<DIV class="panel-heading">
							<H2 class="panel-title">À propos de vous</H2>
						</DIV>
						<DIV class="panel-body">
							<?php if ($CONNECTED) { ?>
							<?php echo $_SESSION['mon_profil']['prenom'] ?>, vous êtes déjà connu(e) dans notre système.
							<?php } else { ?>
							<?php include $_SERVER['DOCUMENT_ROOT']."/includes/formPersonne.php" ?>
							<?php } ?>
						</DIV>
					</DIV>
					<DIV class="panel panel-default">
						<DIV class="panel-heading">
							<H2 class="panel-title">Médias</H2>
						</DIV>
						<DIV class="panel-body">
							<div class="form-group">
								<label for="image">Image d'illustration</label>
								<P>Votre projet sera plus visible avec une image.</P>
								<INPUT name="image" type="file" class="form-control">
							</div>
							<div class="form-group">
								<label for="media">Documents</label>
								<P>Vous pouvez joindre des documents (PDF) ou des images.</P>
								<INPUT name="media[]" type="file" class="form-control">
								<INPUT name="media[]" type="file" class="form-control">
								<INPUT name="media[]" type="file" class="form-control">
							</div>
							<P class="alert alert-warning">Les documents seront montrés avec leur nom de fichier : essayez d'avoir des noms explicites.</P>
						</DIV>
					</DIV>
					<INPUT type="hidden" name="action" value="nouveauProjet">
					<INPUT type="hidden" name="start" value="<?php echo time() ?>">
					<INPUT type="hidden" name="last_name">
					<BUTTON type="submit" class="btn btn-success btn-lg btn-block"><SPAN class="glyphicon glyphicon-send"></SPAN>&nbsp;&nbsp;&nbsp;Envoyez-nous votre projet</BUTTON>
				</DIV>
				<DIV class="col-md-6 col-sm-12">
					<DIV class="panel panel-default">
						<DIV class="panel-heading">
							<H2 class="panel-title">Le projet</H2>
						</DIV>
						<DIV class="panel-body">
							<?php include $_SERVER['DOCUMENT_ROOT']."/includes/formProjet.php" ?>
						</DIV>
					</DIV>
				</DIV>
			</form>
	<?php } ?>
		</DIV>
	</DIV>
</DIV>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>
</body>
</html>
