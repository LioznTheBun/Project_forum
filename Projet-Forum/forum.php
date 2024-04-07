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
    <link rel="stylesheet" href="CSS/styleForum.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Topics</title>
</head>

<body class="body">

    <br>
    <br>
    <br>

    <!-- Choix de la catégorie -->
    <form method="POST">
        <label class="col-12 py-1">Catégorie :</label>
        <select id="choice" class="col-12 py-1" name="Catégorie">
            <option value="4">Français</option>
            <option value="6">Anglais</option>
            <option value="7">Espagnol</option>
            <option value="3">Math</option>
            <option value="2">SISR</option>
            <option value="1">SLAM</option>
            <option value="5">CEJM</option>
        </select>
        <br>
        <br>
        <input id="submitbutton" class="col-12 py-1" type="submit" value="Valider" name="submit">
    </form>
    <?php
    if (isset($_POST["Catégorie"])) {


        $catégorie = $_POST["Catégorie"];
        header('Location: forum.php?categorie=' . $catégorie);
        exit();
    }

    ?>
    <br>
    <hr>
    <br>


    <?php




    //Connexion à la base de données


    $dtb = dtbConnexion::getConnexion();


    //Récupération des topics par catégorie
    $recup = $_GET["categorie"];
    $fetchtopics = $dtb->prepare("SELECT * FROM Thread WHERE categorie_id = ? ORDER BY date_creation DESC");
    $fetchtopics->execute(array($recup));
    $topics = $fetchtopics->fetchAll();


    //Vérification du paramètre
    if ($_GET["categorie"] < 1 || $_GET["categorie"] > 7) {
        header('Location: ' . 'index.php');
    }



    //Récupération de la catégorie
    $fetchcat = $dtb->prepare("SELECT * FROM Categorie WHERE idCategorie = ?");
    $fetchcat->execute(array($recup));
    $cat = $fetchcat->fetch();

    ?>
    <div class="container">
        <div class="littleTopic">
            <div class="row">
                <div class="col-12">
                    <a id="title">Liste des topics en <?php echo $cat['nom'] ?> :</a>
                </div>
                <br>
                <br>
            </div>
            <?php

            //génération des couleurs random
            $colors = ['info', 'success', 'warning', 'danger', 'primary'];
            $rdm = rand(0, 4);
            $rdmColor = $colors[$rdm];



            //Affichage des topics réduits
            if ($topics != NULL) {
            ?>
                <table class="table">
                    <thead class="thead-light">
                        <tr class="bg-<?php echo $rdmColor ?> ">
                            <th scope="col">Avatar</th>
                            <th scope="col">Auteur</th>
                            <th scope="col">Sujet</th>
                            <th scope="col">Date </th>
                            <th scope="col">Voir </th>
                        </tr>
                    </thead>

                    <?php
                    $cpt1 = 0;
                    foreach ($topics as $topic) {
                        $cpt1++;

                        $idUser = $topic["auteur_id"];
                        $fetchuser = $dtb->prepare("SELECT * FROM Users WHERE idUser = ?");
                        $fetchuser->execute(array($idUser));
                        $actualUser = $fetchuser->fetch();
                    ?>
                        <tbody>
                            <tr>
                                <td><img id="imgg" src="img/avatars/<?php echo $actualUser['avatar'] ?>"></td>
                                <td><a id="userclick" href="profil.php?m=<?php echo $actualUser["idUser"] ?>" ><?php echo $actualUser["username"] ?></a></td>
                                <td><?php echo $topic["title"] ?></td>
                                <td><?php echo $topic["date_creation"] ?></td>
                                <td><a class="btn btn-outline-<?php echo $rdmColor ?>" href="thread.php?topic=<?php echo $topic["idThread"] ?>" target="blank" role="button"> </a>
                                </td>
                            </tr>
                        </tbody>



                <?php
                    }
                }

                ?>
                </table>
                <br>

                <?php

                //Message informatif au cas ou aucun topic n'existe dans la catégorie
                if ($topics == NULL) {
                ?>
                    <br>
                    <br>
                    <div class="row">
                        <br>
                        <div class="col-12">
                            <a id="answer1">Malheureusement, aucun topic n'éxiste dans cette catégorie :'(</a>
                        </div>
                    </div>
                <?php
                }

                ?>
        </div>




    </div>

</body>

</html>