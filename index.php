<?php
require $_SERVER['DOCUMENT_ROOT']."/includes/init.php";

/*
	Dernières nouvelles
*/
$rows = sqlExecute("SELECT * FROM nouvelles WHERE archive=0 ORDER BY date DESC, id DESC LIMIT 5");
$nouvelles = array();
$idNouvelles = array();
while ($row = $rows->fetch()) {
	$nouvelles[$row['id']] = $row;
	$idNouvelles[]= $row['id'];
}
$rows->closeCursor();
if (count($idNouvelles) > 0) {
	$rows = sqlExecute("SELECT * FROM medias WHERE role='illustration' AND proprietaire IN (".implode(",",$idNouvelles).")");
	while ($row = $rows->fetch()) {
		$nouvelles[$row['proprietaire']]['illustration'] = $row['fichier'];
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
    <link href="/css/default.css?20160916" rel="stylesheet">
    <link href="/css/font-awesome.min.css" rel="stylesheet">
</head>

<body class="home <?php echo $bodyClasses ?>">

<?php include $_SERVER['DOCUMENT_ROOT']."/includes/nav.php"; ?>

<DIV class="strip strip-banner strip-identite">
	<DIV class="container">
		<DIV class="row">
			<IMG class="strip-brand" src="img/logo_ombre.png">
			<DIV class="strip-title">
				<H1><?= SITE_SLOGAN ?></H1>
			</DIV>
		</DIV>
	</DIV>
</DIV>
<DIV class="strip">
	<DIV class="container">
		<DIV class="row">
					
			<!------------------------------------------------------------- Actualités ------------------------------------->
			
			<DIV class="col-md-4 col-sm-6 col-actualites">
				<H1>Nouvelles </H1>
				<P>Voici les dernières nouvelles concernant notre association, les forums et les projets. </P>
				<?php foreach ($nouvelles as $idNouvelle=>$nouvelle) { ?>
				<DIV class="panel panel-news">
					<DIV class="panel-heading">
						<span class="glyphicon glyphicon-bullhorn"></span> <?php echo transformDate($nouvelle['date'],"dd/mm/yyyy") ?><BR>
						<H2 class="panel-title"><?php echo $nouvelle['titre'] ?></H2>
					</DIV>
					<DIV class="panel-body">
						<?php if (isset($nouvelle['illustration'])) { ?>
						<DIV class="panel-illu" style="background-image:url('/_medias/<?php echo $nouvelle['illustration'] ?>');"></DIV>
						<?php } ?>
						<DIV class="news-excerpt"><?php echo truncateText(strip_tags($nouvelle['texte']),200," ") ?></DIV>
						<DIV class="news-more"><A href="/nouvelles/?idNouvelle=<?php echo $idNouvelle ?>"><SPAN class="glyphicon glyphicon-chevron-right"></SPAN> Lire la suite</A></DIV>
					</DIV>
				</DIV>
				<?php } ?>
			</DIV>
		
			<!------------------------------------------------------------- Forums ------------------------------------->
			
			<DIV class="col-md-4 col-sm-6 col-forums">
				<H1>Forums</H1>
				<P>Il y a <?php echo count($FORUMS) ?> <?php echo (count($FORUMS) == 1 ? "forum ouvert" : "forums ouverts") ?> aux id&eacute;es : rejoignez-nous pour en discuter. </P>
				<?php
				foreach ($FORUMS as $forum) {
					// Nombre d'idées
					$sql = sqlExecute("SELECT count(*) FROM idees WHERE forum='".$forum['nom']."' AND statut='accepte'"); 
					$row = $sql->fetch();
					$cntIdees = $row[0];
					// Dernier commentaire du forum
					$sql = sqlExecute("
						SELECT C.id, C.email, C.texte, P.prenom 
						FROM idees I, commentaires C, personnes P
						WHERE C.conversation = I.id AND I.forum = '".$forum['nom']."' AND C.email = P.email
						ORDER BY C.id DESC
						LIMIT 1");
					if ($row = $sql->fetch()) {
						$dernierCommentaire = $row;
					} else {
						$dernierCommentaire = false;
					}
				?>
				<DIV class="panel panel-forum">
					<DIV class="panel-heading">
						<H3><i class="fa fa-comments" aria-hidden="true"></i> <?php echo $forum['titre'] ?></H3>
					</DIV>
					<DIV class="panel-body">
						<?php if (file_exists("forums/".$forum['nom']."/cover.png")) { ?>
						<DIV class="panel-illu" style="background-image:url('/forums/<?php echo $forum['nom'] ?>/cover.png"></DIV>
						<?php } ?>
						<P><?php echo $forum['introduction'] ?></P>
						<?php if ($dernierCommentaire !== false) { ?>
							<DIV class="idee-commentaire">
								<DIV class="meta">Dernier commentaire par <?php echo $dernierCommentaire['prenom'] ?>
								le <?php echo date("d/m/Y",$dernierCommentaire['id']) ?> :</DIV>
								<DIV class="body"><?php echo $dernierCommentaire['texte'] ?></DIV>
							</DIV>
						<?php } ?>
						<P class="text-center">
							<SPAN class="compteur">
								<I class="fa fa-users" aria-hidden="true"></I> <?php echo count($forum['adhesions']) ?>
								<?php echo (count($forum['adhesions']) > 1 ? "adhérents" : "adhérent") ?>
							</SPAN>
							<SPAN class="compteur">
								<i class="fa fa-lightbulb-o" aria-hidden="true"></i> <?php echo $cntIdees ?>
								<?php echo ($cntIdees > 1 ? "idées" : "idée") ?>
							</SPAN>
						</P>
						<p><a href="forum/?nom=<?php echo $forum['nom'] ?>" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-chevron-right"></span> Découvrez ce forum</a> </p>
					</DIV>
				</DIV>
				<?php } ?>
			</DIV>
		
			<!------------------------------------------------------------- Projets ------------------------------------->
			
			<DIV class="col-md-4 col-sm-6 col-projets">
				<H1>Projets</H1>
				<P>Voici les derniers projets soumis à votre attention.</P>
				<?php
				$cntProjet = 0;
				foreach ($PROJETS as $idProjet=>$projet) {
					$cntProjet++;
					if ($cntProjet > 4) break;
				?>
				<DIV class="panel panel-projet">
					<DIV class="panel-heading">
						<H2 class="panel-title"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <?php echo $projet['titre'] ?></H2>
						<P class="meta">Soumis par <?php echo $projet['prenom']." ".$projet['nom']." le ".date("d/m/Y",$idProjet) ?></P>
					</DIV>
					<DIV class="panel-body">
						<?php if (isset($projet['illustration'])) { ?>
						<DIV class="panel-illu" style="background-image:url('<?php echo MEDIAS_FOLDER.$projet['illustration']['fichier'] ?>')"></DIV>
						<?php } ?>
						<P><?php echo $projet['description'] ?></P>
						<P class="text-center">
							<SPAN class="compteur">
								<I class="fa fa-users" aria-hidden="true"></I> <?php echo count($projet['adhesions']) ?>
								<?php echo (count($projet['adhesions']) > 1 ? "adhérents" : "adhérent") ?>
							</SPAN>
							<SPAN class="compteur">
								<SPAN class="glyphicon glyphicon-thumbs-up"></SPAN> <?php echo $projet['votes'] ?>
								<?php echo ($projet['votes'] > 1 ? "votes" : "vote") ?>
							</SPAN>
						<P><A href="/projet/?id=<?php echo $idProjet ?>" class="btn btn-primary btn-block"><SPAN class="glyphicon glyphicon-chevron-right"></SPAN> Lisez, votez, adhérez</A></P>
					</DIV>
				</DIV>
				<?php
				}
				?>
				<P><A href="/projet/soumettre.php" class="btn btn-primary btn-lg btn-block"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Soumettez votre projet</A></P>
				<P>&nbsp;</P>
				<P><A href="/projet/" class="btn btn-block btn-primary"><SPAN class="glyphicon glyphicon-chevron-right"></SPAN> Voir tous les projets</A></P>
			</DIV>
		</DIV>
	</DIV>
</DIV>

<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>
</body>
</html>