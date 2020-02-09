<?php
require $_SERVER['DOCUMENT_ROOT']."/includes/init.php";

$error = false;
$aucunForum = false;
$jeSuisAdherent = false;

// Chercher le forum à afficher
if (isset($_GET['nom'])) {
	foreach($FORUMS as $forum) {
		if ($forum['nom'] == $_GET['nom']) {
			$_SESSION['ce_forum'] = $forum;
			break;
		}
	}
	if (!isset($_SESSION['ce_forum'])) {
		$aucunForum = true;
	}
} elseif (isset($_GET['tab'])) {
	if (!isset($_SESSION['ce_forum'])) {
		// On a mis une URL sans passer par le premier onglet de cette page
		$aucunForum = true;
	}
} else {
	$aucunForum = true;
}
if ($aucunForum) {
	if (count($_SESSION) == 0) {
		print "<P><strong>Il semble que vous bloquez les cookies</strong> : le site ne peut pas fonctionner correctement.</P>";
	} else {
		print "<P>Désolé : forum inconnu.</P>";
	}
	print "<P><A href='/'>Page d'accueil</A></P>";
	exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $_SESSION['ce_forum']['titre'] ?> | <?= SITE_TITLE ?></title>
    
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
    <link href="/css/default.css?20160924" rel="stylesheet">
	<link href="/css/fonts.css" rel="stylesheet">
    <link href="/css/font-awesome.min.css" rel="stylesheet">
</head>

<body class="home">

<?php
// Souscrire à ce forum
if (isset($_POST['souscrire']) and CONNECTED) {
	if (isItHuman() === false) {
		// Formulaire rempli par un robot !
		header("Location: index.php?tab=equipe");
		exit;
	}
	$values['profil'] = trim(strip_tags($_POST['profil']));
	if ($values['profil'] == "") {
		// Cette erreur peut être écrasée par une autre avec les données personnelles, plus loin : 
		// c'est qui très bien puisque que cette zone est en fin de formulaire.
		$error = true;
		$message = "Pourriez-vous écrire quelques mots dans le profil s.v.p. ?";
	}
	$values['email'] = $_SESSION['mon_profil']['email'];
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
		// Profil
		$values['profil'] = trim(strip_tags($_POST['profil']));
		// Ajouter son adhésion
		if (sqlExecute ("INSERT INTO adhesions VALUES (".$db->quote($values['email']).",".$_SESSION['ce_forum']['projet'].",".$db->quote($values['profil']).")")
			== DUPLICATE_KEY) {
			// Cette situation peut exister si la personne n'est pas connectée
			$messageWarning = "Vous avez déjà souscrit à ce forum.";
		} else {
			$action = "completed";
			$message = "Votre adhésion au forum est enregistrée.";
			if (!$CONNECTED) {
				$messageWarning = "Votre adhésion sera effective quand vous aurez confirmé votre inscription : un email a été envoyé à l'adresse <STRONG>".$values['email']."</STRONG>.";
			} else {
				$_SESSION['ce_forum']['adhesions'][$values['email']] = array('prenom'=>$_SESSION['mon_profil']['prenom'], 'profil'=>$values['profil']);
			}
			$jeSuisAdherent = true;
		}
	}
}

// Connecté, adhérent ?
if ($CONNECTED and isset($_SESSION['ce_forum']['adhesions'][$_SESSION['mon_profil']['email']])) {
	$jeSuisAdherent = true;
}

$conversationOuverte = ""; // Conversation dans laquelle on est occupé : pour l'ouvrir et se positionner dessus

// Ajout d'un commentaire
if (isset($_POST['nouveauCommentaire']) or isset($_POST['modifierCommentaire'])) {
	if (!$jeSuisAdherent) {
		// Piratage
		exit;
	}
	$conversationOuverte = $_POST['idee'];
	$texte = trim(strip_tags($_POST['texte']));
	if (isset($_POST['nouveauCommentaire'])) {
		// Nouveau commentaire
		if ($texte == "") {
			$error = true;
			$message = "Votre texte de commentaire est vide";
		} else {
			sqlExecute("INSERT INTO commentaires (id, conversation, email, texte) 
				VALUES ('".time()."','".$_POST['idee']."','".$_SESSION['mon_profil']['email']."',".$db->quote($texte).")");
		}
	} else {
		// Commentaire modifié
		if ($texte == "") {
			// Suppression
			sqlExecute("DELETE FROM commentaires WHERE id='".$_POST['id']."'");
		} else {
			sqlExecute("UPDATE commentaires SET texte=".$db->quote($texte)." WHERE id='".$_POST['id']."'");
		}
	}
	// Corriger le positionnement par la hauteur de la bannière
?>
<SCRIPT type="text/javascript">
jQuery(document).ready(function(){
	setTimeout(function(){
		$(window).scrollTop($(window).scrollTop() - 100);
	},500);
});
</SCRIPT>
<?php
}
?>

<?php include $_SERVER['DOCUMENT_ROOT']."/includes/nav.php"; ?>

<?php include "modalSouscrire.php" ?>

<DIV class="strip strip-banner">
	<DIV class="container">
		<DIV class="row">
			<A href="/" class="strip-brand"><IMG src="/img/logo_ombre-50.png"></A>
			<DIV class="strip-title">
				<H1><i class="fa fa-comments" aria-hidden="true"></i> <?php echo $_SESSION['ce_forum']['titre'] ?></H1>
			</DIV>
		</DIV>
	</DIV>
</DIV>

<DIV class="strip">
	<DIV class="container">
		<DIV class="row">
			<DIV class="col-md-12">
				<?php require $_SERVER['DOCUMENT_ROOT']."/includes/alerts.php" ?> 
				<ul class="nav nav-tabs">
					<li <?php if (!isset($_GET['tab']) or $_GET['tab'] == "idees") { ?>class="active"<?php } ?>><a href="<?php echo $THIS_PAGE['name'] ?>?tab=idees">Les id&eacute;es</a></li>
					<li <?php if (isset($_GET['tab']) and $_GET['tab'] == "presentation") { ?>class="active"<?php } ?>><a href="<?php echo $THIS_PAGE['name'] ?>?tab=presentation">Pr&eacute;sentation</a></li>
					<li <?php if (isset($_GET['tab']) and $_GET['tab'] == "actualites") { ?>class="active"<?php } ?>><a href="<?php echo $THIS_PAGE['name'] ?>?tab=actualites">Actualit&eacute;s</a></li>
					<li <?php if (isset($_GET['tab']) and $_GET['tab'] == "equipe") { ?>class="active"<?php } ?> ><a href="<?php echo $THIS_PAGE['name'] ?>?tab=equipe">Les adhérents</a></li>
				</ul>
			</DIV>
		</DIV>
<?php
if (isset($_GET['tab']) and $_GET['tab'] == "presentation") { ?>
		<!-------------------------------------------------- PRESENTATION ----------------------------------------->
	<?php include $_SERVER['DOCUMENT_ROOT']."/forums/".$_SESSION['ce_forum']['nom']."/index.php"; ?>
<?php
} elseif (!isset($_GET['tab']) or $_GET['tab'] == "idees") { 
?>
		<!-------------------------------------------------- IDEES ----------------------------------------->

<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script src="/js/masonry.pkgd.min.js"></script>
<SCRIPT type="text/javascript">
jQuery(document).ready(function(){
	tinymce.init({
		selector:'.tinymce',
		plugins: ['link code'],
		toolbar: 'bold italic underline strikethrough | bullist numlist | link unlink',
		menubar: false,
		init_instance_callback : function(editor) {
			$('.grid').masonry(); // Recalculer les places
		}
	});
	// Layout Masonry
	$('.grid').masonry({
		  itemSelector: '.grid-item',
		  columnWidth: 380
	});
	// Provoquer Masonry
	$("._conversation").on("shown.bs.collapse", function(){
		$('.grid').masonry();
	});
	if ($("#conversationOuverte").val() != "") {
		// Se positionner sur une conversation ouverte
		ancreConversation = "#conversation-" + $("#conversationOuverte").val();
		$(document).scrollTop( $(ancreConversation).offset().top );
	}
	// Ouverture d'un commentaire pour modification
	$("._openComment").click(function(){
		blockComment = "#block-" + $(this).attr("id-comment");
		$(blockComment).find("._hideForModif").hide();
		$(blockComment).find("._showForModif").show();
		$('.grid').masonry();
		return false;
	});
	// Annuler l'ouvertue
	$("._cancelOpenComment").click(function(){
		blockComment = "#block-" + $(this).attr("id-comment");
		$(blockComment).find("._hideForModif").show();
		$(blockComment).find("._showForModif").hide();
		$('.grid').masonry();
		return false;
	});
});
</SCRIPT>
		<INPUT type="hidden" id="conversationOuverte" value="<?php echo $conversationOuverte ?>">
		<DIV class="row">
			<DIV class="col-sm-12 grid">
<?php
// Liste des idées
$idees = array();
$rows = sqlExecute("
	SELECT I.id, I.statut, I.email, I.forum, I.titre, I.texte, F.titre titreForum, P.nom, P.prenom
	FROM idees I, forums F, personnes P
	WHERE I.forum='".$_SESSION['ce_forum']['nom']."' AND I.forum = F.nom AND I.statut='accepte' AND I.email = P.email
	ORDER BY I.forum, I.id DESC");
while ($row = $rows->fetch()) {
	$idees[$row['id']] = $row;
	$idees[$row['id']]['medias'] = array();
	$idees[$row['id']]['commentaires'] = array();
}
// Compléter la liste avec les médias attachés
$rows = sqlExecute("SELECT * FROM medias ORDER BY proprietaire");
while ($row = $rows->fetch()) {
	if (isset($idees[$row['proprietaire']])) {
		$idees[$row['proprietaire']]['medias'][$row['fichier']] = $row;
	}
}
// Compléter avec les commentaires
$rows = sqlExecute("
	SELECT C.id, C.conversation, C.email, C.texte, P.prenom
	FROM commentaires C, personnes P 
	WHERE C.email=P.email 
	ORDER BY id ASC");
while ($row = $rows->fetch()) {
	if (isset($idees[$row['conversation']])) {
		$idees[$row['conversation']]['commentaires'][$row['id']] = $row;
	}
}
?>
				<DIV class="panel grid-item">
					<DIV class="panel-heading">
						<H2>Introduction à ce forum</H2>
					</DIV>
					<DIV class="panel-body">
						<?php echo $_SESSION['ce_forum']['introduction'] ?>
						<P class="text-right"><A href="?tab=presentation" class="btn btn-sm btn-primary"><SPAN class="glyphicon glyphicon-chevron-right"></SPAN> Lisez tout notre dossier</A></P>
						<?php if (!$jeSuisAdherent) { ?>
						<P class="alert alert-warning">Vous trouverez ici toutes les idées soumises par les adhérents de ce forum.
						Si vous voulez réagir ou soumettre une idée, <a href="?tab=equipe">vous devez adhérer au forum</a>.</P>
						<?php } ?>
					</DIV>
				</DIV>
			<?php if ($jeSuisAdherent) { ?>
				<DIV class="panel panel-primary grid-item">
					<?php require $_SERVER['DOCUMENT_ROOT']."/includes/panelIdee.php"; ?>
					<A id="conversation-nouvelle"></A>
				</DIV>
			<?php } ?>
			<?php foreach ($idees as $idIdee=>$idee) { ?>
				<DIV class="panel panel-default grid-item">
					<DIV class="panel-heading">
						<H2 class="panel-title"><i class="fa fa-lightbulb-o" aria-hidden="true"></i> <?php echo $idee['titre'] ?></H2>
						<P class="meta">Proposée par <STRONG><?php echo $idee['prenom'] ?></STRONG> le <?php echo date("d/m/Y",$idIdee) ?></P>
					</DIV>
					<DIV class="panel-body">
						<?php echo $idee['texte'] ?>
						<?php foreach ($idee['medias'] as $fichier=>$media) { ?>
						<div class="media">
							<div class="media-left">
								<?php if ($media['role'] == "image") { ?>
								<IMG src="<?php echo MEDIAS_FOLDER.$media['fichier'] ?>" class="media-object" width="50">
								<?php } else { ?>
								<IMG src="/img/symbol_pdf.png" class="media-object" width="50">
								<?php } ?>
							</div>
							<div class="media-body">
								<A href="<?php echo MEDIAS_FOLDER.$fichier ?>" target="_blank" title="Voir le média"><?php echo substr($fichier,11) ?></A>
							</div>					
						</div>
						<?php } ?>
						<DIV class="panel-group idee-discussion" id="idee-<?php echo $idIdee ?>">
							<?php
							if (count($idee['commentaires']) > 0) {
								$dernierCommentaire = end($idee['commentaires']);
							?>
							<DIV class="panel">
								<DIV class="panel-heading">
									<A class="collapse-link btn btn-block" data-toggle="collapse" data-parent="#idee-<?php echo $idIdee ?>" href="#commentaire-<?php echo $dernierCommentaire['id'] ?>">
										Dernier commentaire le <?php echo date("d/m/Y",$dernierCommentaire['id']) ?>
									</A>
								</DIV>
								<DIV id="commentaire-<?php echo $dernierCommentaire['id'] ?>" class="panel-collapse collapse <?php echo ($conversationOuverte == $idIdee) ? "" : "in" ?>">
									<DIV class="panel-body">
										<DIV class="idee-commentaire">
											<div class="media">
												<div class="media-left">
													<IMG width="50" src="http://www.gravatar.com/avatar/<?php echo md5($dernierCommentaire['email']) ?>?s=50&d=blank" class="media-object gravatar" style="background-image:url('/img/gravatar/<?php echo strtoupper(substr($dernierCommentaire['prenom'],0,1)) ?>.jpg');">
												</div>
												<div class="media-body">
													<div class="commentaire-texte">
														<STRONG><?php echo $dernierCommentaire['prenom'] ?></STRONG> <?php echo nl2br($dernierCommentaire['texte']) ?>
													</div>
												</div>
											</div>
										</DIV>
									</DIV>
								</DIV>
							</DIV>
							<?php } ?>
							<DIV class="panel">
								<DIV class="panel-heading">
									<A class="btn btn-block collapse-link collapsed" data-toggle="collapse" data-parent="#idee-<?php echo $idIdee ?>" href="#conversation-<?php echo $idIdee ?>">
										<i class="fa fa-comments" aria-hidden="true"></i> 
										<?php if (count($idee['commentaires']) == 0) { ?>
										Soyez le premier à réagir
										<?php } elseif (count($idee['commentaires']) == 1) { ?>
										Réagissez
										<?php } else { ?>
										Voir les <?php echo count($idee['commentaires']) ?> commentaires et réagir
										<?php } ?>
									</A>
								</DIV>
								<DIV  id="conversation-<?php echo $idIdee ?>" class="panel-collapse collapse <?php echo ($conversationOuverte == $idIdee) ? "in active" : "" ?> _conversation">
									<DIV class="panel-body">
										<?php foreach ($idee['commentaires'] as $idCommentaire=>$commentaire) { ?>
										<DIV class="idee-commentaire" id="block-<?php echo $idCommentaire ?>">
											<div class="media">
												<div class="media-left">
													<IMG width="50" src="http://www.gravatar.com/avatar/<?php echo md5($commentaire['email']) ?>?s=50&d=blank" class="media-object gravatar" style="background-image:url('/img/gravatar/<?php echo strtoupper(substr($commentaire['prenom'],0,1)) ?>.jpg');">
												</div>
												<div class="media-body">
													<div class="commentaire-texte _hideForModif">
														<STRONG><?php echo $commentaire['prenom'] ?></STRONG> <?php echo nl2br($commentaire['texte']) ?>
													</div>
													<?php if ($CONNECTED and $commentaire['email'] == $_SESSION['mon_profil']['email']) { ?>
													<FORM method="post" class="commentaire-input _showForModif" style="display:none; ">
														<TEXTAREA name="texte" rows="3" style="width:100%" placeholder="En laissant vide, le commentaire sera supprimé"><?php echo $commentaire['texte'] ?></TEXTAREA>
														<INPUT type="hidden" name="id" value="<?php echo $idCommentaire ?>">
														<INPUT type="hidden" name="idee" value="<?php echo $idIdee ?>">
														<SPAN class="meta">Vider le texte pour le supprimer</SPAN>
														<DIV class="btn-group">
															<BUTTON type="submit" name="modifierCommentaire" class="btn btn-primary btn-xs">Enregistrer</BUTTON>
															<BUTTON type="button" class="btn btn-default btn-xs _cancelOpenComment" id-comment="<?php echo $idCommentaire ?>">Annuler</BUTTON>
														</DIV>
													</FORM>
													<DIV class="commentaire-actions text-right _hideForModif">
														<A href="javascript:void();" class="btn btn-xs btn-default _openComment" id-comment="<?php echo $idCommentaire ?>" title="Modifier ou supprimer votre commentaire"><SPAN class="glyphicon glyphicon-pencil"></SPAN></A>
													</DIV>
													<?php } ?>
												</div>
											</div>
										</DIV>
										<?php } ?>
										<?php if ($jeSuisAdherent) { ?>
										<DIV class="idee-commentaire">
											<div class="media">
												<div class="media-left">
													<IMG width="50" src="http://www.gravatar.com/avatar/<?php echo md5($_SESSION['mon_profil']['email']) ?>?s=50&d=blank" class="media-object gravatar" style="background-image:url('/img/gravatar/<?php echo strtoupper(substr($_SESSION['mon_profil']['prenom'],0,1)) ?>.jpg');">
												</div>
												<div class="media-body">
													<FORM method="post">
														<TEXTAREA name="texte" rows="3" style="width:100%" required placeholder="Ecrivez un nouveau commentaire"></TEXTAREA>
														<INPUT type="hidden" name="idee" value="<?php echo $idIdee ?>">
														<BUTTON type="submit" name="nouveauCommentaire" class="btn btn-success btn-sm">Envoyer</BUTTON>
													</FORM>
												</div>
											</div>
										</DIV>
										<?php } else { ?>
										<DIV class="idee-commentaire">
										<div class="media-body">
											Vous devez <a href="/forum/index.php?tab=equipe">adhérer au forum</a> pour réagir.
										</div></DIV>
										<?php } ?>
									</DIV>
								</DIV>
							</DIV>
						</DIV>
					</DIV>
				</DIV>
			<?php } ?>
			</DIV>
		</DIV>
<?php
} elseif (isset($_GET['tab']) and $_GET['tab'] == "equipe") { 
?>
		<!-------------------------------------------------- EQUIPE ----------------------------------------->
		<DIV class="row">
			<DIV class="col-md-12">
				<P>Voici les personnes qui ont adhéré à ce forum.</P>
				<?php if ($CONNECTED) { ?>
					<?php if (!$jeSuisAdherent) { ?>
					<P>Voulez-vous en faire partie ? <A href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#ModalSouscrire" data-forum="<?php echo $_SESSION['ce_forum']['titre'] ?>"><SPAN class="glyphicon glyphicon-ok"></SPAN> Rejoindre les adhérents de ce forum</A></P>
					<?php } else{ ?>
					<P><strong><span class="glyphicon glyphicon-ok"></span> Vous en faites partie !</strong></P>
					<?php } ?>
				<?php } else { ?>
				<P class="alert alert-warning">Vous devez <A href="/connexion/?back=/forum/index.php?tab=equipe">vous connecter ou vous inscrire</A> pour pouvoir adhérer à ce forum.</P>
				<?php } ?>
				</P>
				<?php
				foreach ($_SESSION['ce_forum']['adhesions'] as $email=>$adhesion) {
				?>
				<div class="media">
					<div class="media-left">
						<IMG width="100" src="http://www.gravatar.com/avatar/<?php echo md5($email) ?>?s=100&d=blank" class="media-object gravatar" style="background-image:url('/img/gravatar/<?php echo strtoupper(substr($adhesion['prenom'],0,1)) ?>.jpg');">
					</div>
					<div class="media-body">
						<h4><?php echo $adhesion['prenom'] ?></h4>
						<P><?php echo $adhesion['profil'] ?></P>
					</div>
				</div>
				<?php
				}
				?>
			</DIV>
		</DIV>
<?php
} elseif (isset($_GET['tab']) and $_GET['tab'] == "actualites") { 
?>
		<!-------------------------------------------------- ACTUALITES ----------------------------------------->
<?php
$selectNouvelles = "SELECT * FROM nouvelles WHERE lien='".$_SESSION['ce_forum']['projet']."' and archive=0 ORDER BY date DESC, id";
require $_SERVER['DOCUMENT_ROOT']."/includes/afficherNouvelles.php";
?>
<?php
}
?>
	</DIV>
</DIV>

<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>
</body>
</html>
