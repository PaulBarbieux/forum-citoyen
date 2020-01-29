<?php
require $_SERVER['DOCUMENT_ROOT']."/includes/init.php";

if ($action == "modifier") {
	$values = $_POST;
	// Fichier joint
	if ($_FILES['fichier']['name'] != "") {
		$fichierEnvoye = $_FILES['fichier'];
		if ($fichierEnvoye['error'] != 0) {
			$error = true;
			$message = "Désolé, le fichier ".$fichierEnvoye['name']." n'a pas pu être chargé. Les raisons peuvent être : fichier trop gros, format non accepté;, fichier inaccessible sur votre ordinateur.";
		} else {
			// Vérifier le type
			if (strtolower(pathinfo($fichierEnvoye['name'],PATHINFO_EXTENSION)) != "pdf") {
				$error = true;
				$message = "Seuls les PDF sonc acceptés. Si c'est une image, convertissez-la en PDF.";
			} else {
				// Chargement du fchier
				$nomFichier = goodFileName($fichierEnvoye['name']);
				if (move_uploaded_file($fichierEnvoye['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/_presse/".$nomFichier) === false) {
					// Erreur au chargement
					$error = true;
					switch ($fichierEnvoye['error']) {
						case UPLOAD_ERR_INI_SIZE :
						case UPLOAD_ERR_FORM_SIZE :
							$message = "Le fichier <strong>".$fichierEnvoye['name']."</strong> est trop gros.";
							break;
						default :
							$message = "Une erreur technique est arrivée au chargement du fichier <strong>".$fichierEnvoye['name']."</strong> (erreur ".$fichierEnvoye['error'].").";
					}
				} else {
					$values['lien'] = "/_presse/".$nomFichier;
				}
			}
		}
	}
	if (!$error) {
		// Vérification du lien
		if ($values['lien'] == "") {
			$error = true;
			$message = "Veuillez donner un lien ou charger un fichier.";
		} else {
			if (substr($values['lien'],0,9) != "/_presse/" and substr($values['lien'],0,4) != "http") {
				$values['lien'] = "http://".$values['lien'];
			}
		}
	}
	if (!$error) {
		if ($values['id'] == "") {
			// Données envoyées pour la création d'une presse
			$values['id'] = time();
			sqlExecute("INSERT INTO presse (id,date,source,titre,lien) 
				VALUES ('".$values['id']."',STR_TO_DATE('".$values['date']."','%d/%m/%Y'),".
				$db->quote($values['source']).",".$db->quote($values['titre']).",".$db->quote($values['lien']).")");
			$action = "completed";
			$message = "Presse créée.";
		} else {
			// Données envoyées pour la mise à jour d'une nouvelle
			sqlExecute("UPDATE presse SET 
				date=STR_TO_DATE('".$values['date']."','%d/%m/%Y'),
				source=".$db->quote($values['source']).",
				titre=".$db->quote($values['titre']).",
				lien=".$db->quote($values['lien'])."
				WHERE id='".$values['id']."'");
			$action = "completed";
			$message = "Presse modifiée.";
		}
	}
}

// Liste de la presse
$presse = array();
$rows = sqlExecute("SELECT * FROM presse ORDER BY date DESC");
while ($row = $rows->fetch()) {
	$presse[$row['id']] = $row;
	$presse[$row['id']]['date'] = transformDate($row['date'],"dd/mm/yyyy");
}

switch ($action) {
	case "create" :
		// Création d'une presse
		$values['id'] = "";
		break;
	case "edit" :
		// Modification d'une presse
		$id = $_GET['id'];
		if (isset($presse[$id])) {
			$values = $presse[$id];
		} else {
			$error = true;
			$message = "Id de presse inconnu.";
			$action = "init";
		}
		break;
	case "delete" :
		$id = $_GET['id'];
		$article = $presse[$id];
		if (substr($article['lien'],0,9) == "/_presse/") {
			// Supprimer le document lié
			unlink($_SERVER['DOCUMENT_ROOT'].$article['lien']);
		}
		sqlExecute("DELETE FROM presse WHERE id='".$id."'");
		unset($presse[$id]);
		$action = "completed";
		$message = "Presse supprimée.";
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
</head>

<body class="admin">

<?php include "nav.php"; ?>

<DIV class="container-fluid">
	<DIV class="row">
		<DIV class="col-sm-12">
			<?php include $_SERVER['DOCUMENT_ROOT']."/includes/alerts.php"; ?>
		</DIV>
		<?php if ($action == "edit" or $action == "create" or $error) { ?>
		<DIV class="col-lg-4 col-md-5 col-sm-12">
			<DIV class="panel panel-primary">
				<DIV class="panel-heading">
					<H2 class="panel-title"><?php echo ($action == "edit" ? "Modifier une presse" : "Créer une presse") ?></H2>
				</DIV>
				<DIV class="panel-body">
					<FORM method="post" enctype="multipart/form-data">
						<?php include $_SERVER['DOCUMENT_ROOT']."/includes/formPresse.php"; ?>
						<INPUT type="hidden" name="action" value="modifier">
						<INPUT type="hidden" name="id" value="<?php echo $values['id'] ?>">
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
			<P><A href="?action=create" class="btn btn-primary"><SPAN class="glyphicon glyphicon-plus"></SPAN> Créer un lien presse</A></P>
		<?php } ?>
			<TABLE class="table table-striped">
				<THEAD>
					<TR>
						<TH>Date</TH>
						<TH>Source</TH>
						<TH>Titre</TH>
						<TH>Lien</TH>
						<TH></TH>
					</TR>
				</THEAD>
				<TBODY>
				<?php foreach ($presse as $id=>$article) { ?>
					<TR>
						<TD><?php echo $article['date'] ?></TD>
						<TD><?php echo $article['source'] ?></TD>
						<TD><?php echo $article['titre'] ?></TD>
						<TD><A href="<?php echo $article['lien'] ?>" target="_blank"><?php echo $article['lien'] ?></A></TD>
						<TD><DIV class="btn-group">
								<A href="?action=edit&id=<?php echo $id ?>" class="btn btn-sm btn-default" title="Modifier"><SPAN class="glyphicon glyphicon-pencil"></SPAN></A>
								<A href="?action=delete&id=<?php echo $id ?>" class="btn btn-sm btn-default _delete" title="Supprimer" label-confirm="<?php echo $article['titre'] ?>"><SPAN class="glyphicon glyphicon-trash"></SPAN></A>
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
