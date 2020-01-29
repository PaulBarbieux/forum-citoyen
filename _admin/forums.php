<?php
require $_SERVER['DOCUMENT_ROOT']."/includes/init.php";

$error = false;

if ($action == "modifier") {
	// Modification des données du forum
	$nom_id = $_POST['nom_id'];
	$values = array();
	foreach ($_POST as $input=>$value) {
		if ($input != "introduction") {
			$value = trim(strip_tags($value));
		}
		$values[$input] = $value;
		switch ($input) {
			case "nom" :
			case "titre" :
			case "introduction" :
				if ($value == "") {
					$error = true;
					$message = "Veuillez remplir toutes les zones.";
				}
		}
	}
	if (!$error) {
		// Mettre à jour les données du forum
		if (sqlExecute("UPDATE forums SET 
				nom='".$values['nom']."', 
				titre=".$db->quote($values['titre']).",
				introduction=".$db->quote($values['introduction']).",
				statut='".$values['statut']."'
				WHERE nom='".$nom_id."'")
			  == DUPLICATE_KEY) {
			$error = true;
			$message = "Ce nom est déjà pris.";
		} else {
			if ($values['nom'] != $nom_id) {
				// Nom changé : mettre à jour les tables liées
				sqlExecute("UPDATE idees SET forum='".$values['nom']."' WHERE forum='".$nom_id."'");
			}
			$action = "completed";
			$message = "Forum <STRONG>".$values['titre']."</STRONG> modifié.";
			if ($values['statut'] == "actif" and $values['envoi'] == "1") {
				// Avertir les adhérents
				$emailAdherents = array();
				require $_SERVER['DOCUMENT_ROOT']."/includes/prepareMail.php";
				$result = sqlExecute("
					SELECT A.email, R.prenom, P.titre
					FROM adhesions A, personnes R, forums F, projets P
					WHERE F.nom='".$values['nom']."' AND F.projet=P.id AND F.projet=A.projet AND A.email=R.email AND (R.code_confirmation='' OR R.code_confirmation is NULL)");
				while ($row = $result->fetch()) {
					$mail->AddBCC($row['email']);
					$emailAdherents[] = $row['email'];
					$projetTitre = $row['titre'];
				}
				$mail->AddAddress(ADMIN_EMAIL,SITE_TITLE);
				$mail->Subject = utf8_decode(SITE_TITLE." : le projet ".$projetTitre." élu en forum");
				$body = "<P>Le projet <strong>".$projetTitre."</strong> a été élu en forum.</P>
					<P>Vous pouvez désormais soumettre des idées dans le forum <A href='http://".$_SERVER['SERVER_NAME']."/forum/?nom=".$values['nom']."'>".$values['titre']."</A>.</P>
					<P>Vous recevez cet email parce que vous avez adhéré à ce projet.</P>";
				$mail->Body = utf8_decode($body);
				if (TESTING) {
					print "<DIV class='mail-body-test'>".$body."</DIV>";
					$message .= "<br>Un email a été envoyé aux adhérents : ".implode(", ",$emailAdherents).".";
				} else {
					if (!$mail->send()) {
						print "Oups ! Erreur technique lors de l'envoi !<br>".$mail->ErrorInfo;
					} else {
						$message .= "<br>Un email a été envoyé aux adhérents : ".implode(", ",$emailAdherents).".";
					}
				}
			}
		}
	}
} elseif ($action == "delete") {
	// Supprimer un forum
	sqlExecute("DELETE FROM forums WHERE nom='".$_GET['nom']."'");
	$action = "completed";
	$message = "Forum <strong>".$_GET['nom']."</strong> supprimé.";
}

// Liste des forums.
$forums = array();
$projetsLies = 	array();
$rows = sqlExecute("SELECT * FROM forums ORDER BY nom");
while ($row = $rows->fetch()) {
	$forums[$row['nom']] = $row;
	$forums[$row['nom']]['idees'] = 0;
	$forums[$row['nom']]['titre_projet'] = "inconnu (".$row['projet'].")";
	$projets[$row['nom']] = $row['projet'];
}
if (count($projets) > 0) {
	// Chercher les idées liées
	$rows = sqlExecute("SELECT id,titre FROM projets WHERE id IN (".implode(",",$projets).")");
	while ($row = $rows->fetch()) {
		$nomForum = array_keys($projets, $row['id']);
		$forums[$nomForum[0]]['titre_projet'] = $row['titre'];
	}
}

// Compter les idées dans chaque forum
$rows = sqlExecute("SELECT count(*), forum FROM idees GROUP BY forum");
while ($row = $rows->fetch()) {
	$forums[$row['forum']]['idees'] = $row[0];
}

// Edition des données d'un forum
if ($action == "edit") {
	$nom = $_GET['nom'];
	$nom_id = $nom; // Le nom sert d'identifiant : conserver cette clé car le nom peut changer
	$values = $forums[$nom];
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
</head>

<body class="admin">

<?php include "nav.php"; ?>

<DIV class="container-fluid">
	<DIV class="row">
		<?php if ($error or $action == "completed") { ?>
		<DIV class="col-sm-12">
			<?php if ($action == "completed") { ?>
			<P class="alert alert-success"><?php echo $message ?></P>
			<?php } elseif ($error) { ?>
			<P class="alert alert-danger"><?php echo $message ?></P>
			<?php } ?>		
		</DIV>
		<?php } ?>
		<?php if ($action == "edit" or $action == "modifier") { ?>
		<?php
		include $_SERVER['DOCUMENT_ROOT']."/includes/tinyMce.php";
		if (!isset($values['nom'])) $values['nom'] = "";
		if (!isset($values['titre'])) $values['titre'] = "";
		if (!isset($values['introduction'])) $values['introduction'] = "";
		if (!isset($values['statut'])) $values['statut'] = "inactif";
		if (!isset($values['envoi'])) $values['envoi'] = "0";
		?>
		<SCRIPT type="text/javascript">
		jQuery(document).ready(function(){
			// Faire apparaître ou non l'option d'envoi suivant le statut
			$("[name='statut']").change(function(){
				if ($(this).val() == "inactif") {
					$("#inputActif").hide();
					$("[name=envoi][value=0]").click();
				} else {
					$("#inputActif").show();
				}
			});
			// Initialiser
			if ($("[name='statut']:checked").val() == "inactif") {
				$("#inputActif").hide();
			}
		});
		</SCRIPT>
		<DIV class="col-lg-4 col-md-5 col-sm-12">
			<DIV class="panel panel-primary">
				<DIV class="panel-heading">
					<H2 class="panel-title"><?php echo $values['titre'] ?></H2>
				</DIV>
				<DIV class="panel-body">
					<FORM method="post">
						<div class="form-group">
							<label for="nom">Nom de forum/r&eacute;pertoire</label>
							<input type="text" name="nom" class="form-control" required value="<?php echo $values['nom'] ?>">
						</div>
						<div class="form-group">
							<label for="titre">Titre</label>
							<input type="text" name="titre" class="form-control" required value="<?php echo $values['titre'] ?>">
						</div>
						<div class="form-group">
							<label for="introduction">Introduction</label>
							<textarea name="introduction" class="form-control tinymce" rows="10" placeholder="Petit texte affiché dans le bloc en page d'accueil du site. Code HTML non permis."><?php echo $values['introduction'] ?></textarea>
						</div>
						<div class="form-group">
							<label>Statut</label><BR>
							<label class="radio-inline">
								<input type="radio" id="statut" name="statut" value="inactif" <?php if ($values['statut'] == "inactif") { ?>checked<?php } ?>> Inactif
							</label>
							<label class="radio-inline">
								<input type="radio" id="statut" name="statut" value="actif" <?php if ($values['statut'] == "actif") { ?>checked<?php } ?>> actif
							</label>
						</div>
						<div id="inputActif" class="form-group">
							<label>Forum actif : avertir les adhérents de la création du forum ?</label><BR>
							<label class="radio-inline">
								<input type="radio" name="envoi" value="0" <?php if ($values['envoi'] == "0") { ?>checked<?php } ?>> Non
							</label>
							<label class="radio-inline">
								<input type="radio" name="envoi" value="1" <?php if ($values['envoi'] == "1") { ?>checked<?php } ?>> Oui
							</label>
						</div>
						<INPUT type="hidden" name="action" value="modifier">
						<INPUT type="hidden" name="nom_id" value="<?php echo $nom_id ?>">
						<P>Pour qu'un forum fonctionne, il doit &ecirc;tre &quot;actif&quot;, mais en plus :
							<UL>
								<LI>Un<strong> dossier portant le nom du forum</strong> doit exister dans le r&eacute;pertroie /forums/.</LI>
								<LI>Une page <strong>index.php</strong>, pr&eacute;sentant le forum, doit se trouver dans ce dossier.</LI>
								<LI>Si ce r&eacute;pertoire contient une image JPEG portant le nom <strong>cover.jpg</strong>, elle est automatiquement utilis&eacute;e comme illustraion en page d'accueil. </LI>
							</UL>
						</P>
						<DIV class="btn-group">
							<BUTTON type="submit" class="btn btn-success"><SPAN class="glyphicon glyphicon-save"></SPAN>&nbsp;&nbsp;&nbsp;Enregistrer</BUTTON>
							<A href="forums.php" class="btn btn-default">Annuler</A>
						</DIV>
					</FORM>
				</DIV>
			</DIV>
		</DIV>
		<DIV class="col-lg-8 col-md-7 col-sm-12">
		<?php } else { ?>
		<DIV class="col-sm-12">
		<?php } ?>
			<h1>Liste des forums</h1>
			<TABLE class="table table-striped">
				<THEAD>
					<TR>
						<TH>Titre</TH>
						<TH>Page</TH>
						<TH>Statut</TH>
						<TH>Idées</TH>
						<TH>Lié au projet</TH>
						<TH>Action</TH>
					</TR>
				</THEAD>
				<TBODY>
				<?php foreach ($forums as $nom=>$forum) { ?>
					<TR>
						<TD><?php echo $forum['titre'] ?></TD>
						<TD>/forums/<A href="/forum/?nom=<?php echo $nom ?>" title="Voir le forum"><?php echo $nom ?>/index.php</A></TD>
						<TD><span class="label label-<?php echo classStatus($forum['statut']) ?>"><?php echo $forum['statut'] ?></span></TD>
						<TD><?php echo $forum['idees'] ?></TD>
						<TD><A href="projet.php?action=edit&id=<?php echo $forum['projet'] ?>" title="Modifier le projet"><?php echo $forum['titre_projet'] ?></a></TD>
						<TD><DIV class="btn-group">
								<A href="?action=edit&nom=<?php echo $nom ?>" class="btn btn-sm btn-default" title="Modifier et/ou statuer"><SPAN class="glyphicon glyphicon-pencil"></SPAN></A>
								<?php if ($forum['idees'] == 0) { ?>
								<A href="?action=delete&nom=<?php echo $nom ?>" class="btn btn-sm btn-default _delete" title="Supprimer" label-confirm="<?php echo $nom ?>"><SPAN class="glyphicon glyphicon-trash"></SPAN></A>
								<?php } else { ?>
								<A class="btn btn-sm btn-default disabled" title="Supprimer les idées avant de pouvoir supprimer le forum"><SPAN class="glyphicon glyphicon-trash"></SPAN></A>
								<?php } ?>
							</DIV>
						</TD>
					</TR>
				<?php } ?>
				</TBODY>
			</TABLE>
		</DIV>
	</DIV>
</DIV>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>
</body>
</html>
