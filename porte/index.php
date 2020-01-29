<?php
require $_SERVER['DOCUMENT_ROOT']."/includes/init-closed.php";

$error = false;
$values['demande'] = "";
$montrerFormInscription = true;

if (isset($_POST['connect'])) {
	// Identification
	$email = $_POST['email'];
	$passe = $_POST['passe'];
	$result = sqlExecute("SELECT * FROM personnes WHERE email='".$email."' AND role='admin'");
	if ($row = $result->fetch()) {
		if (crypt($passe, $row['passe']) == $row['passe']) {
			// Stockage des données de la personne dans une session
			$_SESSION['entree'] = true;
			$_SESSION['mon_profil'] = $row;
			$CONNECTED = true;
			$action = "completed";
			header("location:/");
		} else {
			// Mot de passe invalide
			$error = true;
			$message = "Désolé : email inconnu ou mot de passe invalide.";
		}
	} else {
		// Personne inconnue
		$error = true;
		$message = "Désolé : email inconnu ou mot de passe invalide.";
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
    <link href="/css/default.css" rel="stylesheet">
</head>

<body class="home <?php echo $bodyClasses ?>">

<?php include $_SERVER['DOCUMENT_ROOT']."/includes/nav.php"; ?>

<DIV class="strip strip-banner">
	<DIV class="container">
		<DIV class="row">
			<A href="/" class="strip-brand"><IMG src="/img/logo_ombre-50.png"></A>
			<DIV class="strip-title">
				<H1><?php if ($CONNECTED) { echo $_SESSION['mon_profil']['prenom']." ".$_SESSION['mon_profil']['nom']; } else { ?>Connexion<?php } ?></H1>
			</DIV>
		</DIV>
	</DIV>
</DIV>

<DIV class="strip">
	<DIV class="container">
		<DIV class="row">
			<DIV class="col-sm-12">
				<?php include $_SERVER['DOCUMENT_ROOT']."/includes/alerts.php"; ?>
			</DIV>
		</DIV>
		<DIV class="row">
			<DIV class="col-sm-4">
				<H1>Connectez-vous</H1>
				<DIV class="panel panel-primary">
					<DIV class="panel-body">
						<form method="post">
							<div class="form-group">
								<label for="titre">Votre adresse email</label>
								<input type="email" name="email" class="form-control" required>
							</div>
							<div class="form-group">
								<label for="titre">Votre mot de passe</label>
								<input type="password" name="passe" class="form-control" required>
							</div>
							<BUTTON type="submit" name="connect" class="btn btn-primary btn-block"><SPAN class="glyphicon glyphicon-log-in"></SPAN> S'identifier</BUTTON>
						</form>
					</DIV>
				</DIV>
			</DIV>
			<DIV class="col-sm-8">
				<H1>Accès réservé aux administrateurs du site</H1>
				<p>Pour afficher le site pendant que celui-ci est fermé, il est nécessaire de vous identifier et d'avoir le rôle d'administrateur.</p>
				<p>Si vous ne possédez pas le rôle d'administrateur, l'accès vous sera automatiquement refusé.</p>
			</DIV>
		</DIV>
	</DIV>
</DIV>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>
</body>
</html>
