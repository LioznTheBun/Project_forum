<?php
//Inclusion du header
include("parts/header.php");
include("tools/dtbConnexion.php");
include('tools/basicFunction.php');
?>

<!---Import de la police d'écriture--->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Sono&family=Suez+One&display=swap');
</style>

<head>
    <link rel="stylesheet" href="CSS/styleThread.css">
</head>
<br>
<?php

//Connexion à la base de données
$dtb = dtbConnexion::getConnexion();

//Récupération des données du topic par id
$recup = $_GET["topic"];
$fetchtopic = $dtb->prepare("SELECT * FROM Thread WHERE idThread = ?");
$fetchtopic->execute(array($recup));
$topic = $fetchtopic->fetch();

//Récupération de l'auteur du topic
$fetchuser = $dtb->prepare("SELECT * FROM Users WHERE idUser = ?");
$fetchuser->execute(array($topic["auteur_id"]));
$auteur = $fetchuser->fetch();

//Récupération de l'utilisateur actuel
if (isset($_SESSION["user_id"]))
    getUserById($_SESSION["user_id"], $dtb);

//Vérification du paramètre
if ($recup == null || $topic == null)
    header('Location: ' . 'index.php');
?>

<!--Affichage du pseudo, de l'avatar, de la date de création-->
<div class="container">
    <div class="row">
        <div id="divAvatarPseudo" class="col-6">
            <img id="imgg" src="img/avatars/<?= $auteur['avatar'] ?>">
            <a href="profil.php?m=<?php echo $auteur["idUser"] ?>" class="BigTXT"><?= $auteur["username"] ?> </a>
        </div>



        <!---Affichage de l'avatar de l'auteur--->
        <div id="divAvatarPseudo" class="col-6">
            <a class="BigTXT"><?= $topic["date_creation"] ?></a>
        </div>
    </div>
    <hr>
    <?php
    if ($topic['isClosed'] == 1) {
    ?> <a id="cloremsg">⚠ Ce forum est clos ! Seuls les administrateurs peuvent encore le modifier. ⚠</a>
        <br> <?php
            } else {
                ?> <br> <?php
                    }

                        ?>

    <!--Affichage du sujet-->
    <div id="sujetRow" class="row">
        <div id="sujet" class="col-11">
            <a class="txt2"><?= $topic["title"] ?></a>
        </div>
    </div>




    <!--Affichage du bouton clore/rouvrir ce topic-->
    <?php
    if (isset($_SESSION['rank'])) {
        if ($_SESSION['rank'] == 1 && $topic["isClosed"] == false) {
    ?>
            <div id="divclore" class="row">
                <a role="button" href="thread.php?topic=<?php echo $recup ?>&action=clore" id="clore">Clore ce topic</a>
            </div>
            <?php
            if (isset($_GET['action'])) {
                if ($_GET['action'] == 'clore' && $_SESSION['rank'] == 1) {
                    $stmt = $dtb->prepare("UPDATE Thread SET isClosed = TRUE WHERE idThread = :threadId;");
                    $stmt->bindParam(':threadId', $recup);
                    $stmt->execute();
                    header('Location: thread.php?topic=' . $recup);
                }
                if ($_GET['action'] == 'clore' && $_SESSION['rank'] != 1) {
                    header('Location: index.php');
                }
            }
        } else if ($_SESSION['rank'] == 1 && $topic["isClosed"] == true) { ?>
            <div id="divclore" class="row">
                <a role="button" href="thread.php?topic=<?php echo $_GET['topic'] ?>&action=rouvrir" id="clore">Rouvrir ce topic</a>
            </div>
            <?php
            if (isset($_GET['action'])) {
                if ($_GET['action'] == 'rouvrir' && $_SESSION['rank'] == 1) {
                    $stmt = $dtb->prepare("UPDATE Thread SET isClosed = FALSE WHERE idThread = :threadId;");
                    $stmt->bindParam(':threadId', $recup);
                    $stmt->execute();
                    header('Location: thread.php?topic=' . $recup);
                }
                if ($_GET['action'] == 'clore' && $_SESSION['rank'] != 1) {
                    header('Location: index.php');
                }
            }
        } else {
            ?> <br>
        <?php }
    } else {
        ?><br><?php
            }
                ?>



    <!---Affichage du contenu--->
    <div id="contentRow" class="row">
        <div id="content" class="col-11">
            <a class="txt"><?= $topic["content"] ?></a>
        </div>
    </div>
    <br>

    <!---Boutons pour modifier et suprimer le topic déstiné aux admins ou à l'auteur du topic--->
    <?php if (isset($_SESSION["user_id"]) && ($_SESSION["user_id"] == $auteur['idUser'] || $_SESSION['rank'] == 1)) { ?>
        <div id="boutonRow" class="row">
            <div class="col-11 col-md-6">
                <?php
                if ($topic["isClosed"] == false || $_SESSION['rank'] == 1) { ?>
                    <a id="boutonMS" class="btn btn-warning" href="modifthread.php?topic=<?= $topic['idThread'] ?>" target="blank" role="button">Modifier</a>
                <?php } ?>
            </div>
            <div class="col-11 col-md-6">
                <a id="boutonMS" class="btn btn-danger" href="suppressionthread.php?topic=<?= $topic['idThread'] ?>" target="blank" role="button">Supprimer</a>
            </div>
        </div>
        <br>
    <?php } ?>
    <br>
    <hr>
    <br>

    <!---Script Wysiwyg--->
    <script>
        tinymce.init({
            selector: 'textarea',
            min_height: 200,
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            mergetags_list: [{
                    value: 'First.Name',
                    title: 'First Name'
                },
                {
                    value: 'Email',
                    title: 'Email'
                },
            ]
        });
        var med = tinymce.get('myTinyEditor');
        var contHtml = med.getContent();
    </script>

    <!---Section Réponses--->
    <div id="sujetRow" class="row">
        <div id="sujet" class="col-11">
            <a class="txt2">Réponses</a>
        </div>
    </div>
    <br>

    <?php
    if (isset($_SESSION['rank'])) {
        if ((isset($_SESSION['user_id']) && $topic['isClosed'] == false) || $_SESSION['rank'] == 1) { ?>



            <!---Wysiwig pour poster une réponse--->
            <form method="POST" action="thread.php?topic=<?= $recup ?>">
                <label class="col-11 py-1">Répondre :</label>
                <textarea name="Réponse" placeholder="Écrivez une réponse !">
            </textarea>
                <br>
                <br>
                <input id="submitbutton" class="col-11 py-1" type="submit" value="Envoyer" name="submit">
            </form>
            <br>
    <?php }
    } ?>





    <!---Récupération des réponses--->
    <div id="reponsesRow" class="row">
        <?php
        $fetchtopics = $dtb->prepare("SELECT * FROM Commentaire WHERE thread_id = ? ORDER BY date_creation ASC");
        $fetchtopics->execute(array($recup));
        $coms = $fetchtopics->fetchAll();
        ?>



        <!--Boucle qui parcourt les réponses-->
        <?php if ($coms != null) :
            foreach ($coms as $com) :


                //Récupération de l'auteur de la réponse
                $auteurCommentaire = getUserById($com['auteur_id'], $dtb);
        ?>
                <br>
                <!---Affichage de la réponse--->
                <div class="container">
                    <div class="row">
                        <div id="divAvatarPseudoR" class="col-6">
                            <img id="imggR" src="img/avatars/<?= $auteurCommentaire['avatar'] ?>">
                            <a href="profil.php?m=<?php echo $auteurCommentaire["idUser"] ?>" class="BigTXTR" ><?= $auteurCommentaire["username"] ?></a>
                        </div>
                        <div id="divAvatarPseudoR" class="col-6">
                            <a class="BigTXTR"><?= $com["date_creation"] ?></a>
                        </div>
                    </div>
                    <br>
                    <div id="contentRow" class="row">
                        <div id="comcontent" class="col-11">
                            <a class="txt"><?= $com["content"] ?></a>
                        </div>
                    </div>
                    <br>



                    <!--Boutons pour modifier et suprimer la réponse déstiné aux admins ou à l'auteur de la réponse-->
                    <?php if (isset($_SESSION["user_id"]) && ($_SESSION["user_id"] == $auteurCommentaire['idUser'] || $_SESSION['rank'] == 1)) : ?>
                        <div id="boutonRow" class="row">
                            <div class="col-11 col-md-6">
                                <?php
                                if ($topic["isClosed"] == false || $_SESSION['rank'] == 1) { ?>
                                    <a id="boutonMSR" class="btn btn-warning" href="modifréponse.php?reponse=<?= $com['idCommentaire'] ?>" target="blank" role="button">Modifier</a>
                                <?php } ?>
                            </div>
                            <div class="col-11 col-md-6">
                                <a id="boutonMSR" class="btn btn-danger" href="suppressionréponse.php?reponse=<?= $com['idCommentaire'] ?>" target="blank" role="button">Supprimer</a>
                            </div>
                        </div>
                        <br>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else : ?>
                <div id="noRep" class="col-12">
                    <a class="txt">Oups.. Il n'y a aucune réponse sur ce topic pour le moment...</a>
                </div>
            <?php endif; ?>



            <!---Vérifications de l'envoi de la réponse--->
            <?php if (isset($_POST["Réponse"])) :
                if ($_POST["Réponse"] != null) :
                    $réponse = $_POST["Réponse"];

                    //Transfert des données de la réponse à la base de données
                    $dtb = dtbConnexion::getConnexion();
                    $stmt = $dtb->prepare("INSERT INTO Commentaire (auteur_id, thread_id, content, date_creation) VALUES (:auteur_id, :thread_id, :content, CURRENT_TIMESTAMP)");
                    $stmt->bindParam(':auteur_id', $_SESSION['user_id']);
                    $stmt->bindParam(':thread_id', $recup);
                    $stmt->bindParam(':content', $_POST["Réponse"]);
                    $stmt->execute();
            ?>
                    <script language='Javascript'>
                        alert("Merci, ton commentaire a bien été envoyé !");
                        location.href = "thread.php?topic=<?= $recup ?>";
                    </script>
                <?php else : ?>
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <p>Merci de remplir la case réponse !</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <br>
            <br>
                </div>
                <br>
                <br>
                <br>
    </div>
    </body>

    </html>