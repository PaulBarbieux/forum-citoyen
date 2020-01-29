<?php require $_SERVER['DOCUMENT_ROOT']."/includes/init.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notre charte | <?= SITE_TITLE ?></title>
    
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
				<H1>Notre charte</H1>
			</DIV>
		</DIV>
	</DIV>
</DIV>

<DIV class="strip">
	<DIV class="container">
		<DIV class="row">
			<DIV class="col-sm-12">
				<h2>Aliquam sollicitudin</h2>
				<p>Nibh vel dignissim tincidunt, turpis eros consectetur risus, finibus pharetra odio ante elementum mauris. Donec nec odio et quam volutpat fringilla. In eu lacus sit amet neque imperdiet convallis sed ac leo. Proin egestas, magna non auctor tincidunt, leo nulla semper dui, eu consequat nibh odio sed risus. Pellentesque ornare quam eget nisi suscipit pellentesque. Fusce efficitur tortor tincidunt, pellentesque nulla a, blandit leo. Quisque id arcu pulvinar, commodo ante eu, tincidunt mi. Nam a pellentesque metus. Integer ut varius magna. Sed aliquam ex nec libero volutpat, at ultrices lacus accumsan. Ut porttitor enim sed lectus euismod, nec varius orci posuere. Sed ornare lorem in orci ultricies cursus. Donec vel vehicula elit. Interdum et malesuada fames ac ante ipsum primis in faucibus. </p>
				<h2> Sed pharetra nibh turpis</h2>
				<p>Ut interdum lorem sagittis placerat. Aliquam bibendum mollis mauris, vel accumsan dolor auctor eu. Praesent sit amet dui eu nunc gravida laoreet. Nunc faucibus bibendum semper. Ut sit amet lectus eu leo imperdiet facilisis. Mauris lacus massa, aliquam nec turpis sit amet, ultrices pulvinar ipsum. Maecenas sed libero mattis, tincidunt leo vel, molestie dui. Aenean imperdiet rutrum est, vitae placerat diam rutrum id. Suspendisse potenti. Quisque ultrices magna accumsan magna ultrices dapibus. Suspendisse suscipit in massa vel pretium. Suspendisse in malesuada mi. In at porttitor orci. Nunc eu auctor diam. Nulla volutpat lacus eget dui commodo, vitae varius nisl consequat. </p>
				<h2> Interdum et malesuada fames ac ante ipsum primis in faucibus</h2>
				<p>Cras dapibus velit ut urna pulvinar euismod id eget massa. Vivamus in felis arcu. Mauris erat metus, porta nec porttitor sit amet, faucibus id est. In ultrices neque nibh, in pulvinar velit gravida quis. Vestibulum vel nisi et dolor fringilla malesuada in at arcu. Morbi tincidunt posuere nibh sit amet cursus. Quisque justo diam, dapibus nec laoreet a, aliquet sed metus. Sed porttitor bibendum condimentum. Morbi urna sem, molestie at pharetra sit amet, bibendum sit amet erat. Quisque faucibus pulvinar commodo. Nam consequat auctor quam, eu luctus tellus scelerisque a.</p>
				</DIV>
			<DIV class="col-sm-12">
				<?php if (!$CONNECTED) { ?>
				<HR />
				<P>Vous voulez nous soutenir et adhérer à notre manifeste ? <A href="/connexion/index.php" class="btn btn-sm btn-primary">Enregistrez-vous</A></p>
				<?php } ?>
			</DIV>
	  </DIV>
	</DIV>
</DIV>

<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>
</body>
</html>
