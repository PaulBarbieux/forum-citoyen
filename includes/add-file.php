<?php
session_start();
if ($_SESSION['valid']=="1" AND $_SESSION['tos']=="1") {
	
$page="library";

include('../includes/header.php');

if (isset ($_POST['add'])) {
	// Traitement du formulaire
	$name = basename($_FILES['file']['name']);
	$directory_id = $_POST['directory_id'];
	$private = $_POST['private'];
	
	$request = $db->prepare("SELECT id FROM cloud_files WHERE name = :name");
	$request->execute(array(
		'name' => $name));
	
	$result = $request->rowCount();
	
	if ($result)
	{
		$status = 'Ce fichier est déjà enregistré dans la bibliothèque.';
	}
	
	else {	
		$directory = './files/';
		$size_max = 5000000;
		$size = filesize($_FILES['file']['tmp_name']);
		
		$extensions = array(
			//Document
			'.doc',	
			'.docx',
			'.dot',
			'.odt',
			'.pdf',
			'.txt',
			//Présentation
			'.odp',
			'.pps',
			'.ppt',
			'.pptx',
			//Tableau
			'.csv',
			'.ods',
			'.xls',
			'.xlsx',
			//Image
			'.bmp',
			'.jpg',
			'.jpeg',
			'.gif',
			'.ico',
			'.png',
			//Musique
			'.m3u',
			'.mp3',
			'.wav',
			//Vidéo
			'.avi',
			'.mp4',
			'.wma',
			//Dossier
			'.zip');
			
		$extension = strrchr($_FILES['file']['name'], '.'); 
		//Début des vérifications de sécurité...
		if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
		{
			$status = 'L\'extension n\'est pas reconnue par le système.';
		}
		if (!file_exists($_FILES['file']['tmp_name']))
		{
			$status = 'test';
		}
		if($size>$size_max)
		{
			$status = 'Le fichier est trop volumineux.';
		}
		if(!isset($error)) //S'il n'y a pas d'erreur, on upload
		{
			//On formate le nom du fichier ici...
			$name = strtr($name, 
				'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ@', 
				'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy-');
			$name = preg_replace('/([^.a-z0-9]+)/i', '-', $name);
			if(move_uploaded_file($_FILES['file']['tmp_name'], $directory . $name)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
			{
				// Enregistrement du fichier
				$request = $db->prepare("INSERT INTO cloud_files (name, directory_id, private) VALUES(:name, :directory_id, :private)");
				$request->execute(array(
					'name' => $name,
					'directory_id' => $directory_id,
					'private' => $private));
			
				$status = 'Le fichier a été envoyé avec succès.';
			}
			else //Sinon (la fonction renvoie FALSE).
			{
				$status = 'Le système a rencontré une erreur inconnue.';
			}
		}
		else
		{
			$status = 'Le système a rencontré une erreur inconnue.';
		}
	}
}
?>

				<div id="main-wrapper">
					<div id="main" class="container">
						<div class="row">
							<div class="3u 12u(mobile)">
								<div class="sidebar">

											<?php include('includes/navigation.php'); ?>

								</div>
							</div>
							<div class="9u 12u(mobile) important(mobile)">
								<div class="content content-right">

										<article class="box page-content">

											<header>
												<h2>Bibliothèque</h2>
											</header>

											<section>
												<?php if(isset($status)) echo '<blockquote><strong>' . $status . '</strong></blockquote>'; ?>
												<blockquote>Taille maximale pour l'envoi d'un fichier: 5Mo</blockquote>
												<form method="post" action="add-file.php" enctype="multipart/form-data">
													<div class="row uniform 40%">
														<div class="4u 12u(mobilep)">
															<p>Fichier à envoyer</p>
														</div>
														<div class="8u 12u(mobilep)">
															<input type="file" id="file" name="file" required />
														</div>
													</div>
													<div class="row uniform 40%">
														<div class="4u 12u(mobilep)">
															<p>Répertoire de destination</p>
														</div>
														<div class="8u 12u(mobilep)">
															<select id="directory_id" name="directory_id">
															<?php
															$request = $db->query('SELECT * FROM cloud_directory ORDER BY name');

															while ($result = $request->fetch()) {
															?>	
																<option value="<?php echo $result['id']; ?>">Groupe <?php echo $result['name']; ?></option>
															<?php
															}
															?>
															</select>
														</div>
													</div>
													<div class="row uniform 40%">
														<div class="4u 12u(mobilep)">
															<p>Confidentialité</p>
														</div>
														<div class="8u 12u(mobilep)">
															<select id="private" name="private">
																<option value="0">Accessible aux utilisateurs enregistrés</option>
																<option value="1">Accessible aux membres des groupes de travail</option>
															</select>
														</div>
													</div>
													<div class="row uniform">
														<div class="12u">
															<ul class="actions align-center">
																<li><input type="submit" name="add" value="Enregistrer le fichier" /></li>
															</ul>
														</div>
													</div>
												</form>
											</section>

										</article>

								</div>
							</div>
						</div>
						<?php include('../includes/join-us.php'); ?>
					</div>
				</div>

				<?php include('../includes/footer.php'); ?>

		</div>

			<?php include('../includes/scripts.php'); ?>

	</body>
</html>
<?php
}

else if ($_SESSION['tos']=="0") {
	header('Location: tos');
}

else {
	header('Location: login');
}
?>