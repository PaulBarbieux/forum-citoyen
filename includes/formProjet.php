<?php
if (!isset($values['titre'])) $values['titre'] = "";
if (!isset($values['description'])) $values['description'] = "";
if (!isset($values['attente'])) $values['attente'] = "";
if (!isset($values['ressources'])) $values['ressources'] = "";
if (!isset($values['initiative'])) $values['initiative'] = "";
if (!isset($values['demarches'])) $values['demarches'] = "";
?>
<div class="form-group">
	<label for="titre">Titre</label>
	<input type="text" name="titre" class="form-control" placeholder="Titre pour identifier le projet" required value="<?php echo $values['titre'] ?>">
</div>
<div class="form-group">
	<label for="titre">Description</label>
	<textarea name="description" class="form-control" placeholder="Description ou explication du projet" rows="5" required><?php echo $values['description'] ?></textarea>
</div>
<div class="form-group">
	<label for="titre">Attente</label>
	<textarea name="attente" class="form-control" placeholder="D&eacute;crivez votre attente par rapport &agrave; ce projet" rows="5" required><?php echo $values['attente'] ?></textarea>
</div>
<div class="form-group">
	<label for="ressources">Ressources</label>
	<DIV class="form-info">
		<P>Avez-vous des personnes-ressources en mesure d'apporter un &eacute;clairage technique, urbanstique, l&eacute;gal, sanitaire, philosophique, &eacute;thique, ... sur le sujet propos&eacute;&nbsp;? Si oui, &eacute;num&eacute;rez les comp&eacute;tences disponibles.</P>
	</DIV>
	<textarea name="ressources" class="form-control" rows="5" required><?php echo $values['ressources'] ?></textarea>
</div>
<div class="form-group">
	<label for="initiative">Initiative</label>
	<DIV class="form-info">
		<P>Expliquez les resources existantes autour de ce projet :</P>
		<UL><LI>Initiative individuelle:
				<UL>
					<LI>Pouvez-vous r&eacute;unir autour de vous des personnes pr&ecirc;tes &agrave; soutenir le projets ? Combien de personnes ?</LI>
					<LI>Quel seraient leurs r&ocirc;les ?</LI>
				</UL>
			</LI>
			<LI>Initiative de plusieurs personnes ou d'une association :
				<UL>
					<LI>D&eacute;crivez le groupe ou l'association.</LI>
					<LI>Combien de personnes sont impliqu&eacute;es ?</LI>
					<LI>Quels sont les r&ocirc;les ? Riverains, voisins, interpel&eacute;s par le sujet ?</LI>
				</UL>
			</LI>
		</UL>
	</DIV>
	<textarea name="initiative" class="form-control" rows="5" required><?php echo $values['initiative'] ?></textarea>
</div>
<div class="form-group">
	<label for="titre">D&eacute;marches</label>
	<DIV class="form-info">
		<P>Avez-vous d&eacute;j&agrave; effectu&eacute; des d&eacute;marches officielles (r&eacute;ponses &agrave; enqu&ecirc;te publique, interpellation communale...) pour d&eacute;battre de la question ? Si oui, d&eacute;crivez-les (description, dates, documents en attestant...) ?</P>
	</DIV>
	<textarea name="demarches" class="form-control" rows="5"><?php echo $values['demarches'] ?></textarea>
</div>