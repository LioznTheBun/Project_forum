<?php
include('parts/header.php');
include('tools/dtbConnexion.php');
include('tools/basicFunction.php');
include('tools/functionThread.php');
$dtb = dtbConnexion::getConnexion();
$rdmColor = randomColor();
?>

<head>
    <link rel="stylesheet" type="text/css" href="CSS/styleIndex.css" media="all" />
</head>


<main>
<div class="container h-100 justify-content-center align-items-center">
<h3>Bienvenue sur le Forum étudiant de BTS SIO</h3>
<div class="row">
    <!-- Menu -->
    <article class="container col-12 col-xl-8">
        <header></header>
        <div class="row" id="tabMatiere">
            <section class="bg-<?=$rdmColor?> rounded col-11 col-lg-8 row">
                <h4>Informatique</h4>
                <a type="button" class="btn btn-lg btn-dark col-5" id="SLAM" href="forum.php?categorie=1"> SLAM</a>
                <a type="button" class="btn btn-lg btn-dark col-5" id="SISR" href="forum.php?categorie=2"> SISR</a>
                <a type="button" class="btn btn-lg btn-dark col-5" id="MATH" href="forum.php?categorie=3"> MATH</a>
            </section>
            <section class="bg-<?=$rdmColor?> rounded col-11 col-lg-5 row">
                <h4>Culture</h4>
                <a type="button" class="btn btn-lg btn-dark col-5" id="Français" href="forum.php?categorie=4"> Français</a>
                <a type="button" class="btn btn-lg btn-dark col-5" id="CEJM" href="forum.php?categorie=5"> CEJM</a>
            </section>
            <section class="bg-<?=$rdmColor?> rounded col-11 col-lg-5 row">
                <h4>Langue</h4>
                <a type="button" class="btn btn-lg btn-dark col-5" id="Anglais" href="forum.php?categorie=6"> Anglais</a>
                <a type="button" class="btn btn-lg btn-dark col-5" id="Espagnol" href="forum.php?categorie=7"> Espagnol</a>
            </section>
        </div>
    </article>

    <!-- Affichage des derniers threads en date -->

    <aside class="container col-12 col-xl-4">
        <div class=" col-12">
        <?php $topics = getLastThreadsLimit($dtb) ?>
        <h3 class="top-bar">Derniers Threads crées : </h3>
        <ol>
            <?php if($topics !=NULL) : ?>
                <table class="table">
                    <thead class="thead-light">
                        <tr class="bg-<?=$rdmColor ?>">
                            <th class="d-none d-md-table-cell" scope="col">Avatar</th>
                            <th scope="col">Utilisateur</th>
                            <th scope="col">Sujet</th>
                            <th scope="col">Date Création</th>
                            <th scope="col">Voir</th>
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
                                <td class="d-none d-md-table-cell"><img id="imgg" src="img/avatars/<?=$actualUser['avatar'] ?>"></td>
                                <td><a id="userclick" href="profil.php?m=<?php echo $actualUser["idUser"] ?>" ><?php echo $actualUser["username"] ?></a></td>
                                <td><?php echo $topic["title"] ?></td>
                                <td><?php echo $topic["date_creation"] ?></td>
                                <td><a class="btn btn-outline-<?=$rdmColor ?>" href="thread.php?topic=<?=$topic["idThread"] ?>" target="blank" role="button"> </a></td>
                            </tr>
                        </tbody>
                <?php
                }
                
            else :?>
                <p>Il n'y a aucun thread d'enregistré. <br>
                Ayez cette image de chaton triste en échange :                </p>
                <img src="img/" alt="On a pas eu le budget pour, désolé :c">
            <?php endif;?>
        </ol>
    </div>
    </aside>
</div>
</div>
</main>
</body>
</html>