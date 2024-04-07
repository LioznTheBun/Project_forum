<?php
	include("parts/header.php");
	include("tools/dtbConnexion.php");
	include("tools/basicFunction.php");
	include("tools/functionPseudo.php");
	include("tools/functionEmail.php");
	include("tools/functionMdp.php");
	?>

 <?php
	//URL : register.php?keyword=...
	$affichagePage = htmlspecialchars($_GET['keyword'], ENT_QUOTES);
	//Récup base donnée
	$dtb = dtbConnexion::getConnexion();
	?>



 <head>
 	<link rel="stylesheet" type="text/css" href="CSS/styleRegist.css" media="all" />
 </head>

 <main id='container'>
 	<header></header>
 	<article>




 		<?php
			// Variables pour l'affichage 'register'
			$register_bool = FALSE;
			if (!empty($_POST['pseudo']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirm'])) {
				$register_bool = TRUE;
				$valid = NULL;
				$i = 0;
				$temps = time();
				$pseudo = htmlspecialchars($_POST["pseudo"], ENT_QUOTES);
				$email = htmlspecialchars($_POST["email"], ENT_QUOTES);
				$pass = htmlspecialchars($_POST["password"], ENT_QUOTES);
				$confirm = htmlspecialchars($_POST["confirm"], ENT_QUOTES);

				//Vérifications
				$verifPseudo = verifPseudo($pseudo, $dtb);
				$verifMdp = verifMdp($pass, $confirm);
				$verifEmail = verifEmail($email, $dtb);
				$i += $verifPseudo['i'] + $verifMdp['i'] + $verifEmail['i'];
				if ($i == 0) {
					$valid = createAccount($pseudo, $pass, $email, $dtb);
				}
			}
			?>

 		<?php if ($affichagePage == 'register') : ?>
 			<Section>
 				<h1>Inscription</h1>
 				<form method="post" action="register.php?keyword=register">
 					<fieldset>
 						<legend>Identifiants</legend>
 						<label for="pseudo">* Pseudo :</label> <input placeholder="Choisissez un pseudo" name="pseudo" type="text" id="pseudo" required /><br />
 						<label for="password">* Mot de Passe :</label><input placeholder="Choisissez un mot de passe" type="password" name="password" id="password" required /><br />
 						<label for="confirm">* Confirmer le mot de passe :</label><input placeholder="Confirmez" type="password" name="confirm" id="confirm" required /><br />
 						<label for="email">* Votre adresse Mail :</label><input placeholder="Entrer votre courriel" type="text" name="email" id="email" required /><br />
 						<p>Les champs précédés d un * sont obligatoires</p>

 						<?php if ($register_bool) : ?>
 							<p><?= $verifPseudo['erreur'] ?></p>
 							<p><?= $verifMdp['erreur'] ?></p>
 							<p><?= $verifEmail['email_erreur1'] ?></p>
 							<p><?= $verifEmail['email_erreur2'] ?></p>
 							<p><?= $valid ?></p>
 						<?php endif; ?>

 					</fieldset>
 					<p><input type="submit" value="S'inscrire" /></p>
 				</form>
 			</Section>
 		<?php endif; ?>




 		<?php
			// Variables pour l'affichage 'connexion'
			$print_result = 'Votre login ou mot de passe est incorrecte';
			if (!empty($_POST['pseudo']) && !empty($_POST['password'])) {
				//On récupère les variables
				$i = 0;
				$temps = time();
				$pseudo = htmlspecialchars($_POST["pseudo"], ENT_QUOTES);
				$pass = htmlspecialchars($_POST["password"], ENT_QUOTES);

				//Recherche de l'utilisateur
				$state = $dtb->prepare('SELECT * FROM users WHERE username = ? AND password = SHA2(?, 256)');
				$state->execute(array($pseudo, $pass));
				$data = $state->fetch();
				$state->CloseCursor();
				if ($state->rowCount() > 0) {
					if ($data["id_Rank"] != 3 && $data["id_Rank"] != 4) {
						$_SESSION["rank"] = $data["id_Rank"];
						$_SESSION["user_id"] = $data["idUser"];
						$_SESSION["avatar"] = './img/avatars/' . $data["avatar"];
						header('Location: index.php');
						exit;
					}


					//On récupère les infos du ban si utilisateur banni définitivement
					if ($data["id_Rank"] == 3) {
						$user = $data["idUser"];
						$stmt = $dtb->prepare("SELECT * FROM Ban WHERE user_id = ?");
						$stmt->execute(array($user));
						['date_debut' => $debut_ban, 'raison' => $raison_ban] = $stmt->fetch();
						$stmt->CloseCursor();
						$print_result = <<<PRINT_RES
						<p>Vous avez étés banni définitivement le $debut_ban pour la raison suivante:</p>
						$raison_ban
						PRINT_RES;
					}


					//On récupère les infos du ban si utilisateur banni temporairement
					if ($data["id_Rank"] == 4) {
						$user = $data["idUser"];
						$stmt = $dtb->prepare("SELECT * FROM BanTemporaire WHERE user_id = ?");
						$stmt->execute(array($user));
						['raison' => $raison_ban, 'date_fin' => $fin_ban, 'id' => $idTemp] = $stmt->fetch();
						$stmt->CloseCursor();
						$print_result = <<<PRINT_RES
						<p>Vous êtes bannis jusqu'au $fin_ban pour la raison suivante:</p>
						$raison_ban
						PRINT_RES;


						//Si la date de fin du ban a été dépassée
						$dt = new \DateTime();
						$dategood = $dt->format('Y-m-d');
						if ($dategood >= $fin_ban) {
							$rank = 2;

							$stmt = $dtb->prepare("UPDATE Users SET id_Rank = :idRank
                    		WHERE idUser = :userId;");
							$stmt->bindParam(':idRank', $rank);
							$stmt->bindParam(':userId', $data['idUser']);
							$stmt->execute();

							$fetchsup = $dtb->prepare("DELETE FROM BanTemporaire WHERE id = ?");
							$fetchsup->execute(array($idTemp));
						}
					}
				}
			}
			?>

 		<?php if ($affichagePage == 'connexion') : ?>
 			<h1>Connexion</h1>
 			<form method="post" action="register.php?keyword=connexion">
 				<fieldset>
 					<legend>Identifiants</legend>
 					<label for="pseudo">* Pseudo :</label> <input placeholder="Entrer votre pseudo" name="pseudo" type="text" id="pseudo" required /><br />
 					<label for="password">* Mot de Passe :</label><input placeholder="Entrer votre mot de passe" type="password" name="password" id="password" required /><br />

 					<?= $print_result ?>

 				</fieldset>
 				<p><input type="submit" value="Connexion" /></p>
 			</form>
 			</div>
 		<?php endif; ?>



 	</article>
 </main>
 </body>

 </html>