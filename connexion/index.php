<?php
require $_SERVER['DOCUMENT_ROOT']."/includes/init.php";

$error = false;
$values['demande'] = "";
$montrerFormInscription = true;

if ($action == "quitter_confirme") {
	// Quitter un forum ou projet
	sqlExecute("DELETE FROM adhesions WHERE email='".$_SESSION['mon_profil']['email']."' AND projet='".$_GET['id']."'");
	$action = "completed";
	if ($_GET['type'] == "forum") {
		$message = "Vous avez quitté le forum <strong>".$FORUMS[$_GET['id']]['titre']."</strong>.";
	} else {
		$message = "Vous avez quitté le projet <strong>".$PROJETS[$_GET['id']]['titre']."</strong>.";
	}
}

if (isset($_POST['connect'])) {
	// Identification
	$email = $_POST['email'];
	$passe = $_POST['passe'];
	$result = sqlExecute("SELECT * FROM personnes WHERE email='".$email."' AND (code_confirmation='' OR code_confirmation is NULL)");
	if ($row = $result->fetch()) {
		if (crypt($passe, $row['passe']) == $row['passe']) {
			// Stockage des données de la personne dans une session
			$_SESSION['mon_profil'] = $row;
			$CONNECTED = true;
			$action = "completed";
			$message = "Bonjour ".$row['prenom'];
			if (isset($_GET['back'])) {
				// Renvoie dans la page d'origine
				header("Location: ".$_GET['back']);
			}
		} else {
			// Mot de passe invalide
			$error = true;
			$message = "Désolé : email inconnu ou mot de passe invalide.";
		}
	} else {
		// Personne inconnue
		$error = true;
		$message = "Désolé : email inconnu ou mot de passe invalide.";
	}
} elseif (isset($_POST['corriger'])) {
	$values['demande'] = strip_tags($_POST['demande']);
	if ($values['demande'] == "") {
		$error = true;
		$message = "Veuillez écrire votre demande s.v.p.";
	} else {
		// Demande de correction
		require ROOT.'/includes/phpmailer/PHPMailerAutoload.php';
		$mail = new PHPMailer;
		$mail->From = SEND_EMAIL;
		$mail->FromName = SITE_TITLE;
		$body = utf8_decode("<P>".$_SESSION['mon_profil']['prenom']." ".$_SESSION['mon_profil']['nom']." a une demande par rapport &agrave; ses donn&eacute;es personnelles :</P>
			<P>".$values['demande']."</P>
			<P><A href='http://".$_SERVER['SERVER_NAME']."/_admin/personnes.php?action=edit&id=".$_SESSION['mon_profil']['email']."'>Corriger ses donn&eacute;es ici</A></P>");
		$mail->isHTML(true);
		$mail->Body = $body;
		$mail->addAddress(ADMIN_EMAIL,SITE_TITLE);
		$mail->addBCC(WEBMASTER_EMAIL,"Webmaster ".SITE_TITLE);
		$mail->Subject = SITE_TITLE." : demande de ".$_SESSION['mon_profil']['nom']." concernant ses données personnelles";
		if (TESTING) {
			print $body;
		} else {
			if (!$mail->send()) {
				print "Oups ! Erreur technique lors de l'envoi !<br>".$mail->ErrorInfo;
				exit;
			} else {
				$message = "Votre demande a été envoyée. Nous y donnerons suite dans les plus brefs délais.";
				$action = "completed";
			}
		}
	}
} elseif (isset($_POST['logout'])) {
	unset($_SESSION['mon_profil']);
	$CONNECTED = false;
} elseif (isset($_GET['confirm'])) {
	$codeConfirmation = $_GET['confirm'];
	$result = sqlExecute("SELECT * FROM personnes WHERE code_confirmation=".$db->quote($codeConfirmation));
	if ($result->fetch()) {
		// Code connu
		sqlExecute("UPDATE personnes SET code_confirmation=NULL where code_confirmation=".$db->quote($codeConfirmation));
		$action = "completed";
		$message = "Votre inscription est confirmée. Vous pouvez vous connecter.";
	} else {
		// Code inconnu
		$error = true;
		$message = "Ce code d'activation est inconnu : si vous l'avez copié-collé à partir de votre email, vérifiez que vous avez bien copier toute l'url.
			Ou peut-être que votre compte est déjà activé : vérifiez en vous connectant.";
	}
} elseif (isset($_POST['inscrire'])) {
	$values = $_POST;
	require $_SERVER['DOCUMENT_ROOT']."/includes/personneValidation.php";
	if (!$error) {
		require $_SERVER['DOCUMENT_ROOT']."/includes/personneCreation.php";
		if (!$error) {
			$action = "completed";
			$message = "Vous inscription est enregistrée : un email de confirmation a été envoyée à l'adresse <strong>".$values['email']."</strong>";
			$montrerFormInscription = false;
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
    <title><?= SITE_TITLE ?></title>
    
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
				<H1><?php if ($CONNECTED) { echo $_SESSION['mon_profil']['prenom']." ".$_SESSION['mon_profil']['nom']; } else { ?>Connexion<?php } ?></H1>
			</DIV>
		</DIV>
	</DIV>
</DIV>

<DIV class="strip">
	<DIV class="container">
		<DIV class="row">
			<DIV class="col-sm-12">
				<?php include $_SERVER['DOCUMENT_ROOT']."/includes/alerts.php"; ?>
				<?php if ($action == "quitter") { ?>
				<BR>
				<DIV class="panel panel-danger">
					<DIV class="panel-heading">
						<H2 class="panel-title">Quitter un <?php echo $_GET['type'] ?></H2>
					</DIV>
					<DIV class="panel-body">
						<P class="alert alert-warning">
							<?php if ($_GET['type'] == "forum") { ?>
							Veuillez confirmer votre souhait de quitter le forum <STRONG><?php echo $FORUMS[$_GET['id']]['titre'] ?></STRONG>.
							<?php } else { ?>
							Veuillez confirmer votre souhait de quitter le projet <STRONG><?php echo $PROJETS[$_GET['id']]['titre'] ?></STRONG>.
							<?php } ?>
						</P>
						<DIV class="btn-group">
							<A href="?action=quitter_confirme&id=<?php echo $_GET['id'] ?>&type=<?php echo $_GET['type'] ?>" class="btn btn-danger"><SPAN class="glyphicon glyphicon-ok"></SPAN> Confirmer</A>
							<A href="?cancel" class="btn btn-default"><SPAN class="glyphicon glyphicon-chevron-left"></SPAN> Annuler</A>
						</DIV>
					</DIV>
				</DIV>
				<?php } ?>
			</DIV>
		</DIV>
	<?php if ($CONNECTED) { ?>
		<DIV class="row">
			<DIV class="col-sm-12">
				<H1>Vous êtes connecté</H1>
				<P>
					<FORM method="post">
						<BUTTON type="submit" name="logout" class="btn btn-primary"><SPAN class="glyphicon glyphicon-log-out"></SPAN> Se déconnecter</BUTTON>
					</FORM>
				</P>
			</DIV>
		</DIV>
		<DIV class="row">
			<DIV class="col-md-6 col-sm-12">
				<DIV class="panel panel-default">
					<DIV class="panel-heading">
						<H2>Votre profil</H2>
					</DIV>
					<DIV class="panel-body">
						<TABLE class="table">
							<TR>
								<TH>Nom</TH>
								<TD><?php echo $_SESSION['mon_profil']['nom'] ?></TD>
							</TR>
							<TR>
								<TH>Prénom</TH>
								<TD><?php echo $_SESSION['mon_profil']['prenom'] ?></TD>
							</TR>
							<TR>
								<TH>Email</TH>
								<TD><?php echo $_SESSION['mon_profil']['email'] ?></TD>
							</TR>
							<TR>
								<TH>Téléphone</TH>
								<TD><?php echo $_SESSION['mon_profil']['telephone'] ?></TD>
							</TR>
							<TR>
								<TH>Adresse</TH>
								<TD><?php echo $_SESSION['mon_profil']['rue']." - ".$_SESSION['mon_profil']['code_postal']." ".$_SESSION['mon_profil']['commune'] ?></TD>
							</TR>
							<TR>
								<TH>Décrivez-vous</TH>
								<TD><?php echo nl2br($_SESSION['mon_profil']['presentation']) ?></TD>
							</TR>
							<TR>
								<TD colspan="2">
									J'adhère au manifeste ? <STRONG><?php echo ($_SESSION['mon_profil']['manifeste']==1 ? "Oui" : "Non") ?></STRONG>
								</TD>
							</TR>
						</TABLE>
						<FORM method="post">
							<LABEL for="demande">Demande de correction</LABEL>
							<TEXTAREA name="demande" class="form-control" rows="5" placeholder="Vous ne pouvez pas modifier vos données, mais vous pouvez demander des corrections au webmaster."><?php echo $values['demande'] ?></TEXTAREA>
							<BUTTON type="submit" class="btn btn-success btn-block" name="corriger"><SPAN class="glyphicon glyphicon-send"></SPAN> Envoyer</BUTTON>
						</FORM>
					</DIV>
				</DIV>
			</DIV>
			<DIV class="col-md-6 col-sm-12">
				<DIV class="panel panel-default">
					<DIV class="panel-heading">
						<H2>Vos projets</H2>
					</DIV>
					<DIV class="panel-body">
						<P>Voici les projets dont vous êtes l'initiateur.</P>
						<TABLE class="table table-stripped">
							<THEAD>
								<TR>
									<TH>Projet</TH>
									<TH>Statut</TH>
								</TR>
							</THEAD>
							<TBODY>
						<?php
						$rows = sqlExecute("SELECT * FROM projets WHERE demandeur='".$_SESSION['mon_profil']['email']."'");
						while ($row = $rows->fetch()) {
						?>
								<TR>
									<TD><?php if ($row['statut'] == "accepte") { ?><A href="/projet/?id=<?php echo $row['id'] ?>"><?php } ?>
										<?php echo $row['titre'] ?>
										<?php if ($row['statut'] == "accepte") { ?></A><?php } ?>
									</TD>
									<TD><span class="label label-<?php echo classStatus($row['statut']) ?>"><?php echo labelStatus($row['statut']) ?></span></TD>
								</TR>
						<?php
						}
						?>
							</TBODY>
						</TABLE>
					</DIV>
				</DIV>
			</DIV>
		</DIV>
		<DIV class="row">
			<DIV class="col-md-6 col-sm-12">
				<DIV class="panel panel-default">
					<DIV class="panel-heading">
						<H2>Les forums souscrits</H2>
					</DIV>
					<DIV class="panel-body">
						<P>Voici les forums auquels vous êtes souscits</P>
						<TABLE class="table table-stripped">
							<THEAD>
								<TR>
									<TH>Forum</TH>
									<TH>Votre profil</TH>
									<TH></TH>
								</TR>
							</THEAD>
							<TBODY>
						<?php
						$rows = sqlExecute("SELECT profil,P.id,F.titre,F.nom FROM adhesions A, projets P, forums F 
							WHERE email='".$_SESSION['mon_profil']['email']."' AND A.projet=P.id AND P.statut='forum' AND F.projet=P.id");
						while ($row = $rows->fetch()) {
						?>
								<TR>
									<TD><A href="/forum/?nom=<?php echo $row['nom'] ?>"><?php echo $row['titre'] ?></A></TD>
									<TD><?php echo $row['profil'] ?></TD>
									<TD><A href="?action=quitter&id=<?php echo $row['id'] ?>&type=forum" class="btn btn-sm btn-danger" title="Quitter ce forum"><SPAN class="glyphicon glyphicon-remove"></SPAN></A></TD>
								</TR>
						<?php
						}
						?>
							</TBODY>
						</TABLE>
					</DIV>
				</DIV>
			</DIV>
			<DIV class="col-md-6 col-sm-12">
				<DIV class="panel panel-default">
					<DIV class="panel-heading">
						<H2>Les projets souscrits</H2>
					</DIV>
					<DIV class="panel-body">
						<P>Voici les projets auquels vous avez adhérés.</P>
						<TABLE class="table table-stripped">
							<THEAD>
								<TR>
									<TH>Projet</TH>
									<TH>Votre profil</TH>
									<TH></TH>
								</TR>
							</THEAD>
							<TBODY>
						<?php
						$rows = sqlExecute("SELECT profil,id,titre FROM adhesions, projets WHERE email='".$_SESSION['mon_profil']['email']."' AND projet=id AND statut='accepte'");
						while ($row = $rows->fetch()) {
						?>
								<TR>
									<TD><A href="/projet/?id=<?php echo $row['id'] ?>"><?php echo $row['titre'] ?></A></TD>
									<TD><?php echo $row['profil'] ?></TD>
									<TD><A href="?action=quitter&id=<?php echo $row['id'] ?>&type=projet" class="btn btn-sm btn-danger" title="Quitter ce projet"><SPAN class="glyphicon glyphicon-remove"></SPAN></A></TD>
								</TR>
						<?php
						}
						?>
							</TBODY>
						</TABLE>
					</DIV>
				</DIV>
			</DIV>
		</DIV>
	<?php } else { ?>
		<DIV class="row">
			<DIV class="col-sm-4">
				<H1>Connectez-vous</H1>
				<DIV class="panel panel-primary">
					<DIV class="panel-body">
						<form method="post">
							<div class="form-group">
								<label for="titre">Votre adresse email</label>
								<input type="email" name="email" class="form-control" required>
							</div>
							<div class="form-group">
								<label for="titre">Votre mot de passe</label>
								<input type="password" name="passe" class="form-control" required>
							</div>
							<BUTTON type="submit" name="connect" class="btn btn-primary btn-block"><SPAN class="glyphicon glyphicon-log-in"></SPAN> S'identifier</BUTTON>
						</form>
					</DIV>
				</DIV>
			</DIV>
			<DIV class="col-sm-8">
				<H1>Pas encore enregistré dans notre site ?</H1>
				<p>Pour interagir sur notre site, il est indispensable que vous vous enregistriez.</p>
				<DIV class="panel panel-primary">
					<DIV class="panel-body">
						<?php if ($montrerFormInscription) { ?>
						<form method="post">
							<?php require $_SERVER['DOCUMENT_ROOT']."/includes/formPersonne.php" ?>
							<BUTTON type="submit" name="inscrire" class="btn btn-success"><span class="glyphicon glyphicon-send"></span> Envoyer</BUTTON>
						</form>
						<?php } else { ?>
						<P>Vous inscription est envoyée.</P>
						<?php } ?>
					</DIV>
				</DIV>
			</DIV>
	<?php } ?>
		</DIV>
	</DIV>
</DIV>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>
</body>
</html>
