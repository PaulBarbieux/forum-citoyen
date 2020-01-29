<?php require $_SERVER['DOCUMENT_ROOT']."/includes/init.php"; ?>
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
    <link href="/css/default.css" rel="stylesheet">
</head>

<body class="home <?php echo $bodyClasses ?>">

<?php include $_SERVER['DOCUMENT_ROOT']."/includes/nav.php"; ?>

<DIV class="strip strip-banner">
	<DIV class="container">
		<DIV class="row">
			<DIV class="col-sm-12">
				<H1>Erreur 404</H1>
			</DIV>
		</DIV>
	</DIV>
</DIV>
<DIV class="strip">
	<DIV class="container">
		<DIV class="row">
			<DIV class="col-sm-12">
				<H2>Page non trouvée</H2>
				<P>Désolé, la page que vous cherchez n'existe pas (ou plus) sur le serveur.</P>
				<P>Si vous avez saisi manuellement une URL, ou utilisé un signet, merci de vérifier vos sources. </P>
			</DIV>
		</DIV>
	</DIV>
</DIV>

</body>
</html>
