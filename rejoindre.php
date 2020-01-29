<?php require $_SERVER['DOCUMENT_ROOT']."/includes/init.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rejoignez-nous | <?= SITE_TITLE ?></title>
    
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
				<H1>Nous rejoindre</H1>
			</DIV>
		</DIV>
	</DIV>
</DIV>

<DIV class="strip">
	<DIV class="container">
		<DIV class="row">
			<DIV class="col-sm-12">
				<P>Sed pharetra nibh turpis, ut interdum lorem sagittis placerat. Aliquam bibendum mollis mauris, vel accumsan dolor auctor eu. Praesent sit amet dui eu nunc gravida laoreet. Nunc faucibus bibendum semper. Ut sit amet lectus eu leo imperdiet facilisis. Mauris lacus massa, aliquam nec turpis sit amet, ultrices pulvinar ipsum. Maecenas sed libero mattis, tincidunt leo vel, molestie dui. Aenean imperdiet rutrum est, vitae placerat diam rutrum id. Suspendisse potenti. Quisque ultrices magna accumsan magna ultrices dapibus. </P>
				<P>Suspendisse suscipit in massa vel pretium. Suspendisse in malesuada mi. In at porttitor orci. Nunc eu auctor diam. Nulla volutpat lacus eget dui commodo, vitae varius nisl consequat.</P>
			</DIV>
		</DIV>
	</DIV>
</DIV>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>
</body>
</html>
