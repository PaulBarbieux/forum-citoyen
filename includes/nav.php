<nav class="navbar navbar-default navbar-fixed-top">
<div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
			<span class="sr-only">Toggle navigation</span> 
			<span class="icon-bar"></span> 
			<span class="icon-bar"></span> 
			<span class="icon-bar"></span>
		</button>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
			<li class="dropdown <?php if ($THIS_PAGE['path'] == "/fonctionnement.php" or $THIS_PAGE['path'] == "/faq.php") echo "active" ?>"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Comment ça marche<span class="caret"></span></a>
                <ul class="dropdown-menu">
            		<li <?php if ($THIS_PAGE['path'] == "/fonctionnement.php") echo "class='active'" ?>><a href="/fonctionnement.php">Comment ça fonctionne ?</a></li>
					<li <?php if ($THIS_PAGE['path'] == "/faq.php") echo "class='active'" ?>><a href="/faq.php">Foire Aux Questions</a></li>
				</ul>
			</li>
            <li class="dropdown hidden-sm hidden-xs <?php if ($THIS_PAGE['path'] == "/nouvelles/index.php" or $THIS_PAGE['path'] == "/nouvelles/presse.php") echo "active" ?>">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Actualités<span class="caret"></span></a>
				<ul class="dropdown-menu">
					<li <?php if ($THIS_PAGE['path'] == "/nouvelles/index.php") echo "class='active'" ?>><A href="/nouvelles/index.php">Toutes les nouvelles</A></li>
					<li <?php if ($THIS_PAGE['path'] == "/nouvelles/presse.php") echo "class='active'" ?>><A href="/nouvelles/presse.php">Dans la presse</A></li>
				</ul>
			</li>
            <li class="dropdown <?php if ($THIS_PAGE['path'] == "/forum/index.php") echo "active" ?>">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Forums ouverts<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <?php foreach ($FORUMS as $forum) { ?>
                    <li><a href="/forum/?nom=<?php echo $forum['nom'] ?>"><?php echo $forum['titre'] ?></a></li>
                    <?php } ?>
                </ul>
            </li>
            <li <?php if ($THIS_PAGE['path'] == "/projet/index.php") echo "class='active'" ?>><a href="/projet?all">Projets soumis</a></li>
            <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">À propos de nous<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li class="hidden-md hidden-lg <?php if ($THIS_PAGE['path'] == "/nouvelles/index.php") echo "active" ?>"><a href="/nouvelles/">Toutes les nouvelles</a></li>
                    <li class="hidden-md hidden-lg <?php if ($THIS_PAGE['path'] == "/nouvelles/presse.php") echo "active" ?>"><a href="/nouvelles/presse.php">Dans la presse</a></li>
                    <li <?php if ($THIS_PAGE['path'] == "/equipe.php") echo "class='active'" ?>><a href="/equipe.php">L'équipe</a></li>
                    <li <?php if ($THIS_PAGE['path'] == "/manifeste.php") echo "class='active'" ?>><a href="/manifeste.php">Notre manifeste</a></li>
                    <li <?php if ($THIS_PAGE['path'] == "/charte.php") echo "class='active'" ?>><a href="/charte.php">Notre charte</a></li>
                    <li <?php if ($THIS_PAGE['path'] == "/debattre.php") echo "class='active'" ?>><a href="/debattre.php">Comment débattons-nous</a></li>
                    <li <?php if ($THIS_PAGE['path'] == "/rejoindre.php") echo "class='active'" ?>><a href="/rejoindre.php">Rejoignez-nous</a></li>
                    <li <?php if ($THIS_PAGE['path'] == "/contact.php") echo "class='active'" ?>><a href="/contact.php">Contactez-nous</a></li>
                    <li <?php if ($THIS_PAGE['path'] == "/soutiens.php") echo "class='active'" ?>><a href="/soutiens.php">Ils nous soutiennent</a></li>
					<li <?php if ($THIS_PAGE['path'] == "/privacy.php") echo "class='active'" ?>><a href="/privacy.php">Politique de protection des données</a></li>
                </ul>
            </li>
			<li>
				<A href="/projet/soumettre.php" class="btn btn-primary"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Soumettez votre projet</A>
			</li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="connect-nav" <?php if ($THIS_PAGE['path'] == "/connexion/index.php") echo "class='active'" ?>>
                <?php if ($CONNECTED) { ?>
                <a href="/connexion"><span class="glyphicon glyphicon-user" title="Se d&eacute;connecter"></span> <span class="connect-label"><?php echo $_SESSION['mon_profil']['prenom']." ".$_SESSION['mon_profil']['nom'] ?></span></a>
				<?php } else { ?>
                <a href="/connexion"><span class="glyphicon glyphicon-log-in"></span> <span class="connect-label">Connexion</span></a>
                <?php } ?>
            </li>
                <?php if ($CONNECTED and $_SESSION['mon_profil']['role'] == "admin") { ?>
				<li><a href="/_admin" title="Administration"><SPAN class="glyphicon glyphicon-star"></SPAN></a></li>
				<?php } ?>
        </ul>
    </div>
    <!-- /.navbar-collapse -->
</div>
<!-- /.container-fluid -->
</nav>