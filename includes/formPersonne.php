<?php
if (!isset($values['email'])) $values['email'] = "";
if (!isset($values['nom'])) $values['nom'] = "";
if (!isset($values['prenom'])) $values['prenom'] = "";
if (!isset($values['presentation'])) $values['presentation'] = "";
if (!isset($values['telephone'])) $values['telephone'] = "";
if (!isset($values['rue'])) $values['rue'] = "";
if (!isset($values['code_postal'])) $values['code_postal'] = "";
if (!isset($values['commune'])) $values['commune'] = "";
$values['charteBool'] = (isset($values['charte']) and $values['charte'] == 1 ? 1 : 0);
$values['manifesteBool'] = (isset($values['manifeste']) ? $values['manifeste'] : 1);

if (ADMIN) {
	// Ce formulaire peut être utilisé par l'administrateur pour modifier les données personnelles.
	// Il peut alors laisser vide le mot de passe, pour ne pas le changer.
	$passeRequired = "";
} else {
	$passeRequired = "required";
}
?>
<?php if (!ADMIN) { ?>
<div class="form-group">
	<label>
	  <input type="checkbox" id="charte" name="charte" value="1" required <?php if ($values['charteBool']) echo "checked" ?>> J'ai lu <a href="/charte.php" target="_blank">la charte</a> et je m'engage à la respecter
	</label>
</div>
<?php } ?>
<div class="form-group">
	<label>
	  <input type="checkbox" name="manifeste" value="1" <?php if ($values['manifesteBool']) echo "checked" ?>> J'adhère <a href="/manifeste.php" target="_blank">au manifeste</a> (vous apparaîtrez dans la page <A href="/soutiens.php" target="_blank">Ils nous soutiennent</A>)</strong>
	</label>
</div>
<div class="form-group">
	<label for="nom">Nom de famille</label>
	<input type="text" name="nom" class="form-control" required value="<?php echo $values['nom'] ?>">
</div>
<div class="form-group">
	<label for="prenom">Pr&eacute;nom</label>
	<input type="text" name="prenom" class="form-control" required value="<?php echo $values['prenom'] ?>">
</div>
<div class="form-group">
	<label for="presentation">À propos de moi</label>
	<textarea name="presentation" class="form-control" required placeholder="Décrivez-vous en quelques mots"><?php echo $values['presentation'] ?></textarea>
</div>
<div class="row">
	<div class="form-group col-sm-6">
		<label for="email">Adresse email</label>
		<input type="email" name="email" class="form-control" required value="<?php echo $values['email'] ?>">
	</div>
	<div class="form-group col-sm-6">
		<label for="telephone">T&eacute;l&eacute;phone</label>
		<input type="tel" name="telephone" class="form-control" required value="<?php echo $values['telephone'] ?>">
	</div>
</div>
<div class="form-group">
	<label for="rue">Rue</label>
	<input type="text" name="rue" class="form-control" placeholder="Rue/avenue/... et numéro" required value="<?php echo $values['rue'] ?>">
</div>
<div class="row">
	<div class="form-group col-sm-3">
		<label for="rue">Code postal</label>
		<input type="text" name="code_postal" class="form-control" required value="<?php echo $values['code_postal'] ?>">
	</div>
	<div class="form-group col-sm-9">
		<label for="rue">Commune</label>
		<input type="text" name="commune" class="form-control" required value="<?php echo $values['commune'] ?>">
	</div>
</div>
<?php if (ADMIN) { ?>
<P><BR>Laissez vide le mot de passe pour ne pas le changer.</P>
<?php } ?>
<div class="row">
	<div class="form-group col-sm-6">
		<label for="passe">Mot de passe (minimum 8 caract&egrave;res)</label>
		<input type="password" name="passe" class="form-control" <?php echo $passeRequired ?>>
	</div>
	<div class="form-group col-sm-6">
		<label for="passe2">Confirmez le mot de passe</label>
		<input type="password" name="passe2" class="form-control" <?php echo $passeRequired ?>>
	</div>
</div>
<div class="form-group">
	<label>Photo de profil</label>
	<p>Notre site utilise Gravatar pour avoir votre photo de profil. 
		Vous ne connaissez pas ? Gravatar permet de lier une photo à votre adresse email : simple, efficace et utile sur beaucoup de sites.
		<A href="https://fr.gravatar.com/" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-share" aria-hidden="true"></i>
 Créez votre Gravatar</A>
	</p>
</div>
