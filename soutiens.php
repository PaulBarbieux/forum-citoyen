<?php require $_SERVER['DOCUMENT_ROOT']."/includes/init.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ils nous soutiennent | <?= SITE_TITLE ?></title>
    
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
    <link href="/css/font-awesome.min.css" rel="stylesheet">
	<!-- Masonry -->
	<script src="/js/masonry.pkgd.min.js"></script>
	<SCRIPT type="text/javascript">
	jQuery(document).ready(function(){
		// Layout Masonry
		$('.grid').masonry({
			  itemSelector: '.grid-item',
			  columnWidth: 380
		});
		// Provoquer Masonry après un changement de tab
		$('A[data-toggle="tab"]').on('shown.bs.tab', function (e) {
			$('.grid').masonry();
		});
	});
	</SCRIPT>
</head>

<body class="home <?php echo $bodyClasses ?>">

<?php include $_SERVER['DOCUMENT_ROOT']."/includes/nav.php"; ?>

<DIV class="strip strip-banner">
	<DIV class="container">
		<DIV class="row">
			<A href="/" class="strip-brand"><IMG src="/img/logo_ombre-50.png"></A>
			<DIV class="strip-title">
				<H1>Ils nous soutiennent</H1>
			</DIV>
		</DIV>
	</DIV>
</DIV>

<DIV class="strip">
	<DIV class="container">
		<DIV class="row">
			<DIV class="col-sm-12">
				<UL id="TabsSoutiens" class="nav nav-tabs" role="tablist">
					<LI role="presentation" class="active"><a href="#citoyens" role="tab" data-toggle="tab">Citoyens</a></LI>
					<LI role="presentation"><a href="#associations" role="tab" data-toggle="tab">Associations</a></LI>
				</UL>
			</DIV>
		</DIV>
		<DIV class="tab-content">
    		<DIV role="tabpanel" class="tab-pane fade in active" id="citoyens">
				<DIV class="row">
					<DIV class="col-sm-12">
						<P>Voici les personnes qui soutiennent <A href="manifeste.php">notre manifeste</A>.
						<?php if (!$CONNECTED) { ?>
						Vous voulez nous soutenir ? <A href="/connexion/index.php" class="btn btn-sm btn-primary">Enregistrez-vous</A>
						<?php } ?>
						</P>
					</DIV>
					<DIV class="col-sm-12 grid">
						<?php
						$rows = sqlExecute("SELECT * FROM personnes WHERE manifeste=1 AND code_confirmation IS NULL ORDER BY date_inscription DESC");
						while($row = $rows->fetch()) {
						?>
						<DIV class="panel grid-item">
							<DIV class="panel-body">
								<div class="media">
									<div class="media-left">
										<IMG width="100" class="media-object gravatar" style="background-image:url('/img/gravatar/<?php echo strtoupper(substr($row['prenom'],0,1)) ?>.jpg');" src="http://www.gravatar.com/avatar/<?php echo md5($row['email']) ?>?s=100&d=blank">
									</div>
									<div class="media-body">
										<h3><?php echo $row['prenom'] ?></h3>
										<P class="meta"><?php echo $row['code_postal']." ".$row['commune'] ?></P>
										<P><?php echo nl2br($row['presentation']) ?></P>
									</div>
								</div>
							</DIV>
						</DIV>
						<?php
						}
						?>
					</DIV>
				</DIV>
			</DIV>
    		<DIV role="tabpanel" class="tab-pane fade" id="associations">
				<DIV class="row">
					<DIV class="col-sm-12">
						<P>Voici les associations qui soutiennent <A href="manifeste.php">notre manifeste</A>.
						Vous voulez nous soutenir avec votre association ? <A href="/contact.php" class="btn btn-sm btn-primary">Contactez-nous</A></P>
					</DIV>
				</DIV>
				<DIV class="row">
					<DIV class="col-sm-12 grid">
						<div class="grid-item">
							<a href="http://www.associations21.org" target="_blank">
								<img src="/img/supports/associations_21.png" class="img-responsive">
							</a>
						</div>
						<div class="grid-item">
							<a href="https://www.amisdelaterre.be" target="_blank">
								<img src="/img/supports/amis_de_la_terre.png" class="img-responsive">
							</a>
						</div>
						<div class="grid-item">
							<a href="http://www.objecteursdecroissance.be" target="_blank">
								<img src="/img/supports/mpoc_be.png" class="img-responsive">
							</a>
						</div>
						<div class="grid-item">
							<a href="http://www.n2080.be" target="_blank">
								<img src="/img/supports/namur_2080.png" class="img-responsive">
							</a>
						</div>
						<div class="grid-item">
							<a href="http://namurbanite.over-blog.com" target="_blank">
								<img src="/img/supports/namurbanite.png" class="img-responsive">
							</a>
						</div>
						<div class="grid-item">
							<a href="https://www.facebook.com/Nuitdebout-Namur-706645572772499" target="_blank">
								<img src="/img/supports/nuit_debout_namur.png" class="img-responsive">
							</a>
						</div>
						<div class="grid-item">
							<a href="http://www.sireas.be" target="_blank">
								<img src="/img/supports/sireas.png" class="img-responsive">
							</a>
						</div>
					</div>
				</div>
			</DIV>
		</DIV>
	</DIV>
</DIV>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>
</body>
</html>
