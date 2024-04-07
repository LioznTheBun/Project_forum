<?php
include("parts/header.php");
include('tools/dtbConnexion.php');
include('tools/functionAvatar.php');
include('tools/functionEmail.php');
include('tools/functionMdp.php');
include('tools/basicFunction.php');
include('tools/functionPseudo.php')
?>

<head>
	<link rel="stylesheet" type="text/css" href="CSS/styleProfil.css" media="all" />
</head>
<main>


<?php
$dtb = dtbConnexion::getConnexion();
$action = isset($_GET['action']) ? htmlspecialchars($_GET['action']) : 'consulter';
$membre = isset($_GET['m'])           ? (int) $_GET['m'] :(
	isset($_SESSION['user_id']) ? $_SESSION['user_id'] :
	'');

if ($action == 'modifier' && !isset($_SESSION['user_id']))
	header('Location: register.php?keyword=connexion');

isUserInDTB($membre, $dtb);
$data = getUserById($membre, $dtb);
$role = getRank($data['id_Rank'], $dtb);


//On regarde la valeur de la variable $action

switch ($action) {


	//Si c'est "consulter"
case "consulter":
//On récupère les infos du membre

$mod_profil_link = !empty($_SESSION['user_id']) && $membre==$_SESSION['user_id'] ? 'profil.php?action=modifier' : 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';
//On affiche les infos sur le membre
?>
<article>
	<header>
		<h1>Profil de <?=stripslashes(htmlspecialchars($data['username']))?></h1>
	</header>
	<a id="buttonmodif" type=button href="<?=$mod_profil_link?>">Modifier profil</a><br>
	<p><strong>Rôle : </strong>
		<?=$role['libelle'] ?><br />
	</p>
	<img src="./img/avatars/<?=$data['avatar']?>" alt="Ce membre n'a pas d'avatar" />
	<img id="demonstration" src="./img/avatars/<?=$data['avatar']?>" alt="Ce membre n'a pas d'avatar" />
	<p><strong>Adresse E-Mail : </strong>
		<a id="mail" href="mailto: <?=stripslashes($data['email']) ?> ">
			<?=stripslashes(htmlspecialchars($data['email'])) ?> </a><br />
	</p>

	<?php include('mestopics.php'); ?> <!--Cause l'accès au profil impossible si non connecté, même pour consulter-->
</article>
<?php

break;




//Si on choisit de modifier son profil
case "modifier":
if (empty($_POST['sent'])) // Si on la variable est vide, on peut considérer qu'on est sur la page de formulaire
{

	//On prend les infos du membre
	
	?>
	<article>
		<p><i>Vous êtes ici</i> : <a href="./index.php">Index du forum</a> --> Modification du profil<br>
			<!--Bouton pour consulter profil: -->
			<a type=button href="profil.php?action=consulter">Consulter profil</a><br>
		<h1>Modifier son profil</h1>

		<form id="modif" method="post" action="profil.php?action=modifier" enctype="multipart/form-data">
		<fieldset>
			<legend>Identifiants</legend>
			Pseudo : <strong><?=stripslashes(htmlspecialchars($data['username'])) ?></strong><br />
			<label for="pseudo">Nouveau Pseudo :</label>
			<input type="text" name="pseudo" id="pseudo" /><br />
			<label for="password">Nouveau mot de Passe :</label>
			<input type="password" name="password" id="password" /><br />
			<label for="confirm">Confirmer le mot de passe :</label>
			<input type="password" name="confirm" id="confirm" />
		</fieldset>
		<fieldset>
			<legend>Contacts</legend>
			<label for="email">Votre adresse E_Mail :</label>
			<input type="text" name="email" id="email" placeholder="<?=stripslashes($data['email']) ?>" /><br />
		</fieldset>
		<fieldset>
			<legend>Profil sur le forum</legend>
			<label for="avatar">Changer votre avatar :</label>
			<input type="file" name="avatar" id="avatar" />
			(Taille max : A voir)<br /><br />
			<label><input type="checkbox" name="delete" value="Delete" />
				Remettre l avatar basique</label><br>
			Avatar actuel :
			<img src="./img/avatars/<?=$data['avatar'] ?>" alt="pas d avatar" />
		</fieldset>
		<p>
			<input type="submit" value="Modifier son profil" />
			<input type="hidden" id="sent" name="sent" value="1" />
		</p>
		</form>
	</article>

	<?php

} else //Cas du traitement
{
	//On déclare les variables

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
		$verifAvatar = verifAvatar();
		$i += $verifPseudo['i'] + $verifMdp['i'] + $verifEmail['i'] + $verifAvatar['i'];

	?>
	<p><i>Vous êtes ici</i> : <a href="./index.php">Index du forum</a> --> Modification du profil
	<h1>Modification du profil</h1>
	<?php

	if ($i == 0) // Si $i est vide, il n'y a pas d'erreur
	{
		changePseudo($pseudo, $membre, $dtb);
		changeAvatar($membre, $data['avatar'], $dtb);

		if (isset($_POST['delete']))
			removeAvatar($membre ,$data['avatar'] , $dtb);
	?>
		<section>
			<h1>Modification terminée</h1>
			<p>Votre profil a été modifié avec succès !</p>
			<p>Cliquez <a href="./profil.php?action=consulter">ici</a> pour revenir à la page d accueil</p>
		</section>
		<?php
		//On modifie la table
		changePseudo($pseudo ,$membre ,$dtb);
		changeMdp($membre, $pass, $confirm, $dtb);
		changeEmail($email, $membre, $dtb);
	} else {
		?>
		<section>
			<h1>Modification interrompue</h1>
			<p>Une ou plusieurs erreurs se sont produites pendant la modification du profil</p>
			<p><?=$i?> erreur(s)</p>
			<p><?=$verifMdp['erreur']?></p>
			<p><?=$verifEmail['email_erreur1']?></p>
			<p><?=$verifEmail['email_erreur2']?></p>
			<p><?=$verifAvatar['avatar_erreur']?></p>
			<p><?=$verifAvatar['avatar_erreur1']?></p>
			<p><?=$verifAvatar['avatar_erreur2']?></p>
			<p><?=$verifAvatar['avatar_erreur3']?></p>
			<p> Cliquez <a href="./profil.php?action=modifier">ici</a> pour recommencer</p>
		</section>
	<?php
	}
} //Fin du else
break;



default; //Si jamais il y a un soucis
	?>
	<p>Cette action est impossible</p>
	<?php
} //Fin du switch


?>
</main>
</body>
</html>