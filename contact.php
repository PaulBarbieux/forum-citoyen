<?php
require $_SERVER['DOCUMENT_ROOT']."/includes/init.php";

if (!isset($values['inputName'])) $values['inputName'] = "";
if (!isset($values['inputEmail'])) $values['inputEmail'] = "";
if (!isset($values['inputSubject'])) $values['inputSubject'] = "";
if (!isset($values['inputMessage'])) $values['inputMessage'] = "";

if (isset($_POST['send'])) {
	$action = "send";
	// Robot ?
	if (isItHuman() === false) {
		// Oui, un robot ! Ne rien signaler, juste rafraîchir la page
		header("Location: contact.php");
		exit;
	}
	foreach ($_POST as $input=>$value) {
		$value = trim(strip_tags($value));
		$values[$input] = $value;
		switch($input) {
			case "start" :
			case "last_name" :
				// Ne rien faire : anti-spam
				break;
			case "inputName" :
				if ($value == "") {
					$error = true;
					$message = "Veuillez donner votre nom et prénom s.v.p.";
				}
				break;
			case "inputEmail" :
				if (!$error) {
					if ($value == "") {
						$error = true;
						$message = "Veuillez donner votre adresse email s.v.p.";
					} elseif (!isItEmail($value)) {
						$error = true;
						$message = "Votre adresse email n'est pas correcte.";
					}
				}
				break;
			case "inputSubject" :
				if (!$error) {
					if ($value == "") {
						$error = true;
						$message = "Veuillez choisir un sujet.";
					}
				}
				break;
			case "inputMessage" :
				if (!$error) {
					if ($value == "") {
						$error = true;
						$message = "Vous n'avez pas écrit de message.";
					}
				}
				break;
		}
	}
	if (!$error) {
		// Traitement du sujet
		if (isset($FORUMS[$values['inputSubject']])) {
			$subjectText = "Forum <A href='http://".$_SERVER['SERVER_NAME']."/forum/?nom=".$values['inputSubject']."'>".$FORUMS[$values['inputSubject']]['titre']."</A>";
		} elseif (isset($PROJETS[$values['inputSubject']])) {
			$subjectText = "Projet <A href='http://".$_SERVER['SERVER_NAME']."/projet/?id=".$values['inputSubject']."'>".$PROJETS[$values['inputSubject']]['titre']."</A>";
		} else {
			$subjectText = $values['inputSubject'];
		}
		require $_SERVER['DOCUMENT_ROOT']."/includes/prepareMail.php";
		$body = utf8_decode("
			<P>Message de ".$values['inputName']." (<A href='mailto:".$values['inputEmail']."'>".$values['inputEmail']."</A>)</P>
			<P>Sujet : ".$subjectText."</P>
			<P>".nl2br($values['inputMessage'])."</P>"
		);
		$mail->Body = $body;
		$mail->AddAddress(ADMIN_EMAIL, SITE_TITLE);
		$mail->AddCC("paul.barbieux@gmail.com","Paul Barbieux");
		$mail->Subject = SITE_TITLE." : message de ".$values['inputName'];
		if (TESTING) {
			print "<DIV class='mail-body-test'>".$body."</DIV>";
		} elseif (!$mail->send()) {
			print "Oups ! Erreur technique lors de l'envoi !<br>".$mail->ErrorInfo;
			exit;
		}
		$action = "completed";
		$message = "Votre message a été envoyé. Merci.";
	}
} else {
	$action = "init";
}


?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Contactez-nous | <?= SITE_TITLE ?></title>
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
				<H1>Contact</H1>
			</DIV>
		</DIV>
	</DIV>
</DIV>
<DIV class="strip">
    <DIV class="container">
        <DIV class="row">
            <DIV class="col-md-6">
				<?php require $_SERVER['DOCUMENT_ROOT']."/includes/alerts.php" ?>
				<?php if ($action != "completed") { ?>
                <form class="form-horizontal" method="post">
                    <input type="hidden" name="start" value="<?php echo time() ?>">
                    <input type="hidden" name="last_name" >
                    <div class="form-group">
                        <label for="inputName" class="col-md-3 control-label">Prénom et nom</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="inputName" value="<?php echo $values['inputName'] ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail" class="col-md-3 control-label">Email</label>
                        <div class="col-md-9">
                            <input type="email" class="form-control" name="inputEmail" value="<?php echo $values['inputEmail'] ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputSubject" class="col-md-3 control-label">Sujet</label>
						<div class="col-md-9">
							<select name="inputSubject" class="form-control">
								<option value="" <?php if ($values['inputSubject'] == "") echo "selected" ?>></option>
								<optgroup label="À propos du site et nos activités :">
									<option value="Concernant le site" <?php if ($values['inputSubject'] == "Concernant le site") echo "selected" ?>>Concernant le site</option>
									<option value="Nous rejoindre" <?php if ($values['inputSubject'] == "Nous rejoindre") echo "selected" ?>>Nous rejoindre</option>
								</optgroup>
								<optgroup label="À propos d'un forum :">
								<?php foreach($FORUMS as $idForum=>$forum) { ?>
									<option value="<?php echo $idForum ?>" <?php if ($values['inputSubject'] == $idForum) echo "selected" ?>><?php echo $forum['titre'] ?></option>
								<?php } ?>
								</optgroup>
								<optgroup label="À propos d'un projet :">
								<?php foreach($PROJETS as $idProjet=>$projet) { ?>
									<option value="<?php echo $idProjet ?>" <?php if ($values['inputSubject'] == $idProjet) echo "selected" ?>><?php echo $projet['titre'] ?></option>
								<?php } ?>
								</optgroup>
								<optgroup label="À propos de vous :">
									<option value="Donnée erronée dans votre profil" <?php if ($values['inputSubject'] == "Donnée erronée dans votre profil") echo "selected" ?>>Donnée erronée dans votre profil</option>
									<option value="Problème de mot de passe" <?php if ($values['inputSubject'] == "Problème de mot de passe") echo "selected" ?>>Problème de mot de passe</option>
								</optgroup>
							</select>
						</div>
                    </div>
                    <div class="form-group">
                        <label for="inputMessage" class="col-md-3 control-label">Message</label>
						<div class="col-md-9">
							<textarea name="inputMessage" class="form-control" rows="10" required><?php echo $values['inputMessage'] ?></textarea>
						</div>
					</div>
                    <div class="form-group">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" name="send" class="btn btn-success"><span class="glyphicon glyphicon-send"></span> Envoyer</button>
                        </div>
                    </div>
                </form>
				<?php } ?>
            </DIV>
			<DIV class="col-md-6">
				<P>Interdum et malesuada fames ac ante ipsum primis in faucibus. Cras dapibus velit ut urna pulvinar euismod id eget massa. Vivamus in felis arcu. Mauris erat metus, porta nec porttitor sit amet, faucibus id est. In ultrices neque nibh, in pulvinar velit gravida quis. Vestibulum vel nisi et dolor fringilla malesuada in at arcu.</P>
			</DIV>
        </DIV>
    </DIV>
</DIV>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>
</body>
</html>
