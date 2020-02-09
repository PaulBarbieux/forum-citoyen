<?php require $_SERVER['DOCUMENT_ROOT']."/includes/init.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Politique de protection des données | <?= SITE_TITLE ?></title>
    
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
				<H1>Politique de protection des données</H1>
			</DIV>
		</DIV>
	</DIV>
</DIV>

<DIV class="strip">
  <DIV class="container">
    <DIV class="row">
			<DIV class="col-sm-12">
				<h2>Définitions</h2>
				<ul>
				  <li>Le terme « site » désigne le site web « <?= $_SERVER['SERVER_NAME'] ?> » administré par l'<?= ASBL ?>.</li>
			  <li> Les « cookies » sont des petits fichiers d'information enregistrés dans votre navigateur. La majorité des sites web en utilisent pour faciliter votre navigation et améliorer votre confort. Votre navigateur permet de refuser l'utilisation des cookies. Dans ce cas, le site risque de ne pas fonctionner correctement.</li>
			  <li> Le terme « Forum Citoyen » désigne la plateforme d’échange publiée sur le site.</li>
			  </ul>
  </DIV>
		  <DIV class="col-sm-12">
			  <h2>Déclaration</h2>
			  <p>Vos données personnelles sont protégées conformément au règlement européen 2016/679 du Parlement européen et du Conseil du 27 avril 2016 (entré en application le 25 mai 2018) relatif à la protection des personnes physiques à l'égard du traitement des données à caractère personnel et à la libre circulation de ces données (RGPD).</p>
		</DIV>
			<DIV class="col-sm-6">
				<h3>Données collectées et traitées</h3>
				<p>Nous veillons à ne collecter que des données strictement nécessaires à la finalité des traitements mis en œuvre. Des données personnelles sont ainsi collectées et traitées afin de vous informer, de répondre à vos questions, de publier vos projets et vos idées et de comptabiliser les votes. En particulier :</p>
				<ul>
					<li>Lorsque vous consultez notre site, nous utilisons des cookies pour le temps de votre visite. Ils sont détruits quand vous quittez le site. En aucun cas nous les utilisons pour vous « tracker ».</li>
					<li> Lorsque vous nous contactez à l’aide du formulaire « Contactez-nous », vos noms et prénoms, ainsi que votre adresse mail sont exclusivement utilisés dans le cadre de nos échanges bilatéraux. </li>
					<li> Lorsque vous adhérez au manifeste, en soutien au Forum Citoyen, vos prénom, code postal, commune, photo de profil et votre description sont publiés sur la page « ils nous soutiennent ». Ces informations sont publiques.</li>
					<li> Lorsque vous adhérez à un forum, que vous souscrivez à un projet ou que soumettez un projet, toutes les informations que vous communiquez sont publiées sur le site et deviennent publiques. En outre, en publiant le contenu sur le site <?= $_SERVER['SERVER_NAME'] ?> vous cédez tous les droits de propriété à l’<?= ASBL ?>.</li>
				</ul>
  			</DIV>
			<DIV class="col-sm-6">
				<H3>Le responsable du traitement</H3>
				<P>L’<?= ASBL ?> est représentée par son Délégué à la Gestion Journalière. Il est responsable du traitement des données à caractère personnel qu’il traite dans le cadre de l’exécution de ses missions légales. Ceci implique qu’il détermine, seul ou conjointement avec les administrateurs de l’ASBL, les finalités et les moyens du traitement de ces données à caractère personnel.</P>
		 	</DIV>
			<DIV class="col-sm-6">
				<H3>Utilisation des cookies</H3>
				<p>Le site utilise les cookies suivants&nbsp;:</p>
				<ul>
			  	  <li>PHPSESSID : expire lorsque vous quittez la navigation</li>
					<li>warning_cookie : expire 24 h après création</li>
					<li>welcome : expire 1 heure après création </li>
					<li>aucun, un ou plusieuts forum_citoyen_nnnnnnnnnn : expire 1 an après sa création</li>
			  </ul>
  			</DIV>
			<DIV class="col-sm-6">
				<H3>Sécurité</H3>
				<P>Nous garantissons la sécurité (intégrité et confidentialité) de vos données personnelles. Elles sont stockées sur un serveur hébergé chez O2Switch et protégées notamment contre l'accès non autorisé, l'utilisation illégitime, la perte et des modifications non autorisées.</P>
  			</DIV>
			<DIV class="col-sm-6">
				<H3>Droit d'accès, de rectification, à la portabilité, d’opposition, d’effacement</H3>
				<P>Vous avez des droits concernant les données personnelles que nous utilisons : accès, rectification, portabilité, opposition, effacement etc.</P>
				<P>Pour v&eacute;rifier vos donn&eacute;es, connectez-vous et allez dans la page de votre profil.</P>
				<P>Vous pouvez exercer vos droits en utilisant le formulaire &quot;Demande de correction&quot; dans la page de votre profil, ou en adressant un courrier électronique à <A href="mailto:<?= WEBMASTER_EMAIL ?>?subject=<?= $_SERVER['SERVER_NAME'] ?> : mes données privées"><?= WEBMASTER_EMAIL ?>
				  </A>.</P>
  			</DIV>
			<DIV class="col-sm-6">
				<h3>Réclamations</h3>
				<P> Si vous estimez que l'<?= ASBL ?> n’a pas traité vos données personnelles conformément aux réglementations en vigueur, vous avez le droit d’introduire une réclamation auprès de l'Autorité de protection des données :</P>
				<P>Autorité de protection des données<br>
			      Rue de la Presse 35<br>
			      1000 Bruxelles<br>
			      <a href="mailto:contact@apd-gba.be">contact@apd-gba.be</a><br>
			      <a href="http://www.autoriteprotectiondonnees.be" target="_blank">www.autoriteprotectiondonnees.be</a></P>
			</DIV>
    	</DIV>
	</DIV>
</DIV>

<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>
</body>
</html>
