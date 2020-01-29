<?php
if (!isset($values['date'])) $values['date'] = date("d/m/Y");
if (!isset($values['lien'])) $values['lien'] = "";
if (!isset($values['titre'])) $values['titre'] = "";
if (!isset($values['texte'])) $values['texte'] = "";
if (!isset($values['illustration'])) $values['illustration'] = "";
if (!isset($values['archive'])) $values['archive'] = 0;
?>

<div class="form-group">
	<label for="date">Date</label>
	<input type="text" name="date" class="form-control" required value="<?php echo $values['date'] ?>" placeholder="jj/mm/aaaa">
</div>
<div class="form-group">
	<label for="date">Lien</label>
	<select name="lien" class="form-control">
		<option value="" <?php if ($values['lien'] == "") echo "selected" ?>>Aucun (information g&eacute;n&eacute;rale)</option>
		<optgroup label="Forums">
		<?php foreach($FORUMS as $idProjet=>$forum) { ?>
			<option value="<?php echo $idProjet ?>" <?php if ($values['lien'] == $idProjet) echo "selected" ?>><?php echo $forum['titre'] ?></option>
		<?php } ?>
		</optgroup>
		<optgroup label="Projets">
		<?php foreach($PROJETS as $idProjet=>$projet) { ?>
			<option value="<?php echo $idProjet ?>" <?php if ($values['lien'] == $idProjet) echo "selected" ?>><?php echo $projet['titre'] ?></option>
		<?php } ?>
		</optgroup>
	</select>
</div>
<div class="form-group">
	<label for="titre">Titre</label>
	<input type="text" name="titre" class="form-control" required value="<?php echo $values['titre'] ?>">
</div>
<div class="form-group">
	<label for="texte">Texte</label>
	<textarea name="texte" class="form-control tinymce" rows=10><?php echo $values['texte'] ?></textarea>
</div>
<div class="form-group">
	<label for="illustration">Image d'illustration</label>
	<?php if ($values['illustration'] != "") { ?>
	<IMG src="<?php echo MEDIAS_FOLDER."/".$values['illustration'] ?>" class="img-responsive">
	<?php } ?>
	<INPUT name="illustration" type="file" class="form-control">
</div>
<div class="form-group">
	<label>Archiv&eacute; ?</label><BR>
	<label class="radio-inline">
  		<input type="radio" name="archive" value="0" <?php if ($values['archive'] == "0") { ?>checked<?php } ?>> Non
	</label>
	<label class="radio-inline">
  		<input type="radio" name="archive" value="1" <?php if ($values['archive'] == "1") { ?>checked<?php } ?>> Oui
	</label>
</div>