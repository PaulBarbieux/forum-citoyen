<?php require $_SERVER['DOCUMENT_ROOT']."/includes/init.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>L'équipe | <?= SITE_TITLE ?></title>
    
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

<body class="<?php echo $bodyClasses ?>">

<?php include $_SERVER['DOCUMENT_ROOT']."/includes/nav.php"; ?>

<DIV class="strip strip-banner">
	<DIV class="container">
		<DIV class="row">
			<A href="/" class="strip-brand"><IMG src="/img/logo_ombre-50.png"></A>
			<DIV class="strip-title">
				<H1>L'équipe</H1>
			</DIV>
		</DIV>
	</DIV>
</DIV>

<DIV class="strip">
	<DIV class="container">
		<DIV class="row">
			<?php
			/*
				Des DIV clearfix sont positionnés tous les 2, 3 et 4 blocs : ils empêchent certains blocs de se mettre en dessous du précédent
				plutôt que de se mettre à une nouvelle ligne, suivant la largeur d'écran (problème à cause de la hauteur inégale des blocs).
				En ajoutant un nouveau membre, il faut donc déplacer ces DIV qui le suivent.
				Les règles sont :
				 - un visible-sm-block tous les deux blocs
				 - un visible-md-block tous les trois blocs
				 - un visible-lg-block tous les quatre blocs
			*/
			?>
			<!-- 1 -->
			<DIV class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
				<H2>Jeanne d'Arque</H2>
				<img src="/img/equipe/photo_ipsum_femme.jpg" class="img-responsive">
				<UL class="list-bordered">
				    <li>Lobyiste pour le pétrole.</li>
					<li>Citoyenne engagée.</li>
					<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec convallis dapibus odio, id tempus turpis vulputate gravida. Nullam in tortor efficitur, volutpat neque vel, dignissim libero.</li>
				</UL>
			</DIV>
			<!-- 2 -->
			<DIV class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
				<H2>Henry Encore</H2>
				<img src="/img/equipe/photo_ipsum_homme.jpg" class="img-responsive">
				<UL class="list-bordered">
				    <LI>Responsable d'une ASBL pour les OGM.</LI>
					<LI>Membre fondateur du comité de quartier "Not In My Backward".</LI>
				    <LI>Membre du collectif "Après nous les mouches".</LI>
					<LI>Quisque vel vestibulum erat. Nullam vulputate nisi at venenatis blandit. Nulla turpis velit, vulputate vitae laoreet at, dignissim at nisi. Suspendisse aliquet eleifend eros, eu pharetra justo luctus a. Nam efficitur feugiat condimentum. </LI>
				</UL>
			</DIV>
 			<div class="clearfix visible-sm-block"></div>
			<!-- 3 -->
			<DIV class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
				<H2>Pellentesque Facilisis</H2>
				<img src="/img/equipe/photo_ipsum_homme.jpg" class="img-responsive">
				<UL class="list-bordered">
				    <LI>Quisque vel vestibulum erat.</LI>
					<LI>Uspendisse aliquet eleifend eros, eu pharetra justo luctus a. Nam efficitur feugiat condimentum.</LI>
				    <LI>Nullam vulputate nisi at venenatis blandit.</LI>
					<LI>Nulla turpis velit, vulputate vitae laoreet at, dignissim at nisi. Suspendisse aliquet eleifend eros, eu pharetra justo luctus a. Nam efficitur feugiat condimentum. </LI>
				</UL>
			</DIV>
			<div class="clearfix visible-md-block"></div>
			<!-- 4 -->
			<DIV class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
				<H2>Maecenas Vitae</H2>
				<img src="/img/equipe/photo_ipsum_femme.jpg" class="img-responsive">
				<UL class="list-bordered">
					<li>Curabitur faucibus, leo sed iaculis sodales, urna mauris fermentum velit, in semper massa neque auctor eros.</li>
					<li> Etiam id est porttitor, pharetra mi quis, gravida sem. Mauris suscipit ornare purus, eget rutrum lacus porttitor eu.</li>
				</UL>
			</DIV>
		</DIV>
	</DIV>
</DIV>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>
</body>
</html>
