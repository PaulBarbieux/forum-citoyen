<?php
/*
	L'appel de ce code doit être fait dans un <DIV class="panel"></DIV> (le design du panneau étant à charge de l'appelant)
*/

if (!isset($values['titre'])) $values['titre'] = "";
if (!isset($values['texte'])) $values['texte'] = "";
if  (!isset($values['statut']))	$values['statut'] = "nouveau";
if  (!isset($values['envoi']))	$values['envoi'] = "0";

if (isset($_POST['proposerIdee'])) {
	// Envoi d'une idée
	$action = "proposerIdee";
	$fichiersCharges = array();
	foreach ($_POST as $input=>$value) {
		switch ($input) {
			case "media" :
				// value est un array de 3 fichiers
				break;
			default :
				if ($input == "texte") {
					$value = trim($value);
				} else {
					$value = trim(strip_tags($value));
				}
				$values[$input] = $value;
				if ($value == "") {
					$error = true;
					$message = "Veuillez remplir le titre et le texte s.v.p. <!-- ".$input." -->";
				}
		}
	}
	if (!$error) {
		// Id de l'idée qui sert de suffixe aux fichiers.
		// Si nous sommes en administration, cet id existe déjà.
		if (!ADMIN) {
			$idIdee = time();
		}
		// Vérifier les fichiers
		$idProprietaire = $idIdee;
		for ($iFile=0; $iFile<=2; $iFile++) {
			// Autres médias
			if ($_FILES['media']['name'][$iFile] != "") {
				$fichierEnvoye = array(
					'name' => $_FILES['media']['name'][$iFile],
					'error' => $_FILES['media']['error'][$iFile],
					'tmp_name' => $_FILES['media']['tmp_name'][$iFile]
				);
				require $_SERVER['DOCUMENT_ROOT']."/includes/chargerFichier.php";
			}
		}
	}
	if ($error) {
		// Supprimer les fichiers chargés
		foreach ($fichiersCharges as $nomFichier=>$typeFichier) {
			unlink($_SERVER['DOCUMENT_ROOT'].MEDIAS_FOLDER.$nomFichier);
		}
	} else {
		if (ADMIN) {
			// Modifier l'idée
			sqlExecute("UPDATE idees SET statut='".$values['statut']."', titre=".
				$db->quote($values['titre']).", texte=".$db->quote($values['texte'])." WHERE id='".$idIdee."'");
			$_SESSION['message'] = "Idée <STRONG>".$values['titre']."</STRONG> modifiée.";
			if ($values['statut'] == "accepte" and $values['envoi'] == "1") {
				// Avertir les adhérents
				$emailAdherents = array();
				$cetteIdee = $forums[$ideesIndex[$idIdee]]['idees'][$idIdee];
				require $_SERVER['DOCUMENT_ROOT']."/includes/prepareMail.php";
				$result = sqlExecute("
					SELECT A.email, R.prenom 
					FROM adhesions A, personnes R
					WHERE A.projet='".$cetteIdee['projet']."' AND A.email=R.email AND (R.code_confirmation='' OR R.code_confirmation is NULL)");
				$prenomAuteur = "inconnu";
				while ($rowAdh = $result->fetch()) {
					$mail->AddBCC($rowAdh['email']);
					if ($rowAdh['email'] == $cetteIdee['email']) {
						$prenomAuteur = $rowAdh['prenom'];
					}
					$emailAdherent[] = $rowAdh['email'];
				}
				$mail->AddAddress(ADMIN_EMAIL,SITE_TITLE);
				$mail->Subject = utf8_decode(SITE_TITLE." : nouvelle idée ".$values['titre']);
				$body = "<P>Une nouvelle idée a été postée dans le forum ".$cetteIdee['titreForum'].", par ".$prenomAuteur." : <strong>".$values['titre']."</strong>.</P>
						<P>Vous recevez cet email parce que vous faites partie du forum <A href='http://".$_SERVER['SERVER_NAME']."/forum/?nom=".$cetteIdee['forum']."'>".$cetteIdee['titreForum']."</A>.</P>";
				$mail->Body = utf8_decode($body);
				if (TESTING) {
					print "<DIV class='mail-body-test'>".$body."</DIV>";
				} elseif (!$mail->send()) {
					print "Oups ! Erreur technique lors de l'envoi !<br>".$mail->ErrorInfo;
					exit;
				}
				$_SESSION['message'] .= "<br>Email envoyé aux adhérents : ".implode(", ",$emailAdherent).".";
			}
			// Retour à la page : mécanisme le plus simple pour rafraîchir la liste des idées
?>
<SCRIPT type="text/javascript">
setTimeout(function(){
	window.location.assign("?action=updateCompleted");
},500);
</SCRIPT>
<?php
		} else {
			// Créer l'idée
			sqlExecute("INSERT INTO idees VALUES ('".$idIdee."','".$values['statut']."','".$_SESSION['ce_forum']['nom']."','".$_SESSION['mon_profil']['email']."',".
				$db->quote($values['titre']).",".$db->quote($values['texte']).")");
			$message = "Votre id&eacute;e a &eacute;t&eacute; envoy&eacute;e. Elle sera publi&eacute;e apr&egraves approbation par un mod&eacute;rateur. Merci.";
			// Message aux administrateurs
			require ROOT.'/includes/phpmailer/PHPMailerAutoload.php';
			$mail = new PHPMailer;
			$mail->From = SEND_EMAIL;
			$mail->FromName = SITE_TITLE;
			$body = utf8_decode("<P>Une nouvelle id&eacute;e a &eacute;t&eacute; soumise sur le site. <A href='http://".$_SERVER['SERVER_NAME']."/_admin/idees.php?action=edit&id=".$idIdee."'>Veuillez la traiter</A>.</P>");
			$mail->isHTML(true);
			$mail->Body = $body;
			$mail->addAddress(ADMIN_EMAIL,SITE_TITLE);
			$mail->Subject = SITE_TITLE." : nouvelle idée ".$values['titre'];
			if (TESTING) {
				print "<DIV class='mail-body-test'>".$body."</DIV>";
			} else {
				if (!$mail->send()) {
					print "Oups ! Erreur technique lors de l'envoi !<br>".$mail->ErrorInfo;
					exit;
				}
			}
		}
		// Créer les médias
		foreach ($fichiersCharges as $nomFichier=>$typeFichier) {
			sqlExecute("INSERT INTO medias (fichier,proprietaire,role) VALUES (".$db->quote($nomFichier).",'".$idIdee."','".$typeFichier."')");
		}
		$action = "completed";
	}
}
?>
<SCRIPT type="text/javascript">
jQuery(document).ready(function(){
	// Faire apparaître ou non l'option d'envoi suivant le statut
	$("[name='statut']").change(function(){
		if ($(this).val() == "accepte") {
			$("#inputAccepte").show();
		} else {
			$("#inputAccepte").hide();
			$("[name=envoi][value=0]").click();
		}
	});
	// Initialiser
	if ($("[name='statut']:checked").val() != "accepte") {
		$("#inputAccepte").hide();
	}
});
</SCRIPT>

<DIV class="panel-heading">
	<H4><I class="fa fa-lightbulb-o" aria-hidden="true"></I> <?php if (ADMIN) { ?>Modifier une id&eacute;e<?php } else { ?>Proposer une id&eacute;e<?php } ?></H4>
</DIV>
<DIV class="panel-body">
	<?php require $_SERVER['DOCUMENT_ROOT']."/includes/alerts.php" ?> 
	<?php if ($action != "completed") { ?>
	<FORM method="post" enctype="multipart/form-data">
		<?php if (ADMIN) { ?>
		<DIV class="row">
			<DIV class="col-lg-6">
		<?php } ?>
				<?php if (ADMIN) { ?>
				<div class="form-group">
					<label>Statut</label>
					<P>Toutes les modifications faites dans les zones ouvertes seront apport&eacute;es en m&ecirc;me temps que le statut.</P>
					<label class="radio-inline">
						<input type="radio" name="statut" value="nouveau" <?php if ($values['statut'] == "nouveau") echo "checked" ?>>
						Nouveau
					</label>
					<label class="radio-inline">
						<input type="radio" name="statut" value="accepte" <?php if ($values['statut'] == "accepte") echo "checked" ?>>
						Accept&eacute;
					</label>
					<label class="radio-inline">
						<input type="radio" name="statut" value="rejete" <?php if ($values['statut'] == "rejete") echo "checked" ?>>
						Rejet&eacute;
					</label>
				</div>
				<div id="inputAccepte" class="form-group">
					<label>Idée acceptée : avertir les adhérents de l'arrivée de cette idée ?</label><BR>
					<label class="radio-inline">
						<input type="radio" name="envoi" value="0" <?php if ($values['envoi'] == "0") { ?>checked<?php } ?>> Non
					</label>
					<label class="radio-inline">
						<input type="radio" name="envoi" value="1" <?php if ($values['envoi'] == "1") { ?>checked<?php } ?>> Oui
					</label>
				</div>
				<?php } ?>
				<div class="form-group">
					<label for="titre">Titre</label>
					<input name="titre" type="text" class="form-control" required placeholder="Un titre pour votre id&eacute;e" value="<?php echo $values['titre'] ?>">
				</div>
				<div class="form-group">
					<label for="texte">Votre id&eacute;e</label>
					<textarea name="texte" class="form-control tinymce" placeholder="Exposez votre id&eacute;e" rows="10"><?php echo $values['texte'] ?></textarea>
				</div>
		<?php if (ADMIN) { ?>
			</DIV>
			<DIV class="col-lg-6">
		<?php } ?>
				<DIV class="form-group">
					<label>M&eacute;dias</label>
					<?php if (ADMIN) { ?>
					<div class="form-group">
						<TABLE>
						<?php foreach ($values['medias'] as $fichier=>$media) { ?>
							<TR><TD>
								<A href="<?php echo MEDIAS_FOLDER.$fichier ?>" target="_blank"><?php echo $fichier ?></A>
								<A href="?action=deleteMedia&fichier=<?php echo $fichier ?>" class="btn btn-default btn-sm _delete" label-confirm="<?php echo $fichier ?>"><span class="glyphicon glyphicon-trash"></span></A>
							</TD></TR>
						<?php } ?>
						</TABLE>
					</div>
					<?php } ?>
					<P>Vous pouvez joindre des images (jpeg, png, gif) et des documents en format Acrobat (PDF).</P>
					<INPUT name="media[]" type="file" class="form-control">
					<INPUT name="media[]" type="file" class="form-control">
					<INPUT name="media[]" type="file" class="form-control">
				</DIV>
				<BUTTON type="submit" class="btn btn-success btn-block" name="proposerIdee" value="proposerIdee"><span class="glyphicon glyphicon-send"></span> Envoyer</BUTTON>
		<?php if (ADMIN) { ?>
			</DIV>
		</DIV>
		<?php } ?>
	</FORM>
	<?php } ?>
</DIV>