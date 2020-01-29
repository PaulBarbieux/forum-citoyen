<?php
require $_SERVER['DOCUMENT_ROOT']."/includes/init.php";

if (isset($_GET['archives'])) {
	$voirArchives = true;
} else {
	$voirArchives = false;
}

if ($action == "modifier") {
	$values = $_POST;
	$lien = ($values['lien'] == "" ? "NULL" : "'".$values['lien']."'"); // Une nouvelle générale a un lien null
	if ($values['id'] == "") {
		// Données envoyées pour la création d'une nouvelle
		$values['id'] = time();
		sqlExecute("INSERT INTO nouvelles VALUES ('".$values['id']."',".$lien.",".$values['archive'].",STR_TO_DATE('".$values['date']."','%d/%m/%Y'),".
			$db->quote($values['titre']).",".$db->quote($values['texte']).",0)");
		$action = "completed";
		$message = "Nouvelle créée.";
	} else {
		// Données envoyées pour la mise à jour d'une nouvelle
		sqlExecute("UPDATE nouvelles SET lien=".$lien.", archive=".$values['archive'].", date=STR_TO_DATE('".$values['date']."','%d/%m/%Y'), titre=".
			$db->quote($values['titre']).", texte=".$db->quote($values['texte'])." WHERE id='".$values['id']."'");
		$action = "completed";
		$message = "Nouvelle modifiée.";
	}
	if ($_FILES['illustration']['name'] != "") {
		// Image d'illustration
		$idProprietaire = $values['id'];
		$fichierEnvoye = $_FILES['illustration'];
		$fichierDocPermis = false;
		require $_SERVER['DOCUMENT_ROOT']."/includes/chargerFichier.php";
		if (!$error) {
			// Suppression de l'éventuelle ancienne illustration (suppression du fichier à programmer !)
			sqlExecute("DELETE FROM medias WHERE proprietaire='".$idProprietaire."' AND role='illustration'");
			sqlExecute("INSERT INTO medias (fichier,proprietaire,role) VALUES (".$db->quote($nomFichier).",'".$idProprietaire."','illustration')");
		} else {
			$messsageWarning = "La nouvelle a malgré tout été enregistrée.";
		}
	}
} elseif ($action == "delete") {
	sqlExecute("DELETE FROM nouvelles WHERE id='".$_GET['id']."'");
	$rows = sqlExecute("SELECT * FROM medias WHERE proprietaire='".$_GET['id']."'");
	$mediasNews = array();
	while ($row = $rows->fetch()) {
		$mediasNews[] = "'".$row['fichier']."'";
		unlink($_SERVER['DOCUMENT_ROOT'].MEDIAS_FOLDER.$row['fichier']);
	}
	if (count($mediasNews) > 0) {
		sqlExecute("DELETE FROM medias WHERE fichier IN (".implode(",",$mediasNews).")");
	}
	$action = "completed";
	$message = "Nouvelle supprimée avec ses éventuels médias.";
}

// Liste des nouvelles
$nouvelles = array();
if ($voirArchives) {
	$rows = sqlExecute("SELECT * FROM nouvelles WHERE archive='1' ORDER BY date DESC, id DESC");
} else {
	$rows = sqlExecute("SELECT * FROM nouvelles WHERE archive!='1' ORDER BY date DESC, id DESC");
}
while ($row = $rows->fetch()) {
	$nouvelles[$row['id']] = $row;
	$nouvelles[$row['id']]['date'] = transformDate($row['date'],"dd/mm/yyyy");
	$nouvelles[$row['id']]['illustration'] = "";
	if ($row['lien'] == "") {
		$nouvelles[$row['id']]['lienTitre'] = "";
	} else {
		if (isset($FORUMS[$row['lien']])) {
			$nouvelles[$row['id']]['lienTitre'] = "Forum : ".$FORUMS[$row['lien']]['titre'];
		} else {
			$nouvelles[$row['id']]['lienTitre'] = "Projet : ".$PROJETS[$row['lien']]['titre'];
		}
	}
}
// Illustrations
$rows = sqlExecute("SELECT * FROM medias WHERE role='illustration'");
while ($row = $rows->fetch()) {
	if (isset($nouvelles[$row['proprietaire']])) {
		$nouvelles[$row['proprietaire']]['illustration'] = $row['fichier'];
	}
}

switch ($action) {
	case "create" :
		// Création d'une nouvelle
		$id = "";
		break;
	case "edit" :
		// Modification d'une nouvelle
		$id = $_GET['id'];
		if (isset($nouvelles[$id])) {
			$values = $nouvelles[$id];
		} else {
			$error = true;
			$message = "Id de nouvelle inconnu.";
			$action = "init";
		}
		break;
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
	<SCRIPT src="/js/init.js"></SCRIPT>
    <script src="/js/bootstrap.min.js"></script>
	<script src="/js/pgwcookie.min.js"></script>
    <link href="/css/default.css" rel="stylesheet">
	<?php include $_SERVER['DOCUMENT_ROOT']."/includes/tinyMce.php"; ?>
</head>

<body class="admin">

<?php include "nav.php"; ?>

<DIV class="container-fluid">
	<DIV class="row">
		<DIV class="col-sm-12">
			<?php include $_SERVER['DOCUMENT_ROOT']."/includes/alerts.php"; ?>
			<UL class="nav nav-tabs">
				<LI <?php if (!$voirArchives) echo "class='active'" ?>><A href="index.php">Actualiés</A></LI>
				<LI <?php if ($voirArchives) echo "class='active'" ?>><A href="index.php?archives">Archives</A></LI>
			</UL>
		</DIV>
		<?php if ($action == "edit" or $action == "create") { ?>
		<DIV class="col-lg-4 col-md-5 col-sm-12">
			<DIV class="panel panel-primary">
				<DIV class="panel-heading">
					<H2 class="panel-title"><?php echo ($action == "edit" ? "Modifier une nouvelle" : "Créer une nouvelle") ?></H2>
				</DIV>
				<DIV class="panel-body">
					<FORM method="post" enctype="multipart/form-data">
						<?php include $_SERVER['DOCUMENT_ROOT']."/includes/formNews.php"; ?>
						<INPUT type="hidden" name="action" value="modifier">
						<INPUT type="hidden" name="id" value="<?php echo $id ?>">
						<DIV class="btn-group">
							<BUTTON type="submit" class="btn btn-success"><SPAN class="glyphicon glyphicon-save"></SPAN>&nbsp;&nbsp;&nbsp;Enregistrer</BUTTON>
							<A href="?back" class="btn btn-danger"><SPAN class="glyphicon glyphicon-chevron-left"></SPAN> Retour</A>
						</DIV>
					</FORM>
				</DIV>
			</DIV>
		</DIV>
		<DIV class="col-lg-8 col-md-7 col-sm-12">
		<?php } else { ?>
		<DIV class="col-sm-12">
			<P><A href="?action=create" class="btn btn-primary"><SPAN class="glyphicon glyphicon-plus"></SPAN> Créer une nouvelle</A></P>
		<?php } ?>
			<TABLE class="table table-striped">
				<THEAD>
					<TR>
						<TH>Date</TH>
						<TH>Titre</TH>
						<TH>Illustration</TH>
						<TH>Lien</TH>
						<TH></TH>
					</TR>
				</THEAD>
				<TBODY>
				<?php foreach ($nouvelles as $id=>$nouvelle) { ?>
					<TR>
						<TD><?php echo $nouvelle['date'] ?></TD>
						<TD><?php echo $nouvelle['titre'] ?></TD>
						<TD><?php echo $nouvelle['illustration'] ?></TD>
						<TD><?php echo $nouvelle['lienTitre'] ?></TD>
						<TD><DIV class="btn-group">
								<A href="?action=edit&id=<?php echo $id ?>" class="btn btn-sm btn-default" title="Modifier"><SPAN class="glyphicon glyphicon-pencil"></SPAN></A>
								<A href="?action=delete&id=<?php echo $id ?>" class="btn btn-sm btn-default _delete" title="Supprimer" label-confirm="<?php echo $nouvelle['titre'] ?>"><SPAN class="glyphicon glyphicon-trash"></SPAN></A>
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
