<?php require $_SERVER['DOCUMENT_ROOT']."/includes/init.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Foire aux questions | <?= SITE_TITLE ?></title>
    
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
				<H1>Foire aux questions</H1>
			</DIV>
		</DIV>
	</DIV>
</DIV>

<DIV class="strip">
	<DIV class="container">
		<DIV class="row">
			<DIV class="col-sm-12">
				<P>Nos FAQ sont classées par thèmes afin que vous puissiez trouver plus rapidement les réponses à vos questions.</P>
				<UL class="nav nav-tabs" role="tablist">
					<LI role="presentation" class="active"><a href="#en-general" role="tab" data-toggle="tab">En général</a></LI>
					<LI role="presentation"><a href="#projets" role="tab" data-toggle="tab">Projets</a></LI>
					<LI role="presentation"><a href="#forums" role="tab" data-toggle="tab">Forums</a></LI>
					<LI role="presentation"><a href="#inscriptions" role="tab" data-toggle="tab">Inscriptions</a></LI>
				</UL>
			</DIV>
		</DIV>
		<DIV class="tab-content">
    		<DIV role="tabpanel" class="tab-pane fade in active" id="en-general">
				<DIV class="row">
					<DIV class="col-sm-12">
						<H2>En général</H2>
					</DIV>
					<DIV class="col-md-4 col-sm-6">
						<H3>Interdum et malesuada ?</H3>
						<P>LInterdum et malesuada fames ac ante ipsum primis in faucibus. Cras dapibus velit ut urna pulvinar euismod id eget massa. Vivamus in felis arcu. Mauris erat metus, porta nec porttitor sit amet, faucibus id est. In ultrices neque nibh, in pulvinar velit gravida quis.</P>
						<P> Vestibulum vel nisi et dolor fringilla malesuada in at arcu. Morbi tincidunt posuere nibh sit amet cursus. Quisque justo diam, dapibus nec laoreet a, aliquet sed metus. Sed porttitor bibendum condimentum.</P>
					</DIV>
					<DIV class="col-md-4 col-sm-6">
						<H3>Morbi urna sem ?</H3>
						<P>Morbi urna sem, molestie at pharetra sit amet, bibendum sit amet erat. Quisque faucibus pulvinar commodo. Nam consequat auctor quam, eu luctus tellus scelerisque a. </P>
						<P>Nam non ipsum sed justo lobortis auctor id vel lacus. In non pretium orci.</P>
					</DIV>
					<DIV class="col-md-4 col-sm-6">
						<H3>In accumsan nulla in fringilla lobortis ?</H3>
						<P>Curabitur nec turpis ultricies magna aliquet venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. </P>
						<P>Cras in gravida dui, et malesuada nibh. Nam non venenatis ex. Aliquam ex turpis, dapibus a ligula vitae, pulvinar posuere magna. Nam ex nisi, gravida eget pulvinar ut, eleifend quis nunc. Nunc eu tortor in augue faucibus rhoncus.</P>
					</DIV>
				</DIV>
			</DIV>
    		<DIV role="tabpanel" class="tab-pane fade" id="projets">
				<DIV class="row">
					<DIV class="col-sm-12">
						<H2>Projets</H2>
					</DIV>
					<DIV class="col-md-4 col-sm-6">
						<H3>Comment soumettre un projet ?</H3>
						<P>Le Forum Citoyen permet à tout citoyen de solliciter un appui/une aide sur tout sujet d'intérêt général et dont il souhaiterait voir débattre publiquement ou non.				</P>
						<P>Pour ce faire, il suffit de compléter le formulaire accessible par le  bouton [SOUMETTEZ VOTRE PROJET] dans la barre de navigation en haut de la page. </P>
						<P>Un accusé de réception lui sera envoyé et les membres de l'équipe du Forum Citoyen examineront sa demande sur base de différents critères: l'importance du sujet pour l'intérêt général, le nombre de personnes impliquées, son impact sur la société civile, les ressources humaines disponibles, etc.</P>
					</DIV>
					<DIV class="col-md-4 col-sm-6">
						<H3>Comment voter pour un projet ?</H3>
						<P>Voter pour un projet c’est marquer son intérêt pour le sujet traité.<P>
						<P>Ce vote ne vous engage à rien et le nombre de votes sera prépondérant pour le passage du statut de projet à celui de forum.</P>
						<P>Un seul vote par projet est autorisé et cette procédure nécessite l'utilisation de cookies.</P>
						<P>Cette démarche se fait de manière anonyme.</P>
					</DIV>
					<DIV class="col-md-4 col-sm-6">
						<H3>Comment adhérer à un projet ?</H3>
						<P>Adhérer à un projet, c’est marquer son intérêt pour le sujet traité et proposer son aide pour y participer activement maintenant ou ultérieurement.</P>
						<P>L’adhésion vous permettra aussi de recevoir la newsletter, d’être informé de l’avancée du projet.</P>
						<P>L’adhésion nécessite que vous nous communiquiez vos coordonnées personnelles. Ces dernières ne sont pas publiées (et restent donc connues des seuls administrateurs du site) à l'exception de votre prénom et de votre localité.</P>
					</DIV>
					<DIV class="col-md-4 col-sm-6">
						<H3>Puis-je soumettre plusieurs projets ?</H3>
						<P>Oui, vous pouvez soumettre plusieurs projets. Toutefois, étant donné l'engagement que nous demandons au soumissionnaire d'un projet, une personne à la tête de beaucoup de projets éveillera notre méfiance.</P>
					</DIV>
					<DIV class="col-md-4 col-sm-6">
						<H3>Je souhaiterais soumettre un projet mais je suis seul pour le porter. Puis-je quand même le proposer ?</H3>
						<P>Oui. S'il est publié, d'autres personnes pourront peut-être vous rejoindre pour le porter.</P>
					</DIV>
					<DIV class="col-md-4 col-sm-6">
						<H3>Que devient un projet ?</H3>
						<P>S'il est retenu, le projet sera publié dans la rubrique projets du site et il sera demandé au public de marquer son intérêt pour le sujet proposé. Sur base du nombre de réactions et/ou de la décision du Forum Citoyen, il sera (ou non) classé dans la rubrique des forums du site et nous déciderons ensemble et avec vous de la procédure à mettre en place pour en débattre publiquement et défendre les propositions retenues.</P>
						<P>L'auteur de la demande de projet sera tenu au courant des décisions prises par l'équipe du Forum Citoyen.</P>
						<P>En cas de nécessité, une rencontre sera organisée entre le demandeur et le groupe de travail du Forum Citoyen.</P>
					</DIV>
				</DIV>
			</DIV>
			<DIV role="tabpanel" class="tab-pane fade" id="forums">
				<DIV class="row">
					<DIV class="col-sm-12">
						<H2>Forums</H2>
					</DIV>
					<DIV class="col-md-4 col-sm-6">
						<H3>Qu'est-ce qu'un forum ?</H3>
						<P>Un forum est un projet qui aura retenu l'attention de l'opinion publique (le nombre de votes) et/ou du Forum Citoyen et qui passera du statut de projet à celui de forum.<BR />
						Dès cet instant, une procédure sera mise en place entre l'auteur du projet et le Forum Citoyen afin de donner suite à la demande telle que formulée (débat public, communiqué de presse, action de terrain, interpellation du pouvoir, etc.).</P>
					</DIV>
					<DIV class="col-md-4 col-sm-6">
						<H3>Comment la page de présentation d'un forum est-elle conçue ?</H3>
						<P>Lorsque vous êtes sur un forum, vous trouvez sa description, comprenant de nombreux textes et images, dans l'onglet "Présentation". Comme les forums concernent des sujets très différents, le contenu de cette page est très variable et doit être adapté au mieux. Pour cette raison elle est mise en page par notre webmaster, au départ du projet qui l'a initié et en concertation avec l'initiateur du projet et son équipe.</P>
					</DIV>
					<DIV class="col-md-4 col-sm-6">
						<H3>Qu'est-ce qu'une idée ?</H3>
						<P>Une idée est constituée par toute proposition, commentaire ou suggestion émises par une personne intéressée par le sujet du forum.<BR />
						Afin d'éviter tout dérapage, ces propositions sont modérées. En cas de non publication, l'auteur est contacté pour l'informer de la décision prise ou pour demander une reformulation de la demande (voir conditions de publication dans la <a href="/charte.php">charte</a>).</P>
					</DIV>
					<DIV class="col-md-4 col-sm-6">
						<H3>Comment puis-je proposer une idée ?</H3>
						<P>Pour proposer une idée, vous devez vous inscrire et adhérer au forum concerné (nous n'acceptons pas les interventions anonymes). Vos coordonnées personnelles ne sont pas publiées (et restent donc connues des seuls administrateurs du site) à l'exception de votre prénom et de votre localité.</P>
						<P>Toute publication doit également respecter les règles éthiques de la <a href="/charte.php">charte</a>.</P>
					</DIV>
				</DIV>
			</DIV>
			<DIV role="tabpanel" class="tab-pane fade" id="inscriptions">
				<DIV class="row">
					<DIV class="col-sm-12">
						<H2>Inscriptions</H2>
					</DIV>
					<DIV class="col-md-4 col-sm-6">
						<H3>On voit la photo de certaines personnes et pourtant je ne vois pas comment mettre ma photo</H3>
						<P>En effet, il n'est pas possible de charger une photo "avatar" pour votre profil. Nous utilisons en fait le service de Gravatar, comme de nombreux sites (par exemple Doodle). Il vous suffit donc d'inscrire votre email sur <a href="https://fr.gravatar.com/" target="_blank">Gravatar</a>, et votre photo apparaîtra automatiquement sur notre site comme, probablement, sur d'autres où vous êtes inscrits.</P>
					</DIV>
					<DIV class="col-md-4 col-sm-6">
						<H3>Comment puis-je modifier mes données personnelles ?</H3>
						<P>La modification de ses propres données personnelles n'est pas encore possible. En attendant, <a href="/connexion/">quand vous êtes connecté</a>, un petit formulaire permet de demander au webmaster de corriger vos données. </P>
					</DIV>
					<DIV class="col-md-4 col-sm-6">
						<H3>Comment seront traitées mes données personnelles ?</H3>
						<P>Seuls les administrateurs du site peuvent voir vos données personnelles (à l'exception de votre mot de passe qui ne reste connu que de vous).</P>
						<P>Sur le site, si vous avez adhéré à un forum, les visiteurs pourront voir votre prénom, votre photo (si vous avez un compte Gravatar) et votre texte exposant votre motivation et votre intérêt pour ce forum.</P>
					</DIV>
					<DIV class="col-md-4 col-sm-6">
						<H3>Comment puis-je supprimer mes données personnelles ?</H3>
						<P>La suppression de vos données personnelles est possible en nous contactant via notre <a href="/contact.php">page de contact</a>.</P>
						<P>Si vous avez introduit un projet qui a été publié, cette action n'est plus possible pour des raisons de traçabilité.</P>
					</DIV>
				</DIV>
			</DIV>
		</DIV>
	</DIV>
</DIV>
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php"; ?>
</body>
</html>
