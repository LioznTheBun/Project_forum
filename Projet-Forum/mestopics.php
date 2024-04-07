<!---Import de la police d'écriture--->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Changa+One&family=Sono&display=swap');
</style>

<?php
include('tools/functionThread.php');
//Récupération de l'utilisateur actuel
$actualUser = getUserById($membre, $dtb)
?>

<hr>

<?php

//Récupération des topics de l'utilisateur actuel
$topics = getThreadByUser($membre, $dtb);


?>
<div class="container">
<div class="littleTopic">
    <div class="row">
        <div class="col-12">
            <a id="title">Liste des topics de <?=$actualUser['username']?> :</a>
        </div>
        <br>
        <br>
    </div>
    <?php

    $rdmColor = randomColor();

    //Affichage des topics réduits
    if ($topics != NULL) {
    ?>
        <table class="table">
            <thead class="thead-light">
                <tr class="bg-<?php echo $rdmColor ?> ">
                    <th scope="col">Sujet</th>
                    <th scope="col">Date de création</th>
                    <th scope="col">Voir</th>
                    <?php if(isset($_SESSION['user_id']) && $membre == $_SESSION['user_id']) : ?>
                    <th scope="col">Modifier</th>
                    <?php endif; ?>
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
                        <td><?php echo $topic["title"] ?></td>
                        <td><?php echo $topic["date_creation"] ?></td>
                        <td><a class="btn btn-outline-<?=$rdmColor ?>" href="thread.php?topic=<?=$topic["idThread"] ?>" target="blank" role="button"> </a></td>
                        <?php if((isset($_SESSION['user_id']) && $membre == $_SESSION['user_id'] && $topic['isClosed'] == false) || $_SESSION['rank'] == 1 ) { ?>
                        <td><a class="btn btn-outline-<?=$rdmColor ?>" href="modifthread.php?topic=<?=$topic["idThread"] ?>" target="blank" role="button"> </a></td>
                        <?php } ?>
                    </tr>
                </tbody>



        <?php
            }
        }

        ?>
        </table>
        <br>

        <?php if ($topics == NULL) : ?>
            <br>
            <br>
            <div class="row">
                <br>
                <div class="col-12">
                    <a id="answer1"><?=$actualUser['username']?> n'a publié aucun topic pour l'instant</a>
                </div>
            </div>
        <?php endif; ?>
</div>
</div>
</body>
</html>
