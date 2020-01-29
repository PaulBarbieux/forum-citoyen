<?php
if (!isset($values['date'])) $values['date'] = date("d/m/Y");
if (!isset($values['lien'])) $values['lien'] = "";
if (!isset($values['titre'])) $values['titre'] = "";
if (!isset($values['source'])) $values['source'] = "";
?>

<div class="form-group">
	<label for="date">Date</label>
	<input type="date" name="date" class="form-control" required value="<?php echo $values['date'] ?>" placeholder="jj/m/aaaa">
</div>
<div class="form-group">
	<label for="source">Source</label>
	<input type="text" name="source" class="form-control" required value="<?php echo $values['source'] ?>" placeholder="La Meuse, Canal C, lavenir.net, ...">
</div>
<div class="form-group">
	<label for="titre">Titre</label>
	<input type="text" name="titre" class="form-control" required value="<?php echo $values['titre'] ?>" placeholder="Titre de l'article de presse">
</div>
<div class="form-group">
	<label for="lien">Lien</label>
	<input type="text" name="lien" class="form-control" value="<?php echo $values['lien'] ?>" placeholder="Ne rien mettre si vous joignez un document">
	<INPUT name="fichier" type="file" class="form-control">
</div>