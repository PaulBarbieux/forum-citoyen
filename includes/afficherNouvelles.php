<?php
/*
	Afficher une nouvelle avec la liste de liens vers les autres.
	La liste des nouvelles dépend le l'ordre SQL ($selectNouvelles) construite dans la page appelante.
*/

$rows = sqlExecute($selectNouvelles);
$nouvelles = array();
$idNouvelles = array(); // Liste des id pour retrouver les illustrations
while ($row = $rows->fetch()) {
	$nouvelles[$row['id']] = $row;
	$idNouvelles[]= $row['id'];
	if ($row['lien'] == "") {
		$nouvelles[$row['id']]['lienType'] = "general";
	} else {
		if (isset($FORUMS[$row['lien']])) {
			$nouvelles[$row['id']]['lienType'] = "forum";
			$nouvelles[$row['id']]['lienTitre'] = $FORUMS[$row['lien']]['titre'];
			$nouvelles[$row['id']]['lienId'] = $FORUMS[$row['lien']]['nom'];
		} else {
			$nouvelles[$row['id']]['lienType'] = "projet";
			$nouvelles[$row['id']]['lienTitre'] = $PROJETS[$row['lien']]['titre'];
			$nouvelles[$row['id']]['lienId'] = $PROJETS[$row['lien']]['id'];
		}
	}
}
$rows->closeCursor();
if (count($nouvelles) > 0) {
	// Chercher les illustrations
	$rows = sqlExecute("SELECT * FROM medias WHERE role='illustration' AND proprietaire IN (".implode(",",$idNouvelles).")");
	while ($row = $rows->fetch()) {
		$nouvelles[$row['proprietaire']]['illustration'] = $row['fichier'];
	}
	if (isset($_GET['idNouvelle'])) {
		// Afficher une nouvelle
		if (isset($nouvelles[$_GET['idNouvelle']])) {
			$cetteNouvelle = $nouvelles[$_GET['idNouvelle']];
		} else {
			// Mauvais id -> prendre la dernière nouvelle
			$cetteNouvelle = reset($nouvelles);
		}
	} else {
		// Afficher la dernière nouvelle par défaut (= première de la liste)
		$cetteNouvelle = reset($nouvelles);
	}
}
if (isset($_GET['tab'])) {
	// Cette page a des tabulations : ajouter le paramètre dans les liens de news
	$lienTab = "&tab=".$_GET['tab'];
} else {
	$lienTab = "";
}
?>
<?php if (count($nouvelles) > 0) { ?>
<DIV class="col-md-7">
	<P><SPAN class="glyphicon glyphicon-bullhorn"></SPAN> <?php echo transformDate($cetteNouvelle['date'],"dd/mm/yyyy") ?>
		<?php if ($cetteNouvelle['lienType'] == "forum") { ?>
		&nbsp;&nbsp;&nbsp;<I class="fa fa-comments" aria-hidden="true"></I> <A href="/forum/?nom=<?php echo $cetteNouvelle['lienId'] ?>" title="Actualité lié à ce forum"><?php echo $cetteNouvelle['lienTitre'] ?></A>
		<?php } elseif ($cetteNouvelle['lienType'] == "projet") { ?>
		&nbsp;&nbsp;&nbsp;<I class="fa fa-pencil-square-o" aria-hidden="true"></I> <A href="/projet/?id=<?php echo $cetteNouvelle['lienId'] ?>" title="Actualité lié à ce projet"><?php echo $cetteNouvelle['lienTitre'] ?></A>
		<?php } ?>
	</P>
	<H2><?php echo $cetteNouvelle['titre'] ?></H2>
	<?php if (isset($cetteNouvelle['illustration'])) { ?>
	<A href="<?php echo MEDIAS_FOLDER.$cetteNouvelle['illustration'] ?>" target="_blank" title="Voir cette image dans sa taille originale">
		<IMG src="<?php echo MEDIAS_FOLDER.$cetteNouvelle['illustration'] ?>" class="img-responsive img-illustration">
	</A>
	<?php } ?>
	<P>&nbsp;</P>
	<P><?php echo $cetteNouvelle['texte'] ?></P>
</DIV>
<DIV class="col-md-5">
	<DIV class="panel">
		<DIV class="panel-body">
			<DIV class="list-group">
			<?php foreach ($nouvelles as $idNouvelle=>$nouvelle) { ?>
					<A href="?idNouvelle=<?php echo $idNouvelle.$lienTab ?>" class="list-group-item <?php echo ($cetteNouvelle['id'] == $idNouvelle ? "active" : "") ?>"><?php echo transformDate($nouvelle['date'],"dd/mm/yyyy") ?> - <?php echo $nouvelle['titre'] ?></A>
			<?php }	?>
			</DIV>
		</DIV>
	</DIV>
</DIV>
<?php } else { ?>
<DIV class="col-sm-12">
	<H2>Il n'y a aucune actualité
	<?php if ($THIS_PAGE['path'] == "/projet/index.php") { ?>
		 pour ce projet
	<?php } elseif ($THIS_PAGE['path'] == "/forum/index.php") { ?>
		pour ce forum
	<?php } ?>
	</H2>
</DIV>
<?php } ?>