<?php
require $_SERVER['DOCUMENT_ROOT']."/includes/init.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dans la presse | <?= SITE_TITLE ?></title>
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
				<H1>Dans la presse</H1>
			</DIV>
		</DIV>
	</DIV>
</DIV>

<DIV class="strip">
	<DIV class="container">
		<DIV class="row">
			<DIV class="col-sm-12">
				<TABLE class="table table-striped">
					<TBODY>
					<?php
					$rows = sqlExecute("SELECT * FROM presse ORDER BY date DESC");
					while ($row = $rows->fetch()) {
					?>
						<TR>
							<TD><?php echo transformDate($row['date'],"dd/mm/yyyy") ?>
							<SPAN class="glyphicon glyphicon-chevron-right"></SPAN>
							<?php echo $row['source'] ?>
							<SPAN class="glyphicon glyphicon-chevron-right"></SPAN>
							<A href="<?php echo $row['lien'] ?>" target="_blank"><?php echo $row['titre'] ?></A> 
							</TD>
						</TR>
					<?php
					}
					?>
					</TBODY>
				</TABLE>
			</DIV>
		</DIV>
	</DIV>
</DIV>

<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>
</body>
</html>
