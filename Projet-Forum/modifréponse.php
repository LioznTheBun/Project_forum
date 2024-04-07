<?php
include("parts/header.php");
include("tools/dtbConnexion.php");

?>

<head>
    <link rel="stylesheet" type="text/css" href="CSS/stylePost.css" media="all" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body class="bodyy">
    <main class="container">
        <br>
        <article class="row text-center">

            <header>

            </header>

            <div class="container">
                <br>
                <br>
                <article class="row text-center">


                    <header class="col-12 py-1">
                        Modification d'une réponse
                    </header>


                </article>
                <br>
                <hr>
                <br>
                <section class="col-12 py-1">

                    <?php




                    //Connexion a la base de données
                    $dtb = dtbConnexion::getConnexion();


                    //Récupération des données de la réponse par id
                    $recup = $_GET["reponse"];
                    $fetchrep = $dtb->prepare("SELECT * FROM Commentaire WHERE idCommentaire = ?");
                    $fetchrep->execute(array($recup));
                    $reponse = $fetchrep->fetch();

                    //Récupération du topic de la réponse
                    $fetchtop = $dtb->prepare("SELECT * FROM Thread WHERE idThread = ?");
                    $fetchtop->execute(array($reponse['thread_id']));
                    $topic = $fetchtop->fetch();


                    //Vérification de session
                    if (($_SESSION["user_id"] != $reponse['auteur_id'] && $_SESSION["rank"] != 1) || (!isset($_SESSION["user_id"])) || $recup == null || $reponse == null || (($topic['isClosed'] == true && $_SESSION["rank"] != 1))) {
                        header('Location: ' . 'index.php');
                    }

                    ?>

                    <!--Formulaire de la modification d'une reponse-->
                    <form method="POST" action="">
                        <label class="col-12 py-1">Contenu :</label>
                        <textarea name="Contenu">
                         <?php echo $reponse['content'] ?>
                        </textarea>
                        <br>
                        <br>
                        <br>
                        <input id="submitbutton" class="col-12 py-1" type="submit" value="Modifier" name="submit">
                    </form>
                </section>
            </div>

        </article>
        <br>
        <hr>
        <br>


        <!--Code JavaScript pour le WYSIWYG-->
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


        <div class="php">
            <?php


            //Vérifications de l'envoi du formulaire

            if (isset($_POST['Contenu'])) {
                if ($_POST["Contenu"] != null) {
                    $contenu = $_POST["Contenu"];

                    //Modification des données de la base de données
                    $stmt = $dtb->prepare("UPDATE Commentaire SET  content = :contenu
                    WHERE idCommentaire = :repId;");

                    $stmt->bindParam(':contenu', $contenu);
                    $stmt->bindParam(':repId', $recup);

                    $stmt->execute();
                    unset($_POST["Contenu"])
            ?>

                    <script language='Javascript'>
                        alert("Merci, la réponse a bien été modifiée !");
                        location.href = "forum.php?categorie=1";
                    </script>


                <?php
                } else {
                    unset($_POST["Catégorie"])
                ?>
                    <a id="answer2">La réponse n'est pas valide ! veuillez remplir la case...</a>
            <?php
                }
            }




            ?>

        </div>
        <br>
        <?php


        ?>
    </main>
</body>

</html>