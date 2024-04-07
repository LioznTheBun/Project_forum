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
                        Création d'un topic
                    </header>


                </article>
                <br>
                <hr>
                <br>
                <section class="col-12 py-1">

                    <?php

                    //Vérification de session
                    if ($_SESSION["user_id"] == null) {
                        header('Location: ' . 'index.php');
                    }


                    ?>

                    <!--Formulaire de la création d'un topic-->
                    <form method="POST" action="post.php">
                        <label class="col-12 py-1">Catégorie :</label>
                        <select id="choice" class="col-12 py-1" name="Catégorie">
                            <option value="4">Français</option>
                            <option value="6">Anglais</option>
                            <option value="7">Espagnol</option>
                            <option value="3">Maths</option>
                            <option value="2">SISR</option>
                            <option value="1">SLAM</option>
                            <option value="5">CEJM</option>
                        </select>
                        <br>
                        <br>
                        <label class="col-12 py-1">Sujet :</label>
                        <input id="sujetinput" class="col-12 py-1" type="text" name="sujet">
                        <br>
                        <br>
                        <label class="col-12 py-1">Contenu :</label>
                        <textarea name="Contenu">
                         Rédigez votre topic juste ici ! :)
                        </textarea>
                        <br>
                        <br>
                        <br>
                        <input id="submitbutton" class="col-12 py-1" type="submit" value="Créer" name="submit">
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
            if (isset($_POST["Catégorie"])) {

                if ($_POST["Catégorie"] != null && $_POST["sujet"] != null && $_POST["Contenu"] != null) {
                    $catégorie = $_POST["Catégorie"];
                    $sujet = $_POST["sujet"];
                    $contenu = $_POST["Contenu"];

                    //Envoie des données à la base de données
                    $dtb = dtbConnexion::getConnexion();
                    $stmt = $dtb->prepare("INSERT INTO Thread (auteur_id, categorie_id,  title, content, date_creation) VALUES (:auteur, :categorie, :sujet, :texte, CURRENT_TIMESTAMP)");

                    $stmt->bindParam(':auteur', $_SESSION['user_id']);
                    $stmt->bindParam(':categorie', $catégorie);
                    $stmt->bindParam(':sujet', $sujet);
                    $stmt->bindParam(':texte', $contenu);

                    $stmt->execute();
            ?>

                    <script language='Javascript'>                      
                        alert("Merci, ton topic a bien été envoyé !");
                        location.href = "index.php";
                    </script>


                <?php
                } else {
                ?>
                    <a id="answer2">Le topic n'est pas valide ! Certaines cases sont vides...</a>
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