<?php
//Inclusion du header
include("parts/header.php");
include("tools/dtbConnexion.php");
include('tools/basicFunction.php');


//Vérification de session
if (!isset($_SESSION['rank'])) {
    header('Location: index.php');
}

if ($_SESSION['rank'] != 1) {
    header('Location: index.php');
}

?>

<!---Import de la police d'écriture--->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Changa+One&family=Sono&display=swap');
</style>

<head>
    <link rel="stylesheet" href="CSS/styleAdmin.css">
</head>
<br>

<body>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <a id="title2">Modération</a>
            </div>
        </div>
    </div>
    <br>
    <br>
    <?php

    //Connexion à la base de données
    $dtb = dtbConnexion::getConnexion();

    //Récupération des utilisateurs
    $fetchusers = $dtb->prepare("SELECT * FROM Users ORDER BY idUser DESC");
    $fetchusers->execute();
    $users = $fetchusers->fetchAll();

    //génération des couleurs random
    $colors = ['info', 'success', 'warning', 'danger', 'primary'];
    $rdm = rand(0, 4);
    $rdmColor = $colors[$rdm];

    //Affichage des utilisateurs
    if ($users != NULL) {
    ?>
        <div class="container">
            <div class="row">
                <div id="title" class="col-12">
                    Liste des utilisateurs :
                </div>
            </div>
        </div>
        <hr>
        <br>
        <div class="container">
            <div class="row">
                <table class="table">
                    <thead class="thead-light">
                        <tr class="bg-<?php echo $rdmColor ?> ">
                            <th scope="col">Avatar</th>
                            <th scope="col">Pseudo</th>
                            <th scope="col">Bannir</th>
                            <th scope="col">Debannir</th>
                        </tr>
                    </thead>

                    <?php
                    $cpt1 = 0;
                    foreach ($users as $user) {
                        $cpt1++;
                    ?>
                        <tbody>
                            <tr>
                                <td><img id="imgg" src="img/avatars/<?php echo $user['avatar'] ?>"></td>
                                <td><?php echo $user["username"] ?></td>
                                <td><?php if ($user['id_Rank'] != 3 && $user['id_Rank'] != 4) {
                                    ?><a class="btn btn-outline-<?php echo $rdmColor ?>" href="bannir.php?user=<?php echo $user["idUser"] ?>" target="blank" role="button"></a>
                                    <?php } ?>
                                </td>
                                <td><?php if ($user['id_Rank'] == 3 || $user['id_Rank'] == 4) {
                                    ?><a class="btn btn-outline-<?php echo $rdmColor ?>" href="debannir.php?user=<?php echo $user["idUser"] ?>" target="blank" role="button"></a>
                                    <?php } ?>
                                </td>
                            </tr>
                        </tbody>



                <?php
                    }
                }

                ?>
                </table>
            </div>
        </div>
        <br>
        <div class="container">

            <?php

            //Message informatif au cas ou aucun utilisateur n'existe
            if ($users == NULL) {
            ?>
                <br>
                <br>
                <div class="row">
                    <br>
                    <div class="col-12">
                        <a id="answer1">Malheureusement, aucun utilisateur n'existe sur le site... </a>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
</body>

</html>