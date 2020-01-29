<?php
require $_SERVER['DOCUMENT_ROOT']."/includes/init.php";

if ($action == "delete") {
	// Suppression d'un projet
	$id = $_GET['id'];
	// Suppression des médias
	$rows = sqlExecute("SELECT * FROM medias WHERE proprietaire='".$id."'");
	while ($row = $rows->fetch()) {
		unlink ($_SERVER['DOCUMENT_ROOT'].MEDIAS_FOLDER.$row['fichier']);
	}
	sqlExecute("DELETE FROM medias WHERE proprietaire='".$id."'");
	// Suppression des adhésions
	sqlExecute("DELETE FROM adhesions WHERE projet='".$id."'");
	// Suppression du projet
	sqlExecute("DELETE FROM projets WHERE id='".$id."'");
	$action = "completed";
	$message = "Projet <strong>".$id."</strong> supprimé.";
}

// Liste des projets
$projets = array();
$rows = sqlExecute("SELECT * FROM projets, personnes WHERE demandeur=email ORDER BY id DESC");
while ($row = $rows->fetch()) {
	$projets[$row['id']] = $row;
	$projets[$row['id']]['medias'] = 0;
}
// Médias par projet
$rows = sqlExecute("SELECT proprietaire, count(*) count FROM medias GROUP BY proprietaire");
while ($row = $rows->fetch()) {
	if (isset($projets[$row['proprietaire']])) {
		$projets[$row['proprietaire']]['medias'] = $row['count'];
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
</head>

<body class="admin">

<?php include "nav.php"; ?>

<DIV class="container-fluid">
	<DIV class="row">
		<DIV class="col-sm-12">
			<?php require $_SERVER['DOCUMENT_ROOT']."/includes/alerts.php"; ?>
			<h1>Liste des projets</h1>
			<TABLE class="table table-striped">
				<THEAD>
					<TR>
						<TH>Date</TH>
						<TH>Soumissionaire</TH>
						<TH>Titre</TH>
						<TH>Médias</TH>
						<TH>Statut</TH>
						<TH>Action</TH>
					</TR>
				</THEAD>
				<TBODY>
				<?php foreach ($projets as $id=>$projet) { ?>
					<TR>
						<TD><?php echo date("d/m/Y",$id) ?></TD>
						<TD><A href="personnes.php?action=edit&id=<?php echo $projet['email'] ?>" title="Modifier la fiche de cette personne"><?php echo $projet['prenom']." ".$projet['nom'] ?></a>
							<?php if ($projet['code_confirmation'] != "") { ?><span class="label label-danger">Non confirmé</span><?php } ?></TD>
						<TD><?php echo $projet['titre'] ?></TD>
						<TD><?php echo $projet['medias'] ?></TD>
						<TD><span class="label label-<?php echo classStatus($projet['statut']) ?>"><?php echo labelStatus($projet['statut']) ?></span></TD>
						<TD><DIV class="btn-group">
							<A href="projet.php?action=edit&id=<?php echo $id ?>" class="btn btn-sm btn-default" title="Modifier et/ou statuer"><SPAN class="glyphicon glyphicon-pencil"></SPAN></A>
							<?php if ($projet['statut'] != "forum") { ?>
							<A href="?action=delete&id=<?php echo $id ?>" class="btn btn-sm btn-default _delete" title="Supprimer" label-confirm="<?php echo $projet['titre'] ?>"><SPAN class="glyphicon glyphicon-trash"></SPAN></A>
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
