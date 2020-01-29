<DIV class="row">
	<DIV class="col-md-12">
		<ul class="nav nav-tabs">
			<li <?php if (!isset($_GET['tab'])) { ?>class="active"<?php } ?>><a href="<?php echo $THIS_PAGE['name'] ?>">Pr&eacute;sentation</a></li>
			<li <?php if (isset($_GET['tab']) and $_GET['tab'] == "idees") { ?>class="active"<?php } ?>><a href="<?php echo $THIS_PAGE['name'] ?>?tab=idees">Vos id&eacute;es</a></li>
			<li <?php if (isset($_GET['tab']) and $_GET['tab'] == "equipe") { ?>class="active"<?php } ?> ><a href="<?php echo $THIS_PAGE['name'] ?>?tab=equipe">Notre &eacute;quipe</a></li>
		</ul>
	</DIV>
</DIV>