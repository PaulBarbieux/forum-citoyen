<?php
require $_SERVER['DOCUMENT_ROOT']."/includes/init.php";

$error = false;

if ($action == "modifier") {
	// Modifier les données d'une personne
	$values = $_POST;
	$id = $_POST['id'];
	$values = $_POST;
	require $_SERVER['DOCUMENT_ROOT']."/includes/personneValidation.php";
	if (!$error) {
		if (sqlExecute("UPDATE personnes SET
			email=".$db->quote($values['email']).",
			nom=".$db->quote($values['nom']).",
			prenom=".$db->quote($values['prenom']).",
			presentation=".$db->quote($values['presentation']).",
			telephone=".$db->quote($values['telephone']).",
			code_postal=".$db->quote($values['code_postal']).",
			commune=".$db->quote($values['commune']).",
			rue=".$db->quote($values['rue']).",
			manifeste=".$values['manifesteBool']."
			WHERE email='".$id."'"
		) == DUPLICATE_KEY) {
			$error = true;
			$message = "Cet adresse email existe déjà.";
		} else {
			if ($values['passe'] != "") {
				// Modifier le mot de passe
				sqlExecute("UPDATE personnes SET passe=".$db->quote(crypt($values['passe']))." WHERE email='".$id."'");
			}
			if ($id != $values['email']) {
				// Email modifié : mettre à jour cette référence dans les autres tables
				sqlExecute("UPDATE projets SET demandeur='".$values['email']."' WHERE demandeur='".$id."'");
				sqlExecute("UPDATE adhesions SET email='".$values['email']."' WHERE email='".$id."'");
				sqlExecute("UPDATE idees SET email='".$values['email']."' WHERE email='".$id."'");
			}
			$action = "completed";
			$message = "Personne modifiée.";
		}
	}
} elseif ($action == "confirm") {
	$codeConfirmation = $_GET['code'];
	sqlExecute("UPDATE personnes SET code_confirmation=NULL where code_confirmation=".$db->quote($codeConfirmation));
	$action = "completed";
	$message = "Inscription confirmée";
} elseif ($action == "delete") {
	// Supprimer la personne
	$id = $_GET['id'];
	sqlExecute("DELETE FROM adhesions WHERE email='".$id."'");
	sqlExecute("DELETE FROM personnes WHERE email='".$id."'");
	$action = "completed";
	$message = "Personne <strong>".$id."</strong> supprimée.";
} elseif ($action == "admin") {
	// Elire une personne en administrateur
	$id = $_GET['id'];
	sqlExecute("UPDATE personnes SET role='admin' WHERE email='".$id."' AND code_confirmation IS NULL");
	$action = "completed";
	$message = "Personne <strong>".$id."</strong> élue en administrateur.";
} elseif ($action == "unAdmin") {
	// Elire une personne en administrateur
	$id = $_GET['id'];
	sqlExecute("UPDATE personnes SET role=NULL WHERE email='".$id."'");
	$action = "completed";
	$message = "Personne <strong>".$id."</strong> déchue d'administrateur.";
}

// Liste de toutes les personnes
$personnes = array();
$rows = sqlExecute("SELECT * FROM personnes ORDER BY date_inscription DESC");
while($row = $rows->fetch()) {
	$personnes[$row['email']] = $row;
	$personnes[$row['email']]['projets'] = array();
	$personnes[$row['email']]['forums'] = array();
	$personnes[$row['email']]['adhesions'] = array();
}
if ($action == "edit") {
	$id = $_GET['id'];
	if (isset($personnes[$id])) {
		$values = $personnes[$id];
	} else {
		// Sans id valable, hors d'ici !
		header("Location: /");
	}
}
// Liste des projets par demandeur
$rows = sqlExecute("SELECT * FROM projets ORDER BY demandeur");
while($row = $rows->fetch()) {
	$personnes[$row['demandeur']]['projets'][$row['id']] = $row['titre'];
}
// Liste des forums souscrits
$rows = sqlExecute("SELECT A.email,F.titre,F.nom FROM adhesions A, projets P, forums F WHERE A.projet=P.id AND P.statut='forum' AND F.projet=P.id ORDER BY A.email");
while($row = $rows->fetch()) {
	$personnes[$row['email']]['forums'][$row['nom']] = $row['titre'];
}
// Liste des projets souscrits
$rows = sqlExecute("SELECT * FROM adhesions, projets WHERE projet=id AND statut !='forum' ORDER BY email");
while($row = $rows->fetch()) {
	$personnes[$row['email']]['adhesions'][$row['id']] = $row['titre'];
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
		<DIV class="col-md-12">
			<?php if ($action == "completed") { ?>
			<P class="alert alert-success"><?php echo $message ?></P>
			<?php } elseif ($error) { ?>
			<P class="alert alert-danger"><?php echo $message ?></P>
			<?php } ?>
		</DIV>
		<?php if ($action == "edit" or $action == "modifier") { ?>
		<DIV class="col-lg-6 col-md-8 col-sm-12">
			<DIV class="panel panel-primary">
				<DIV class="panel-heading">
					<H2 class="panel-title"><?php echo $values['prenom']." ".$values['nom'] ?></H2>
				</DIV>
				<DIV class="panel-body">
					<?php if ($values['code_confirmation'] != "") { ?><span class="label label-danger">Non confirmé</span><?php } ?>
					<form method="post">
						<?php include $_SERVER['DOCUMENT_ROOT']."/includes/formPersonne.php" ?>
						<INPUT type="hidden" name="id" value="<?php echo $values['email'] ?>">
						<INPUT type="hidden" name="code_confirmation" value="<?php echo $values['code_confirmation'] ?>">
						<INPUT type="hidden" name="action" value="modifier">
						<DIV class="btn-group">
							<BUTTON type="submit" class="btn btn-success"><SPAN class="glyphicon glyphicon-save"></SPAN>&nbsp;&nbsp;&nbsp;Enregistrer</BUTTON>
							<A href="personnes.php" class="btn btn-default">Annuler</A>
						</DIV>
					</form>
				</DIV>
			</DIV>
		</DIV>
		<?php } else { ?>
		<DIV class="col-sm-12">
			<TABLE class="table table-striped">
				<THEAD>
					<TR>
						<TH>Inscription</TH>
						<TH>Nom</TH>
						<TH>Email</TH>
						<TH>Projets soumissionnés</TH>
						<TH>Forums souscrits</TH>
						<TH>Projets souscrits</TH>
						<TH>Action</TH>
					</TR>
				</THEAD>
				<TBODY>
				<?php foreach ($personnes as $id=>$personne) { ?>
					<TR class="<?php if ($personne['role'] == "admin") echo "success" ?>">
						<TD><?php echo $personne['date_inscription'] ?></TD>
						<TD><?php echo $personne['nom']." ".$personne['prenom'] ?>
							<?php if ($personne['code_confirmation'] != "") { ?><span class="label label-danger">Non confirmé</span><?php } ?></TD>
						<TD><?php echo $personne['email'] ?></TD>
						<TD><div class="btn-group-vertical">
							<?php foreach ($personne['projets'] as $idProjet=>$projet) { ?>
							<A href="/projet/?id=<?php echo $idProjet ?>" class="btn btn-default btn-sm"><?php echo $projet ?></A>
							<?php } ?>
							</div>
						</TD>
						<TD><div class="btn-group-vertical">
							<?php foreach ($personne['forums'] as $nomForum=>$titre) { ?>
							<A href="/forum/?nom=<?php echo $nomForum ?>" class="btn btn-default btn-sm"><?php echo $titre ?></A>
							<?php } ?>
							</div>
						</TD>
						<TD><div class="btn-group-vertical">
							<?php foreach ($personne['adhesions'] as $idProjet=>$projet) { ?>
							<A href="/projet/?id=<?php echo $idProjet ?>" class="btn btn-default btn-sm"><?php echo $projet ?></A>
							<?php } ?>
							</div>
						</TD>
						<TD><DIV class="btn-group">
							<A href="?action=edit&id=<?php echo $id ?>" class="btn btn-sm btn-default" title="Modifier cette personne"><SPAN class="glyphicon glyphicon-pencil"></SPAN></A>
							<?php if (count($personne['projets']) == 0) { ?>
								<A href="?action=delete&id=<?php echo $id ?>" class="btn btn-sm btn-default _delete" title="Supprimer" label-confirm="<?php echo $personne['nom'].", ".$personne['prenom'] ?>"><SPAN class="glyphicon glyphicon-trash"></SPAN></A>
							<?php } else { ?>
								<A class="btn btn-sm btn-default disabled"><SPAN class="glyphicon glyphicon-trash"></SPAN></A>
							<?php } ?>
							<?php if ($personne['code_confirmation'] != "") { ?>
								<A href="?action=confirm&code=<?php echo $personne['code_confirmation'] ?>" class="btn btn-sm btn-default" title="Confirmer l'inscription"><SPAN class="glyphicon glyphicon-ok"></SPAN></A>
							<?php } ?>
							<?php if ($personne['role'] == "admin") { ?>
								<?php if ($personne['email'] == $_SESSION['mon_profil']['email']) { ?>
								<A  class="btn btn-sm btn-default disabled" ><SPAN class="glyphicon glyphicon-star-empty"></SPAN></A>
								<?php } else { ?>
								<A href="?action=unAdmin&id=<?php echo $id ?>" class="btn btn-sm btn-default" title="Retirer le rôle d'administrateur"><SPAN class="glyphicon glyphicon-star-empty"></SPAN></A>
								<?php } ?>
							<?php } elseif ($personne['code_confirmation'] == "")  { ?>
								<A href="?action=admin&id=<?php echo $id ?>" class="btn btn-sm btn-default" title="Elire en administrateur"><SPAN class="glyphicon glyphicon-star"></SPAN></A>
							<?php } ?>
							</DIV>
						</TD>
					</TR>
				<?php } ?>
				</TBODY>
			</TABLE>
		</DIV>
		<?php } ?>
	</DIV>
</DIV>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>
</body>
</html>
