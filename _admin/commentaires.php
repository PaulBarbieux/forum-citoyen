<?php
require $_SERVER['DOCUMENT_ROOT']."/includes/init.php";

if ($action == "delete") {
	sqlExecute("DELETE FROM commentaires WHERE id='".$_GET['id']."'");
	$action = "completed";
	$message = "Commentaire ".$_GET['id']." supprimé.";
}

// Liste des personnes (une personne peut avoir été supprimée, donc pas de fusion avec la sélection suivante)
$personnes = array();
$rows = sqlExecute("SELECT * FROM personnes");
while ($row = $rows->fetch()) {
	$personnes[$row['email']] = $row;
}

// Liste des commentaires groupés par forum, idée
$forums = array();
$rows = sqlExecute("
	SELECT F.titre titreForum, F.nom idForum, C.conversation, C.id, C.email, C.texte, I.titre titreIdee
	FROM commentaires C, idees I, forums F
	WHERE C.conversation=I.id AND I.forum=F.nom
	ORDER BY F.titre ASC, C.conversation DESC , C.id ASC");
while ($row = $rows->fetch()) {
	if (!isset($forums[$row['idForum']])) {
		// Nouveau forum
		$forums[$row['idForum']]['titre'] = $row['titreForum'];
		$forums[$row['idForum']]['id'] = $row['idForum'];
	}
	if (!isset($forums[$row['idForum']]['conversations'][$row['conversation']])) {
		// Nouvelle conversation
		$forums[$row['idForum']]['conversations'][$row['conversation']]['titre'] = $row['titreIdee'];
	}
	$forums[$row['idForum']]['conversations'][$row['conversation']]['commentaires'][$row['id']] = $row;
	// Personne
	if (isset($personnes[$row['email']])) {
		$forums[$row['idForum']]['conversations'][$row['conversation']]['commentaires'][$row['id']]['nom'] = $personnes[$row['email']]['nom'];
		$forums[$row['idForum']]['conversations'][$row['conversation']]['commentaires'][$row['id']]['prenom'] = $personnes[$row['email']]['prenom'];
	} else {
		$forums[$row['idForum']]['conversations'][$row['conversation']]['commentaires'][$row['id']]['nom'] = "inconnu";
		$forums[$row['idForum']]['conversations'][$row['conversation']]['commentaires'][$row['id']]['prenom'] = "";
	}
}

// Onglet du forum
if (isset($_GET['tab'])) {
	$ceForum = $forums[$_GET['tab']];
} else {
	$ceForum = reset($forums); // Premier forum dans la liste
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
			<UL class="nav nav-tabs">
				<?php foreach ($forums as $idForum=>$forum) { ?>
				<LI <?php if ($idForum == $ceForum['id']) echo 'class="active"'; ?>><A href="?tab=<?php echo $idForum ?>"><?php echo $forum['titre'] ?></A></LI>
				<?php } ?>
			</UL>
		</DIV>
	</DIV>
	<DIV class="row">
		<DIV class="col-sm-12">
			<?php require $_SERVER['DOCUMENT_ROOT']."/includes/alerts.php"; ?>
			<TABLE class="table table-striped">
				<THEAD>
					<TR>
						<TH>Idée</TH>
						<TH>Auteur</TH>
						<TH>Date</TH>
						<TH>Texte</TH>
						<TH></TH>
					</TR>
				</THEAD>
				<TBODY>
				<?php foreach ($ceForum['conversations'] as $idConv=>$conversation) { ?>
					<TR>
						<TD rowspan="<?php echo count($conversation['commentaires']) ?>"><?php echo $conversation['titre'] ?></TD>
					<?php foreach ($conversation['commentaires'] as $id=>$commentaire) { ?>
						<TD><A href="personnes.php?action=edit&id=<?php echo $commentaire['email'] ?>" title="Voir/modifier cette personne"><?php echo $commentaire['prenom'] ?> <?php echo $commentaire['nom'] ?></A></TD>
						<TD><?php echo date("d/m/Y H:m",$id) ?></TD>
						<TD><?php echo $commentaire['texte'] ?></TD>
						<TD><A href="?action=delete&id=<?php echo $id ?>&tab=<?php echo $ceForum['id'] ?>" class="btn btn-sm btn-default _delete" label-confirm="<?php echo truncateText($commentaire['texte'],100) ?>"><SPAN class="glyphicon glyphicon-trash"></SPAN></A></TD>
					</TR>
					<?php } ?>
				<?php } ?>
				</TBODY>
			</TABLE>
		</DIV>
	</DIV>
</DIV>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>
</body>
</html>
