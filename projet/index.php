<?php
if (isset($_COOKIE['welcome'])) {
	$cookies = true;
} else {
	// Les cookies ne fonctionnent pas ou c'est la page d'entrée du site,
	// ce qui est tout à fait probable : le lien peut avoir été communiqué par email.
	if (isset($_GET['welcome'])) {
		// C'est le deuxième appel et toujours pas de cookie ? Alors ils sont désactivés !
		$cookies = false;
	} else {
		// Mettre un cookie et vérifier dans l'appel suivant
		setcookie("welcome", "true", time() + 3600, '/');
		header("location:".$_SERVER['REQUEST_URI']."&welcome");
	}
}
require $_SERVER['DOCUMENT_ROOT']."/includes/init.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Projets | <?= SITE_TITLE ?></title>
    
	<link rel="icon shortcut" href="/img/favicon.png" type="image/png">
    <!-- Bootstrap -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/default.css" rel="stylesheet">
    <link href="/css/font-awesome.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="/js/jquery-1.10.1.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
	<script src="/js/pgwcookie.min.js"></script>
	<script src="/js/masonry.pkgd.min.js"></script>
	<SCRIPT type="text/javascript">
	jQuery(document).ready(function(){
		// Layout Masonry
		$('.grid').masonry({
			  itemSelector: '.grid-item',
			  columnWidth: 380
		});
	});
	</SCRIPT>
</head>

<body class="<?php echo $bodyClasses ?>">

<?php
$error = false;
if (isset($_GET['all'])) {
	// Click sur l'onglet "Tous les projets" -> on quitte le détail d'un projet
	unset($_SESSION['ce_projet']);
} elseif (isset($_GET['id'])) {
	// Détail d'un projet
	$id = $_GET['id'];
	if (isset($PROJETS[$id])) {
		$_SESSION['ce_projet'] = $PROJETS[$id];
		$_SESSION['ce_projet']['adhesionOuverte'] = true;
		if (!$cookies) {
			// Pas de cookie laissé par la page appelante -> cookies bloqués
			$_SESSION['ce_projet']['vote_ouvert'] = false;
		}
		if ($CONNECTED) {
			if (isset($_SESSION['ce_projet']['adhesions'][$_SESSION['mon_profil']['email']])) {
				$_SESSION['ce_projet']['adhesionOuverte'] = false;
			}
		}
	} else {
		$error = true;
		$message = "Projet non trouvé : peut-être a-t-il été retiré du site.";
	}
	// Voter pour ce projet
	if (isset($_POST['vote'])) {
		if ($_SESSION['ce_projet']['vote_ouvert']) {
			$_SESSION['ce_projet']['votes']++;
			sqlExecute("UPDATE projets SET votes=".$_SESSION['ce_projet']['votes']." WHERE id='".$id."'");
			$action = "completed";
			$message = "Votre vote a été comptabilisé. Merci.";
			$_SESSION['ce_projet']['vote_ouvert'] = false;
			setcookie("forum_citoyen_".$id,$_SERVER['REMOTE_ADDR'].";".$id,time()+31536000,'/');
		}
	}
	// Souscrire à ce projet
	if (isset($_POST['souscrire'])) {
		if (isItHuman() === false) {
			// Rempli par un robot : ne rien signaler et revenir à la page
			header("Location: index.php");
			exit;
		}
		$values['profil'] = trim(strip_tags($_POST['profil']));
		if ($values['profil'] == "") {
			// Cette erreur peut être écrasé par une autre avec les données personnelles, plus loin : 
			// c'est qui très bien puisque que cette zone est en fin de formulaire.
			$error = true;
			$message = "Pourriez vous écrire quelques à propos de votre motivation s.v.p. ?";
		}
		if ($CONNECTED) {
			// Connecté : chercher l'email dans la session
			$values['email'] = $_SESSION['mon_profil']['email'];
		} else {
			// Non connecté : réception des données de profil
			$values = $_POST;
			require $_SERVER['DOCUMENT_ROOT']."/includes/personneValidation.php";
			if (!$error) {
				// Créer la personne
				$urlRetourApresConnexion = "/projet/?id=".$id;
				require $_SERVER['DOCUMENT_ROOT']."/includes/personneCreation.php";
			}
		}
		if ($error) {
			// Afficher le popup avec l'erreur
?>
<SCRIPT type="text/javascript">
jQuery(document).ready(function(){
	$('#ModalSouscrire').modal('show');
});
</SCRIPT>
<?php
		} else {
			// Profil éventuel
			$values['profil'] = trim(strip_tags($_POST['profil']));
			// Ajouter son adhésion
			if (sqlExecute ("INSERT INTO adhesions VALUES (".$db->quote($values['email']).",".$db->quote($id).",".$db->quote($values['profil']).")")
				== DUPLICATE_KEY) {
				// Cette situation peut exister si la personne n'est pas connectée
				$action = "warning";
				$message = "Vous avez déjà souscrit à ce projet.";
			} else {
				$action = "completed";
				$message = "Votre adhésion est enregistrée. Merci.";
				if (!$CONNECTED) {
					$messageWarning = "Votre adhésion sera effective quand vous aurez confirmé votre inscription : un email a été envoyé à l'adresse ".$values['email'].".";
				}
				$_SESSION['ce_projet']['adhesionOuverte'] = false;
			}
		}
	}
}
?>

<?php include $_SERVER['DOCUMENT_ROOT']."/includes/nav.php"; ?>

<?php if (isset($_SESSION['ce_projet'])) { ?>

<!------------------------------------------------------------------- Un projet ---------------------------------->

<DIV class="strip strip-banner">
	<DIV class="container">
		<DIV class="row">
			<A href="/" class="strip-brand"><IMG src="/img/logo_ombre-50.png"></A>
			<DIV class="strip-title">
				<H1><I class="fa fa-pencil-square-o" aria-hidden="true"></I> <?php echo $_SESSION['ce_projet']['titre'] ?></H1>
			</DIV>
		</DIV>
	</DIV>
</DIV>

<DIV class="strip">
	<DIV class="container">
		<DIV class="row">
			<?php if (!$cookies) { ?>
			<DIV class="col-sm-12">
				<P class="alert alert-danger">Il semble que vous bloquez les <i>cookies</i> dans votre navigateur : cette page ne fonctionnera pas bien et vous ne pourrez pas voter.</P>
			</DIV>
			<?php } ?>
			<DIV class="col-sm-12">
				<?php require $_SERVER['DOCUMENT_ROOT']."/includes/alerts.php" ?>
			</DIV>
		</DIV>
		<DIV class="row">
			<DIV class="col-sm-12">
				<ul class="nav nav-tabs">
					<li <?php if (!isset($_GET['tab']) or $_GET['tab'] == "presentation") { ?>class="active"<?php } ?>><a href="<?php echo $THIS_PAGE['name'] ?>?tab=presentation">Pr&eacute;sentation</a></li>
					<li <?php if (isset($_GET['tab']) and $_GET['tab'] == "actualites") { ?>class="active"<?php } ?>><a href="<?php echo $THIS_PAGE['name'] ?>?tab=actualites">Actualit&eacute;s</a></li>
					<li><a href="?all">Tous les projets</a></li>
					<li><a href="soumettre.php">Soumettre un projet</a></li>
				</ul>
			</DIV>
		</DIV>

<?php if (!isset($_GET['tab']) or $_GET['tab'] == "presentation") { ?>

		<DIV class="row">
			<DIV class="col-sm-12">
				<P>Proposé par <STRONG><?php echo $_SESSION['ce_projet']['prenom']." ".$_SESSION['ce_projet']['nom'] ?></STRONG> le <?php echo date("d/m/Y",$_SESSION['ce_projet']['id']) ?>
					<SPAN class="compteur">
						<I class="fa fa-users" aria-hidden="true"></I> <?php echo count($_SESSION['ce_projet']['adhesions']) ?>
						<?php echo (count($_SESSION['ce_projet']['adhesions']) > 1 ? "adhérents" : "adhérent") ?>
					</SPAN>
					<SPAN class="compteur">
						<SPAN class="glyphicon glyphicon-thumbs-up"></SPAN> <?php echo $_SESSION['ce_projet']['votes'] ?>
						<?php echo ($_SESSION['ce_projet']['votes'] > 1 ? "votes" : "vote") ?>
					</SPAN>
				</P>
			</DIV>
			<?php if (isset($_SESSION['ce_projet']['illustration'])) { ?>
			<DIV class="col-md-8 col-sm-6">
				<IMG src="<?php echo MEDIAS_FOLDER.$_SESSION['ce_projet']['illustration']['fichier'] ?>" class="img-responsive img-illustration">
			</DIV>
			<?php } ?>
			<DIV class="col-md-4 col-sm-6">
				<DIV class="panel panel-primary">
					<DIV class="panel-heading">
						<H2>Réagissez !</H2>
					</DIV>
					<DIV class="panel-body">
						<FORM method="post" class="form-horizontal">
							<P>Montrer votre intérêt pour ce projet.</P>
							<div class="form-group">
								<label class="col-sm-6 control-label"><?php echo $_SESSION['ce_projet']['votes'] ?> <?php echo ($_SESSION['ce_projet']['votes'] > 1 ? "votes" : "vote") ?></label>
								<div class="col-sm-6">
									<?php if ($_SESSION['ce_projet']['vote_ouvert']) { ?>
									<button class="btn btn-primary" type="submit" name="vote"><SPAN class="glyphicon glyphicon-thumbs-up"></SPAN> Votez</button>
									<?php } else { ?>
									<button class="btn btn-default" type="button" disabled title="Vous avez déjà voté"><SPAN class="glyphicon glyphicon-thumbs-up"></SPAN> Votez</button>
									<?php } ?>
								</div>
							</div>
						</FORM>
						<?php if ($_SESSION['ce_projet']['adhesionOuverte']) { ?>
							<P>Adhérez au projet en vous inscrivant. Vous serez tenu informé de son évolution, tout comme vous deviendrez membre de l'équipe quand son forum sera lancé.</P>
							<?php if ($CONNECTED) { ?>
							<A href="#" class="btn btn-block btn-primary" data-toggle="modal" data-target="#ModalSouscrire" data-projet="<?php echo $_SESSION['ce_projet']['titre'] ?>"><SPAN class="glyphicon glyphicon-ok"></SPAN> Adhérez</A>
							<?php } else { ?>
							<P class="alert alert-warning">Veuillez <A href="/connexion/?back=<?= $_SERVER['REQUEST_URI'] ?>">vous connecter ou vous inscrire</A> pour pouvoir adhérer à ce projet.</P>
							<?php } ?>
						<?php } else { ?>
							<P><SPAN class="glyphicon glyphicon-ok"></SPAN> Vous adhérez à ce projet.</P>
						<?php } ?>
					</DIV>
				</DIV>
			</DIV>
		<?php if (isset($_SESSION['ce_projet']['illustration'])) { ?>
		</DIV>
		<DIV class="row">
		<?php } ?>
			<DIV class="col-md-4 col-sm-6">
				<H2>Description</H2>
				<P><?php echo $_SESSION['ce_projet']['description'] ?></P>
			</DIV>
			<DIV class="col-md-4 col-sm-6">
				<H2>Attente</H2>
				<P><?php echo $_SESSION['ce_projet']['attente'] ?></P>
			</DIV>
			<DIV class="col-md-4 col-sm-6">
				<H2>Ressources</H2>
				<P><?php echo $_SESSION['ce_projet']['ressources'] ?></P>
			</DIV>
			<DIV class="col-md-4 col-sm-6">
				<H2>Initiative</H2>
				<P><?php echo $_SESSION['ce_projet']['initiative'] ?></P>
			</DIV>
			<DIV class="col-md-4 col-sm-6">
				<H2>Démarches</H2>
				<P><?php echo $_SESSION['ce_projet']['demarches'] ?></P>
			</DIV>
			<?php if (count($_SESSION['ce_projet']['medias']) > 0) { ?>
			<DIV class="col-sm-12">
				<H2>Médias</H2>
				<DIV class="row">
					<?php foreach ($_SESSION['ce_projet']['medias'] as $fichier=>$media) { ?>
						<div class="col-sm-4">
							<A href="<?php echo MEDIAS_FOLDER.$fichier ?>" target="_blank" title="Voir le média">
								<?php if ($media['role'] == "image") { ?>
								<IMG src="<?php echo MEDIAS_FOLDER.$media['fichier'] ?>" class="img-responsive">
								<?php } else { ?>
								<IMG src="/img/symbol_pdf.png" class="img-responsive">
								<?php } ?>
							</A>
							<P class="text-center"><?php echo str_replace($media['proprietaire']."_","",$fichier) ?></P>
						</div>
					<?php } ?>
				</DIV>
			</DIV>
			<?php } ?>
		</DIV>

<?php } else { ?>

<!------------------------------------------------------------------- Nouvelles ---------------------------------->

		<DIV class="row">
			<?php
			$selectNouvelles = "SELECT * FROM nouvelles WHERE lien='".$_SESSION['ce_projet']['id']."' AND archive='0' ORDER BY date DESC, id DESC";
			require $_SERVER['DOCUMENT_ROOT']."/includes/afficherNouvelles.php";
			?>
		</DIV>

<?php } ?>

	</DIV>
</DIV>
<?php include "modalSouscrire.php" ?>
<?php } else { ?>

<!------------------------------------------------------------------- Tous les projets ---------------------------------->

<DIV class="strip strip-banner">
	<DIV class="container">
		<DIV class="row">
			<A href="/" class="strip-brand"><IMG src="/img/logo_ombre-50.png"></A>
			<DIV class="strip-title">
				<H1>Tous les projets</H1>
			</DIV>
		</DIV>
	</DIV>
</DIV>

<DIV class="strip">
	<DIV class="container">
		<DIV class="row grid">
			<DIV class="col-sm-12 grid">
				<DIV class="panel grid-item">
					<DIV class="panel-heading">
						<H2>Titre</H2>
					</DIV>
					<DIV class="panel-body">
						<P>Voici des projets que les citoyens ont envoy&eacute;s, et qui sont soumis &agrave; votre attention. Vous pouvez voter pour eux et mieux encore, y adh&eacute;rer : avec votre soutien, le projet pourra alors devenir un forum, ouvert &agrave; toutes les id&eacute;es. </P>
						<P>Vous aussi, vous pouvez nous soumettre un projet.</P>
						<P>Voici les types de sujets possibles qui pourraient être abordés: mobilité, social, santé, etc. (nous vous invitons à consulter le <a href="/manifeste.php#point4" target="_blank">point 4 de notre manifeste</a>).</P>
						<P><A href="/projet/soumettre.php" class="btn btn-success btn-block"><SPAN class="glyphicon glyphicon-send"></SPAN> Soumettez votre projet</A></P>
					</DIV>
				</DIV>
				<?php foreach ($PROJETS as $idProjet=>$projet) { ?>
				<DIV class="panel panel-projet grid-item">
					<DIV class="panel-heading">
						<H2 class="panel-title"><I class="fa fa-pencil-square-o" aria-hidden="true"></I> <?php echo $projet['titre'] ?></H2>
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
						</P>
						<P><A href="/projet/?id=<?php echo $projet['id'] ?>" class="btn btn-primary btn-block"><SPAN class="glyphicon glyphicon-chevron-right"></SPAN> Lisez, votez, adhérez</A></P>
					</DIV>
				</DIV>
				<?php } ?>
			</DIV>
		</DIV>
	</DIV>
</DIV>
<?php } ?>

<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>
</body>
</html>
