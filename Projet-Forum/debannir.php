<?php
include('parts/header.php');
include('tools/dtbConnexion.php');
?>

<?php
//Inclusion du header
include('tools/basicFunction.php');


//Vérification de session
if (!isset($_SESSION['rank']) || $_SESSION['rank'] != 1) {
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
                <a id="title2">Debannir</a>
            </div>
        </div>
    </div>
    <br>
    <br>
    <?php

    //Récupération de l'heure actuelle
    $dt = new \DateTime();
    $dategood = $dt->format('Y-m-d');

    //Connexion à la base de données
    $dtb = dtbConnexion::getConnexion();

    //Récupération de l'utilisateur
    $userId = $_GET['user'];
    $fetchuser = $dtb->prepare("SELECT * FROM Users WHERE idUser = ?");
    $fetchuser->execute(array($userId));
    $user = $fetchuser->fetch();

    //Vérification de paramètres
    if (!isset($_GET['user']) || $user == null || $user['id_Rank'] == 1 || $user['id_Rank'] == 2) {
        header('Location: index.php');
    }

    //Visuel Bannir suivi du pseudo concerné
    ?>
    <div class="container">
        <div class="row">
            <div id="title" class="col-12">
                Debannir <?php echo $user['username'] ?>
            </div>
        </div>
    </div>
    <hr>
    <br>
    <?php





    //Si c'est définitif
    if ($user['id_Rank'] == 3) {

        //Récupération du ban de l'utilisateur
        $userId = $_GET['user'];
        $fetchban = $dtb->prepare("SELECT * FROM Ban WHERE user_id = ?");
        $fetchban->execute(array($userId));
        $ban = $fetchban->fetch();


        //Deban
        $rank = 2;
        $banId = $ban['id'];

        $stmt = $dtb->prepare("UPDATE Users SET id_Rank = :idRank
        WHERE idUser = :userId;");
        $stmt->bindParam(':idRank', $rank);
        $stmt->bindParam(':userId', $user['idUser']);
        $stmt->execute();

        $fetchsup = $dtb->prepare("DELETE FROM Ban WHERE id = ?");
        $fetchsup->execute(array($banId));
    ?>
        <script language='Javascript'>
            alert("Merci, l'utilisateur a été débanni !");
            location.href = "index.php";
        </script>
    <?php
    }

    //Si c'est temporaire
    if ($user['id_Rank'] == 4) {


        //Récupération du ban de l'utilisateur
        $userId = $_GET['user'];
        $fetchbanT = $dtb->prepare("SELECT * FROM BanTemporaire WHERE user_id = ?");
        $fetchbanT->execute(array($userId));
        $banTemp = $fetchbanT->fetch();



        //Deban
        $rank = 2;
        $banTempId = $banTemp['id'];

        $stmt = $dtb->prepare("UPDATE Users SET id_Rank = :idRank
        WHERE idUser = :userId;");
        $stmt->bindParam(':idRank', $rank);
        $stmt->bindParam(':userId', $user['idUser']);
        $stmt->execute();

        $fetchsup = $dtb->prepare("DELETE FROM BanTemporaire WHERE id = ?");
        $fetchsup->execute(array($banTempId));
    ?>
        <script language='Javascript'>
            alert("Merci, l'utilisateur a été débanni !");
            location.href = "index.php";
        </script>
    <?php
    }

    ?>


</body>

</html>