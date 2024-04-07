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
    $idTopic = $_GET['topic'];
    $fetchtopic = $dtb->prepare("SELECT * FROM Thread WHERE idThread = ?");
    $fetchtopic->execute(array($idTopic));
    $topic = $fetchtopic->fetch();


    //Vérification de la session
    if ($_GET["topic"] == null || $topic["content"] == null) {
        header('Location: ' . 'index.php');
    }
;
    if (isset($_SESSION["user_id"])) {
        if (($_SESSION["user_id"] != $topic['auteur_id'] && $_SESSION["rank"] != 1) || ($_GET['topic'] == null)) {
            header('Location: ' . 'index.php');
        } else {


            //Suppression
            $fetchsup = $dtb->prepare("DELETE FROM Thread WHERE idThread = ?");
            $fetchsup->execute(array($idTopic));
            echo "Thread Supprimé";
    ?>
            <script language='Javascript'>
                alert("le topic a bien été supprimé!");
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