<?php

session_name("session_connexion");
session_start();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link rel="icon" type="image/png" href="#" />
    <script src="https://cdn.tiny.cloud/1/r3firjppr367z698af7f1dd4dnsohio3oyw1qnubl2twd8mn/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <title>Forum</title>
</head>

<body>
<header>
<nav class="navbar sticky-top navbar-expand-md navbar-light bg-light">
<div class="container-fluid">
    <a class="navbar-brand" href="#"><img class="icon" src="img/icon-lasalle.png" width="100" height="70" alt="">Forum BTS SIO</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse " id="navbarTogglerDemo01">
        <ul class="navbar-nav mb-2 mb-lg-0 align-items-center">
            <li class="nav-item">
            <a class="nav-link" href="index.php">Acceuil</a>
            </li>
            <li>
            <a class="nav-link" onclick="window.open('documentation/doc.odt', '_blank');">Documentation</a>
            </li>
            <?php if (isset($_SESSION['user_id'])) : ?>
                <?php if ($_SESSION['rank'] == 1) : ?>
                    <li>
                    <a class="nav-link" href="admin.php">Modération</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                <a class="nav-link" href="post.php">Créer un topic</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="deconnexion.php">Déconnexion</a>
                </li>
                <li>
                <a class="nav-link" href="profil.php"><img style="max-width: 40px; max-height: 40px;" src="<?=$_SESSION['avatar'] ?>" class="rounded-circle" alt="User Profile Picture"></a>
                </li>
            <?php else : ?>
                <li class="nav-item">
                <a class="nav-link" href="register.php?keyword=connexion">Se connecter</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="register.php?keyword=register">Créer un compte</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
</nav>
</header>