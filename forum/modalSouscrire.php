<SCRIPT type="text/javascript">
jQuery(document).ready(function(){
	$('#ModalSouscrire').on('show.bs.modal',function(event){
		var trigger = $(event.relatedTarget);
		// Récupérer les valeurs du lien et les placer dans ce popup
		$("#forumTitre").html(trigger.data('forum'));
		return true;
	});
});
</SCRIPT>
<?php
if  (!isset($values['profil'])) {
	$values['profil'] = "";
}
?>
<DIV class="modal fade" id="ModalSouscrire" tabindex="-1" role="dialog" aria-labelledby="ModalSouscrireLabel" aria-hidden="true">
	<DIV class="modal-dialog">
		<DIV class="modal-content">
			<DIV class="modal-header">
				<H4 class="modal-title" id="ModalSouscrireLalel">Souscrire au forum : <SPAN id="forumTitre"></SPAN></H4>
			</DIV>
			<FORM method="post">
				<INPUT type="hidden" name="start" value="<?php echo time() ?>">
				<INPUT type="hidden" name="last_name">
				<DIV class="modal-body">
					<?php if ($error) { ?>
					<P class="alert alert-danger"><?php echo $message ?></P>
					<?php } ?>
					<P>En rejoignant ce forum, vous pourrez exposer vos id&eacute;es.</P>
					<?php if (!$CONNECTED) { ?>
					<P class="alert alert-warning">Si vous &ecirc;tes d&eacute;j&agrave; enregistr&eacute; sur notre site, <A href='/connexion/?back=/forum/index.php?tab=equipe'>veuillez vous connecter</A> avant d'adh&eacute;rer au forum.</P>
					<P>Les informations que vous encodez ici resteront confidentiels mais seront en partie partag&eacute;es avec les autres adh&eacute;rents &agrave; ce forum.</P>
					<?php include $_SERVER['DOCUMENT_ROOT']."/includes/formPersonne.php" ?>
					<?php } ?>
					<div class="form-group">
						<label for="profil">Votre motivation par rapport à ce forum</label>
						<textarea name="profil" class="form-control" required placeholder="Comment vous profilez-vous par rapport &agrave; ce forum, quelles sont les comp&eacute;tences que vous pourriez y apporter, en quoi ce forum vous int&eacute;resse, quel est votre motivation ?" rows="3"><?php echo $values['profil'] ?></textarea>
					</div>
				</DIV>
				<DIV class="modal-footer">
					<DIV class="btn-group">
						<BUTTON type="submit" class="btn btn-success" name="souscrire" value="souscrire"><span class="glyphicon glyphicon-send"></span> Envoyer</BUTTON>
						<BUTTON type="button" class="btn btn-default" data-dismiss="modal">Annuler</BUTTON>
					</DIV>
				</DIV>
			</FORM>
		</DIV>
	</DIV>
</DIV>