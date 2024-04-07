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
                        Modification d'un topic
                    </header>


                </article>
                <br>
                <hr>
                <br>
                <section class="col-12 py-1">

                    <?php




                    //Connexion a la base de données
                    $dtb = dtbConnexion::getConnexion();


                    //Récupération des données du topic par id
                    $recup = $_GET["topic"];
                    $fetchtopic = $dtb->prepare("SELECT * FROM Thread WHERE idThread = ?");
                    $fetchtopic->execute(array($recup));
                    $topic = $fetchtopic->fetch();


                    //Récupération des données de la catégorie
                    $fetchcat = $dtb->prepare("SELECT * FROM Categorie WHERE idCategorie = ?");
                    $fetchcat->execute(array($topic['categorie_id']));
                    $cat = $fetchcat->fetch();


                    //Vérification de session
                    if ($_GET["topic"] == null || $topic["content"] == null ||  ($topic['isClosed'] == true && $_SESSION["rank"] != 1)) {
                        header('Location: ' . 'index.php');
                    }
                    
                    if (($_SESSION["user_id"] != $topic['auteur_id'] && $_SESSION["rank"] != 1)) {
                        header('Location: ' . 'index.php');
                    }

                    ?>

                    <!--Formulaire de la modification d'un topic-->
                    <form method="POST" action="modifthread.php?topic=<?php echo $_GET['topic'] ?>">
                        <label class="col-12 py-1">Catégorie :</label>
                        <select id="choice" class="col-12 py-1" name="Catégorie">
                            <option value="<?php echo $cat['idCategorie'] ?>"><?php echo $cat['nom'] ?></option>
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
                        <input id="sujetinput" class="col-12 py-1" type="text" name="sujet" value="<?php echo $topic['title'] ?>">
                        <br>
                        <br>
                        <label class="col-12 py-1">Contenu :</label>
                        <textarea name="Contenu">
                         <?php echo $topic['content'] ?>
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
            if (isset($_POST["Catégorie"])) {

                if ($_POST["Catégorie"] != null && $_POST["sujet"] != null && $_POST["Contenu"] != null) {
                    $catégorie = $_POST["Catégorie"];
                    $sujet = $_POST["sujet"];
                    $contenu = $_POST["Contenu"];
                    $threadId = $_GET["topic"];

                    //Modification des données dans la base de données
                    $stmt = $dtb->prepare("UPDATE Thread SET categorie_id = :categorie, title = :sujet, content = :contenu
                    WHERE idThread = :threadId;");

                    $stmt->bindParam(':categorie', $catégorie);
                    $stmt->bindParam(':sujet', $sujet);
                    $stmt->bindParam(':contenu', $contenu);
                    $stmt->bindParam(':threadId', $threadId);

                    $stmt->execute();
                    unset($_POST["Catégorie"])
            ?>

                    <script language='Javascript'>
                        alert("Merci, ton topic a bien été modifié !");
                        location.href = "forum.php?categorie=1";
                    </script>


                <?php
                } else {
                    unset($_POST["Catégorie"])
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