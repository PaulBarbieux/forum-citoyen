<?php require $_SERVER['DOCUMENT_ROOT']."/includes/init.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Comment ça fonctionne ? | <?= SITE_TITLE ?></title>
    
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
    <link href="/css/default.css" rel="stylesheet">
    <link href="/css/font-awesome.min.css" rel="stylesheet">
</head>

<body class="home <?php echo $bodyClasses ?>">

<?php include $_SERVER['DOCUMENT_ROOT']."/includes/nav.php"; ?>

<DIV class="strip strip-banner">
	<DIV class="container">
		<DIV class="row">
			<A href="/" class="strip-brand"><IMG src="/img/logo_ombre-50.png"></A>
			<DIV class="strip-title">
				<H1>Comment ça fonctionne ?</H1>
			</DIV>
		</DIV>
	</DIV>
</DIV>
<DIV class="strip">
	<DIV class="container">
		<DIV class="row">
			<DIV class="col-md-offset-2 col-md-3 col-sm-5">
				<IMG src="/img/schema/1_projet.png" class="img-responsive">
			</DIV>
			<DIV class="col-md-5 col-sm-7">
				<H2>1) Vous avez un projet en tête</H2>
				<P> Un sujet qui touche ####### vous interpelle et vous souhaitez en débattre avec d'autres , trouver une solution, interpeler les décideurs, chercher d'autres personnes intéressées... le Forum Citoyen peut vous aider. </P>
			</DIV>
		</DIV>
		<hr>
		<DIV class="row">
			<DIV class="col-md-offset-2 col-md-3 col-sm-5">
				<IMG src="/img/schema/2_soumission_projet.png" class="img-responsive">
			</DIV>
			<DIV class="col-md-5 col-sm-7">
				<H2>2) Vous soumettez un projet </H2>
				<P> Il suffit de compléter le formulaire accessible par le bouton [SOUMETTEZ VOTRE PROJET] dans la barre de navigation en haut de la page.</P>
				<P>Un accusé de réception vous sera envoyé et les membres de l'équipe du Forum Citoyen  examineront votre demande sur base de différents critères: l'importance du sujet pour l'intérêt général, le nombre de personnes impliquées, son impact sur la société civile, les ressources humaines disponibles, etc.</P>
				<P>Voici les types de sujets possibles qui pourraient être abordés: mobilité, social, santé, etc. (nous vous invitons à consulter le <a href="/manifeste.php#point4" target="_blank">point 4 de notre manifeste</a>).</P>
			</DIV>
		</DIV>
		<hr>
		<DIV class="row">
			<DIV class="col-md-offset-2 col-md-3 col-sm-5">
				<IMG src="/img/schema/3_projet_expose.png" class="img-responsive">
			</DIV>
			<DIV class="col-md-5 col-sm-7">
				<H2>3) Votre projet est publié</H2>
				<P> S'il respecte l'esprit du manifeste et les conditions de publication de la charte, votre sujet est publié sur le site et devient alors visible de tous. Il est alors soumis au vote des citoyens (qui soutiennent votre idée, sans plus) ou à l'adhésion (de tous ceux qui souhaitent participer activement  aux débats).</P>
			</DIV>
		</DIV>
		<hr>
		<DIV class="row">
			<DIV class="col-md-offset-2 col-md-3 col-sm-5">
				<IMG src="/img/schema/4_election_forum.png" class="img-responsive">
			</DIV>
			<DIV class="col-md-5 col-sm-7">
				<H2>4) Le projet devient forum </H2>
				<P> S'il est plébiscité et qu'un nombre suffisant de personnes adhérent à votre projet, il acquiert le statut de forum. Le nombre de votants et d'adhérents doit permettre de mener le forum à termes, l'équipe du Forum Citoyen n'ayant pas les capacités de porter seule tous les projets proposés.  </P>
			</DIV>
		</DIV>
		<hr>
		<DIV class="row">
			<DIV class="col-md-offset-2 col-md-3 col-sm-5">
				<IMG src="/img/schema/5_forum.png" class="img-responsive">
			</DIV>
			<DIV class="col-md-5 col-sm-7">
				<H2>5) Le forum r&eacute;colte les id&eacute;es  </H2>
				<P> Le forum doit pouvoir évoluer et s'enrichir de toutes les propositions constructives des citoyens. Il est possible, par le biais du site, de déposer des idées, émettre des suggestions, publier des schémas ou croquis... qui seront pris en compte par l'équipe chargée de gérer le forum. </P>
			</DIV>
		</DIV>
		<hr>
		<DIV class="row">
			<DIV class="col-md-offset-2 col-md-3 col-sm-5">
				<IMG src="/img/schema/6_action.png" class="img-responsive">
			</DIV>
			<DIV class="col-md-5 col-sm-7">
				<H2>6) Le Forum Citoyen formule une proposition, décide d’une action… qui réponde à la demande</H2>
				<P> Toutes les personnes impliquées dans le forum prennent en charge la dynamique du forum de manière à pouvoir répondre à la demande qui y est formulée. Débats publics, interpellations diverses, actions de terrain, forums citoyens... tout est mis en œuvre pour aboutir à une solution et formuler une proposition concrète et réaliste. Celle-ci fait l'objet d'une large diffusion auprès de la population.</P>
			</DIV>
		</DIV>
		<hr>
		<DIV class="row">
			<DIV class="col-sm-12">
				<P class="meta text-center">Schémas conçus à l'aide des images de <a href="http://www.freepik.com" target="_blank">Freepik</a></P>
			</DIV>
		</DIV>
	</DIV>
</DIV>

<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>
</body>
</html>
