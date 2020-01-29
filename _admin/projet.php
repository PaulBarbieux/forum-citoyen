<?php 
require $_SERVER['DOCUMENT_ROOT']."/includes/init.php";

$error = false;
$warning = false;

if ($action == "modifier") {
	$values = $_POST;
	$id = $_POST['id'];
	if ($values['statut'] == "forum") {
		$actionForum = "aucune";
		// Créer une entrée forum
		$values['forum'] = trim($values['forum']);
		if (strlen($values['forum']) <= 5) {
			$error = true;
			$message = "Donnez un nom de forum, et pas trop petit.";
		} elseif ($values['forum_old'] == "") {
			// C'est la première fois que l'on attribue un forum
			if (sqlExecute("INSERT INTO forums (nom,projet,statut) VALUES ('".$values['forum']."',".$id.",'inactif')") == DUPLICATE_KEY) {
				$actionForum = "doublon";
			} else {
				$actionForum = "créé";
			}
		} elseif ($values['forum'] != $values['forum_old']) {
			// Changement de nom de forum
			if (sqlExecute("UPDATE forums SET nom='".$values['forum']."' WHERE projet=".$id)  == DUPLICATE_KEY) {
				$actionForum = "doublon";
			} else {
				$actionForum = "modifié";
			}
		}
		if ($actionForum == "doublon") {
			$error = true;
			$message = "Ce nom de forum existe déjà.";
		} elseif ($actionForum == "créé" or $actionForum == "modifié")  {
			// Forum créé ou modifié, mais ce n'est pas fini...
			$warning = true;
			$messageWarning = "Vous devez cr&eacute;er la page <strong>".$values['forum'].".php</strong> dans le dossier /forum/presentations/.
				Quand elle est pr&ecirc;te, rendez publique le forum en statuant dans l'<A href='forums.php'>administration des forums</A>.";
			$values['forum_old'] = $values['forum'];
		}
	}
	if (!$error) {
		// Mettre à jour le projet
		sqlExecute ("UPDATE projets SET
			titre=".$db->quote($values['titre']).",
			description=".$db->quote($values['description']).",
			attente=".$db->quote($values['attente']).",
			ressources=".$db->quote($values['ressources']).",
			initiative=".$db->quote($values['initiative']).",
			demarches=".$db->quote($values['demarches']).",
			statut=".$db->quote($values['statut'])."
			WHERE id=".$id
		);
		if ($values['statut'] == "accepte") {
			// Créer le demandeur comme premier adhérent
			sqlExecute("INSERT INTO adhesions VALUES ('".$values['email']."','".$id."','Je suis l\'initiateur de ce forum.')");
		}
		// Vérifier et charger les fichiers
		$idProprietaire = $id;
		if (isset($_FILES['image']) and $_FILES['image']['name'] != "") {
			$fichierDocPermis = false;
			// Image d'illustration
			$fichierEnvoye = $_FILES['image'];
			require $_SERVER['DOCUMENT_ROOT']."/includes/chargerFichier.php";
			if (!$error) {
				// Modifier son rôle
				$fichiersCharges[$nomFichier] = "illustration";
			}
		}
		if (!$error) {
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
		if (isset($fichiersCharges)) {
			if ($error) {
				// Supprimer les fichiers chargés
				foreach ($fichiersCharges as $nomFichier=>$typeFichier) {
					unlink($_SERVER['DOCUMENT_ROOT'].MEDIAS_FOLDER.$nomFichier);
				}
			} else {
				// Créer les fichiers
				foreach ($fichiersCharges as $nomFichier=>$typeFichier) {
					sqlExecute("INSERT INTO medias (fichier,proprietaire,role) VALUES (".$db->quote($nomFichier).",'".$id."','".$typeFichier."')");
				}
			}
		}
		$message = "Projet modifié.";
		if (!$error and $values['statut'] == "accepte" and $values['envoi'] == "1") {
			// Avertir le demandeur
			require $_SERVER['DOCUMENT_ROOT']."/includes/prepareMail.php";
			$mail->AddAddress($values['email']);
			$mail->Subject = utf8_decode(SITE_TITLE." : votre projet ".$values['titre']." est accepté");
			$body = "<P>Votre projet <strong>".$values['titre']."</strong> a été accepté par les modérateurs du site ".SITE_TITLE.".</P>
				<P>Vous pouvez le voir  à cette adresse : <A href='http://".$_SERVER['SERVER_NAME']."/projet/?id=".$id."'>".$_SERVER['SERVER_NAME']."/projet/?id=".$id."</A>.</P>";
			$mail->Body = utf8_decode($body);
			if (TESTING) {
				print "<DIV class='mail-body-test'>".$body."</DIV>";
			} elseif (!$mail->send()) {
				print "Oups ! Erreur technique lors de l'envoi !<br>".$mail->ErrorInfo;
				exit;
			}
			$message .= "<br>Email envoyé au demandeur.";
		}
		$action = "completed";
	}
}

if ($action == "edit" or $action == "deleteMedia") {
	$id = $_GET['id'];
	$results = sqlExecute("SELECT * FROM projets, personnes WHERE id='".$id."' AND demandeur=email");
	if ($row = $results->fetch()) {
		$values = $row;
		if ($values['statut'] == "forum") {
			// Chercher le nom du forum
			$results = sqlExecute("SELECT * FROM forums WHERE projet=".$id);
			if ($row = $results->fetch()) {
				$values['forum'] = $row['nom'];
				$values['forum_old'] = $row['nom'];
			} else {
				$values['forum'] = "Inconnu !?";
			}
		} else {
			$values['forum'] = "";
			$values['forum_old'] = "";
		}
	} else {
		// Sans id valable, page d'accueil du site !
		header("Location: /");
	}
	$values['envoi'] = "0";
} else {
	// Retrouver les infos du demandeur
	$results = sqlExecute("SELECT * FROM personnes WHERE email='".$values['email']."'");
	$row = $results->fetch();
	$values['nom'] = $row['nom'];
	$values['prenom'] = $row['prenom'];
	$values['telephone'] = $row['telephone'];
	$values['rue'] = $row['rue'];
	$values['code_postal'] = $row['code_postal'];
	$values['commune'] = $row['commune'];
	$values['code_confirmation'] = $row['code_confirmation'];
}

// Demandeur confirmé ?
if ($values['code_confirmation'] == "") {
	$inscriptionConfirmee = true;
} else {
	$inscriptionConfirmee = false;
}

if ($action == "deleteMedia") {
	// Suppression d'un média
	$fichier = $_GET['fichier'];
	sqlExecute("DELETE FROM medias WHERE fichier=".$db->quote($fichier));
	unlink($_SERVER['DOCUMENT_ROOT'].MEDIAS_FOLDER.$fichier);
	$action = "completed";
	$message = $fichier." supprim&eacute;.";
}

// Médias attachés
$medias = array();
$rows = sqlExecute("SELECT * FROM medias WHERE proprietaire='".$id."'");
while ($row = $rows->fetch()) {
	if ($row['role'] == "illustration") {
		$illustration = $row;
	} else {
		$medias[] = $row;
	}
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administration | <?= SITE_TITLE ?></title>
    
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
	<script src="/js/init.js"></script>
    <link href="/css/default.css" rel="stylesheet">
    <link href="/css/font-awesome.min.css" rel="stylesheet">
	<script type="text/javascript">
	jQuery(document).ready(function(){
		// Montrer l'input forum si le statut est "forum"
		$("[name='statut']").change(function(){
			if ($(this).val() == "forum") {
				$("#InputForum").show();
			} else {
				$("#InputForum").hide();
				$("#InputForum").val("");
			}
			if ($(this).val() == "accepte") {
				$("#inputAccepte").show();
			} else {
				$("#inputAccepte").hide();
				$("[name=envoi][value=0]").click();
			}
		});
		// Initialiser
		if ($("[name='statut']:checked").val() != "accepte") {
			$("#inputAccepte").hide();
		}
	});
	</script>
</head>

<body class="admin">

<?php include "nav.php"; ?>

<DIV class="container">
	<DIV class="row">
		<DIV class="col-sm-12">
			<?php if ($action == "completed") { ?>
			<P class="alert alert-success"><?php echo $message ?></P>
			<?php } elseif ($error) { ?>
			<P class="alert alert-danger"><?php echo $message ?></P>
			<?php } ?>
			<?php if ($warning) { ?>
			<P class="alert alert-warning"><?php echo $messageWarning ?></P>
			<?php } ?>
		</DIV>
		<form method="post" enctype="multipart/form-data">
			<DIV class="col-md-5">
				<DIV class="panel panel-primary">
					<DIV class="panel-heading">
						<H2 class="panel-title">Statuer le projet</H2>
					</DIV>
					<DIV class="panel-body">
						<P>Toutes les modifications faites dans les zones ouvertes seront apportées en même temps que le statut.</P>
						<?php if (!$inscriptionConfirmee) { ?>
						<P class="alert alert-warning">L'inscription du demandeur n'est pas confirmée : accepter maintenant ce projet pourrait donner une situation instable.</P>
						<?php } ?>
						<div class="radio">
							<label>
								<input type="radio" name="statut" value="nouveau" <?php if ($values['statut'] == "nouveau") echo "checked" ?>>
								Nouveau
							</label>
						</div>
						<div class="radio">
							<label>
								<input type="radio" name="statut" value="rejete" <?php if ($values['statut'] == "rejete") echo "checked" ?>>
								Rejeté
							</label>
						</div>
						<div class="radio">
							<label>
								<input type="radio" name="statut" value="accepte" <?php if ($values['statut'] == "accepte") echo "checked" ?>>
								Accepté
							</label>
						</div>
						<div class="radio">
							<label>
								<input type="radio" name="statut" value="forum" <?php if ($values['statut'] == "forum") echo "checked" ?>>
								&Eacute;lu en forum
							</label>
						</div>
						<div id="inputAccepte" class="form-group">
							<label>Projet accepté : avertir le demandeur du projet ?</label><BR>
							<label class="radio-inline">
								<input type="radio" name="envoi" value="0" <?php if ($values['envoi'] == "0") { ?>checked<?php } ?>> Non
							</label>
							<label class="radio-inline">
								<input type="radio" name="envoi" value="1" <?php if ($values['envoi'] == "1") { ?>checked<?php } ?>> Oui
							</label>
						</div>
						<div class="form-group" id="InputForum" <?php if ($values['statut'] != "forum"){ ?>style="display:none;"<?php } ?>>
							<label for="forum">Nom de page du forum</label>
							<p>Donnez le nom du r&eacute;pertoire du forum, sans espace, accents, caractères majuscules. Exemple : parc_leopold.<br>
							Ce nom correspond &agrave; un r&eacute;pertoire &agrave; cr&eacute;er dans le dossier &quot;forums&quot; (au pluriel !) par le webmaster, comme ceci&nbsp;: /forums/<u>parc_leopold</u>/</p>
							<input type="text" name="forum" class="form-control" placeholder="nom_du_forum" value="<?php echo $values['forum'] ?>">
						</div>
						<INPUT type="hidden" name="id" value="<?php echo $id ?>">
						<INPUT type="hidden" name="email" value="<?php echo $values['email'] ?>">
						<INPUT type="hidden" name="forum_old" value="<?php echo $values['forum_old'] ?>">
						<INPUT type="hidden" name="action" value="modifier">
						<DIV class="btn-group">
							<BUTTON type="submit" class="btn btn-success"><SPAN class="glyphicon glyphicon-save"></SPAN>&nbsp;&nbsp;&nbsp;Enregistrer</BUTTON>
							<A href="projets.php" class="btn btn-default"><SPAN class="glyphicon glyphicon-chevron-left"></SPAN>&nbsp;&nbsp;&nbsp;Retour</A>
						</DIV>
					</DIV>
				</DIV>
				<DIV class="panel panel-default">
					<DIV class="panel-heading">
						<H2 class="panel-title">Médias</H2>
					</DIV>
					<DIV class="panel-body">
						<div class="form-group">
							<label for="image">Image d'illustration</label>
							<?php if (isset($illustration)) { ?>
							<P>
								<A href="<?php echo MEDIAS_FOLDER.$illustration['fichier'] ?>" target="_blank"><?php echo $illustration['fichier'] ?></A>
								<A href="?action=deleteMedia&fichier=<?php echo $illustration['fichier'] ?>&id=<?php echo $id ?>" class="btn btn-default btn-sm _delete" label-confirm="<?php echo $illustration['fichier'] ?>"><span class="glyphicon glyphicon-trash"></span></A>
							</P>
							<INPUT name="image" type="file" class="form-control" disabled title="Supprimez l'illustration actuelle pour en recharger une autre">
							<?php } else { ?>
							<INPUT name="image" type="file" class="form-control">
							<?php } ?>
						</div>
						<div class="form-group">
							<?php foreach($medias as $media) { ?>
								<A href="<?php echo MEDIAS_FOLDER.$media['fichier'] ?>" target="_blank"><?php echo $media['fichier'] ?></A>
								<A href="?action=deleteMedia&fichier=<?php echo $media['fichier'] ?>&id=<?php echo $id ?>" class="btn btn-default btn-sm _delete" label-confirm="<?php echo $media['fichier'] ?>"><span class="glyphicon glyphicon-trash"></span></A>
								<br>
							<?php } ?>
							<label for="media">Documents</label>
							<INPUT name="media[]" type="file" class="form-control">
							<INPUT name="media[]" type="file" class="form-control">
							<INPUT name="media[]" type="file" class="form-control">
						</div>
					</DIV>
				</DIV>
			</DIV>
			<DIV class="col-md-7">
				<DIV class="panel panel-default">
					<DIV class="panel-heading">
						<H2 class="panel-title">Projet</H2>
					</DIV>
					<DIV class="panel-body">
						<div class="form-group">
							<label>Soumissionnaire</label>
							<p class="form-control-static">
								<a href="personnes.php?action=edit&id=<?php echo $values['email'] ?>" class="btn btn-default btn-sm" title="Modifier cette personne"><?php echo $values['prenom']." ".$values['nom'] ?></a>
								<?php if (!$inscriptionConfirmee) { ?><span class="label label-danger">Non confirmé</span><?php } ?>
								<span class="glyphicon glyphicon-envelope"></span> <a href="mailto:<?php echo $values['email'] ?>"><?php echo $values['email'] ?></a>
								<span class="glyphicon glyphicon-phone"></span> <?php echo $values['telephone'] ?>
								<i class="fa fa-home" aria-hidden="true"></i> <?php echo $values['rue']." - ".$values['code_postal']." ".$values['commune'] ?>
							</p>
						</div>
						<?php include $_SERVER['DOCUMENT_ROOT']."/includes/formProjet.php" ?>
					</DIV>
				</DIV>
			</DIV>
		</form>
	</DIV>
</DIV>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>
</body>
</html>
