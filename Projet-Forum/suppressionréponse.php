<?php
//Inclusion du header
include("parts/header.php");
include("tools/dtbConnexion.php");
?>

<!---Import de la police d'écriture--->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Londrina+Solid:wght@300;400&family=Sono&display=swap');
</style>


<!doctype html>
<html lang="fr">

<head>
    <link rel="stylesheet" href="">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Suppression</title>
</head>

<body class="body">

    <?php

    //Connexion a la base de données
    $dtb = dtbConnexion::getConnexion();

    //Récupération de l'auteur du topic
    $idRep = $_GET['reponse'];
    $fetchrep = $dtb->prepare("SELECT * FROM Commentaire WHERE idCommentaire = ?");
    $fetchrep->execute(array($idRep));
    $reponse = $fetchrep->fetch();


    //Vérification de la session
    if ($idRep == null || $reponse["content"] == null) {
        header('Location: ' . 'index.php');
    }

    if (isset($_SESSION["user_id"])) {
        if (($_SESSION["user_id"] != $reponse['auteur_id'] && $_SESSION["rank"] != 1) || ($idRep == null)) {
            header('Location: ' . 'index.php');
        } else {


            //Suppression
            $fetchsup = $dtb->prepare("DELETE FROM Commentaire WHERE idCommentaire = ?");
            $fetchsup->execute(array($idRep));
            echo "Thread Supprimé";
    ?>
            <script language='Javascript'>
                alert("la réponse a bien été supprimée !");
                location.href = "forum.php?categorie=1";
            </script>
    <?php
        }
    }
    else {
        header('Location: ' . 'index.php');
    }




    ?>
</body>

</html>