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
      <a class="navbar-brand" href="/">ADMINISTRATION</a>
    </div>

    <!-- Collect the nav links, forms, and other content for togglineg -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li <?php if ($THIS_PAGE['name'] == "index.php") { ?>class="active"<?php } ?>><a href="index.php">Nouvelles</a></li>
        <li <?php if ($THIS_PAGE['name'] == "presse.php") { ?>class="active"<?php } ?>><a href="presse.php">Presse</a></li>
        <li <?php if ($THIS_PAGE['name'] == "forums.php") { ?>class="active"<?php } ?>><a href="forums.php">Forums</a></li>
        <li <?php if ($THIS_PAGE['name'] == "idees.php") { ?>class="active"<?php } ?>><a href="idees.php">Idées</a></li>
        <li <?php if ($THIS_PAGE['name'] == "commentaires.php") { ?>class="active"<?php } ?>><a href="commentaires.php">Commentaires</a></li>
        <li <?php if ($THIS_PAGE['name'] == "projets.php") { ?>class="active"<?php } ?>><a href="projets.php">Projets</a></li>
        <li <?php if ($THIS_PAGE['name'] == "personnes.php") { ?>class="active"<?php } ?>><a href="personnes.php">Personnes</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>