<?php
require $_SERVER['DOCUMENT_ROOT']."/includes/init.php";

if ($action == "deleteMedia") {
	// Suppression d'un média
	$fichier = $_GET['fichier'];
	sqlExecute("DELETE FROM medias WHERE fichier='".$fichier."'");
	unlink($_SERVER['DOCUMENT_ROOT'].MEDIAS_FOLDER.$fichier);
	$action = "completed";
	$message = $fichier." supprim&eacute;.";
} elseif ($action == "delete") {
	// Suppression d'une idée
	$idIdee = $_GET['id'];
	// Suppression des médias
	$rows = sqlExecute("SELECT fichier FROM medias WHERE proprietaire='".$idIdee."'");
	while ($row = $rows->fetch()) {
		unlink($_SERVER['DOCUMENT_ROOT'].MEDIAS_FOLDER.$row[0]);
	}
	sqlExecute("DELETE FROM medias WHERE proprietaire='".$idIdee."'");
	// Suppression de l'idée
	sqlExecute("DELETE FROM idees WHERE id='".$idIdee."'");
	// Pas de suppression des commentaires : le lien de suppression est désactivé s'il en existe. 
	// En cas de tricherie (URL tappée), les commentairs demeurent, orphelins.
	$action = "completed";
	$message = "Idéé ".$idIdee." supprimée, ainsi que ses éventuels médias.";
} elseif ($action == "updateCompleted") {
	// Rafraîchissement de page après màj d'une idée : le message est en session
	$message = $_SESSION['message'];
	$action = "completed";
}

// Liste des médias attachés
$medias = array();
$rows = sqlExecute("SELECT * FROM medias ORDER BY proprietaire");
while ($row = $rows->fetch()) {
	$medias[$row['proprietaire']][$row['fichier']] = $row;
}

// Compter les commentaires
$commentaires = array();
$rows = sqlExecute("SELECT conversation, count(*) count FROM commentaires GROUP BY conversation");
while ($row = $rows->fetch()) {
	$commentaires[$row['conversation']] = $row['count'];
}

// Liste des personnes (une personne peut avoir été supprimée, donc pas de fusion avec la sélection suivante)
$personnes = array();
$rows = sqlExecute("SELECT * FROM personnes");
while ($row = $rows->fetch()) {
	$personnes[$row['email']] = $row;
}

// Liste des idées
$forums = array(); // Liste des idées regroupées par forum
$ideesIndex = array(); // Index des idées avec le forum lié
$rows = sqlExecute("
	SELECT I.id, I.statut, I.email, I.forum, I.titre, I.texte, F.nom nomForum, F.titre titreForum, F.projet
	FROM idees I, forums F
	WHERE I.forum = F.nom
	ORDER BY I.id DESC");
while ($row = $rows->fetch()) {
	if (!isset($forums[$row['nomForum']])) {
		$forums[$row['nomForum']] = array(
			'id' => $row['nomForum'],
			'titre' => $row['titreForum'],
			'projet' => $row['projet'],
			'idees' => array()
		);
	}
	$forums[$row['nomForum']]['idees'][$row['id']] = $row;
	$ideesIndex[$row['id']] = $row['nomForum'];
	// Médias
	if (isset($medias[$row['id']])) {
		$forums[$row['nomForum']]['idees'][$row['id']]['medias'] = $medias[$row['id']];
	} else {
		$forums[$row['nomForum']]['idees'][$row['id']]['medias'] = array();
	}
	// Commentaires
	if (isset($commentaires[$row['id']])) {
		$forums[$row['nomForum']]['idees'][$row['id']]['nb_commentaires'] = $commentaires[$row['id']];
	} else {
		$forums[$row['nomForum']]['idees'][$row['id']]['nb_commentaires'] = 0;
	}
	// Personne
	if (isset($personnes[$row['email']])) {
		$forums[$row['nomForum']]['idees'][$row['id']]['nom'] = $personnes[$row['email']]['nom'];
		$forums[$row['nomForum']]['idees'][$row['id']]['prenom'] = $personnes[$row['email']]['prenom'];
	} else {
		$forums[$row['nomForum']]['idees'][$row['id']]['nom'] = "inconnu";
		$forums[$row['nomForum']]['idees'][$row['id']]['prenom'] = "";
	}
}

/*
	La modification d'une idée se passe dans panelIdee.php
*/
if ($action == "edit" or $action == "proposer") {
	// Données à afficher dans le panneau panelIdee.php
	$idIdee = $_GET['id'];
	if ($action == "edit") {
		$values = $forums[$ideesIndex[$idIdee]]['idees'][$idIdee];
	} else {
		// Valeurs récupérées du $_POST dans panelIdee.php, sauf pour les médias existants
		$values['medias'] = $forums[$ideesIndex[$idIdee]]['idees'][$idIdee]['medias'];
	}
	$ceForum = $forums[$ideesIndex[$idIdee]];
} else {
	// Onglet du forum
	if (isset($_GET['tab'])) {
		$ceForum = $forums[$_GET['tab']];
	} else {
		$ceForum = reset($forums); // Premier forum dans la liste
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
	<script src="/js/init.js"></script>
    <script src="/js/bootstrap.min.js"></script>
	<script src="/js/pgwcookie.min.js"></script>
    <link href="/css/default.css" rel="stylesheet">
	<?php include $_SERVER['DOCUMENT_ROOT']."/includes/tinyMce.php"; ?>
</head>

<body class="<?php echo $bodyClasses ?>">

<?php include "nav.php"; ?>

<DIV class="container-fluid">
	<DIV class="row">
		<DIV class="col-sm-12">
			<?php include $_SERVER['DOCUMENT_ROOT']."/includes/alerts.php"; ?>
			<UL class="nav nav-tabs">
				<?php foreach($forums as $idForum=>$forum) { ?>
				<LI <?php if ($idForum == $ceForum['id']) echo 'class="active"'; ?>><A href="?tab=<?php echo $idForum ?>"><?php echo $forum['titre'] ?></A></LI>
				<?php } ?>
			</UL>
			<?php if ($action == "edit" or $action == "proposer") { ?>
				<DIV class="panel panel-success">
				<?php include $_SERVER['DOCUMENT_ROOT']."/includes/panelIdee.php"; ?>
				</DIV>
			<?php } else { ?>
			<TABLE class="table table-striped">
				<THEAD>
					<TR>
						<TH>Titre</TH>
						<TH>Citoyen</TH>
						<TH>Médias</TH>
						<TH>Commentaires</TH>
						<TH>Statut</TH>
						<TH></TH>
					</TR>
				</THEAD>
				<TBODY>
				<?php foreach ($ceForum['idees'] as $idIdee=>$idee) { ?>
					<TR>
						<TD><?php echo $idee['titre'] ?></TD>
						<TD><A href="personnes.php?action=edit&id=<?php echo $idee['email'] ?>" title="Voir ou modifier cette personne"><?php echo $idee['prenom']." ".$idee['nom'] ?></a></TD>
						<TD><?php echo count($idee['medias']) ?></TD>
						<TD><?php echo $idee['nb_commentaires'] ?></TD>
						<TD><span class="label label-<?php echo classStatus($idee['statut']) ?>"><?php echo $idee['statut'] ?></span></TD>
						<TD><DIV class="btn-group">
								<A href="idees.php?action=edit&id=<?php echo $idIdee ?>" class="btn btn-sm btn-default" title="Modifier et/ou statuer"><SPAN class="glyphicon glyphicon-pencil"></SPAN></A>
								<?php if ($idee['nb_commentaires'] == 0) { ?>
								<A href="idees.php?action=delete&id=<?php echo $idIdee ?>" class="btn btn-sm btn-default _delete" title="Supprimer une idée" label-confirm="<?php echo $idee['titre'] ?>"><SPAN class="glyphicon glyphicon-trash"></SPAN></A>
								<?php } else { ?>
								<A href="" class="btn btn-sm btn-default disabled" title="Suppression impossible : il existe des commentaires"><SPAN class="glyphicon glyphicon-trash"></SPAN></A>
								<?php } ?>
							</DIV></TD>
					</TR>
				<?php } ?>
				</TBODY>
			</TABLE>
			<?php } ?>
		</DIV>
	</DIV>
</DIV>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>
</body>
</html>
