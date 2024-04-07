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
                <a id="title2">Bannir</a>
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
    if (!isset($_GET['user']) || $user == null || $user['id_Rank'] == 3 || $user['id_Rank'] == 4) {
        header('Location: index.php');
    }

    //Visuel Bannir suivi du pseudo concerné
    ?>
    <div class="container">
        <div class="row">
            <div id="title" class="col-12">
                Bannir <?php echo $user['username'] ?>
            </div>
        </div>
    </div>
    <hr>
    <br>


    <!---Formulaire de ban--->
    <form method="POST" action="bannir.php?user=<?php echo $userId ?>">
        <label class="col-12 py-1">Type de ban :</label>
        <select id="choice" class="col-12 py-1" name="Type">
            <option value="1">Définitif</option>
            <option value="2">Temporaire</option>
        </select>
        <br>
        <br>
        <label class="col-12 py-1">Raison</label>
        <input id="input" class="col-12 py-1" type="text" name="raison">
        <br>
        <br>
        <label class="col-12 py-1">Jusqu'au : </label>
        <input id="input" class="col-12 py-1" type="date" name="datefin">
        <br>
        <label>*laisser vide si définitif</label>
        <br>
        <br>
        <br>
        <input id="submitbutton" class="col-12 py-1" type="submit" value="Confirmer" name="submit">
    </form>
    <br>


    <!---Vérification du formulaire--->
    <?php
    if (isset($_POST['Type'])) {



        //Si c'est un ban définitif :
        if ($_POST['Type'] == 1) {
            if ($_POST['datefin'] != null) {
    ?>
                <a id="answer2">Un banni définitif n'a pas de date de deban !</a>
            <?php
            } elseif ($_POST['raison'] == null) {
            ?>
                <a id="answer2">Il faut une raison pour bannir quelqu'un !</a>
            <?php
            } else {


                //Intéractions avec la base de données
                $raison = $_POST["raison"];
                $rank = 3;

                $stmt = $dtb->prepare("UPDATE Users SET id_Rank = :idRank
                    WHERE idUser = :userId;");
                $stmt->bindParam(':idRank', $rank);
                $stmt->bindParam(':userId', $user['idUser']);
                $stmt->execute();

                $stmt2 = $dtb->prepare("INSERT INTO Ban (user_id, raison) VALUES (:auteur, :raison)");

                $stmt2->bindParam(':auteur', $user['idUser']);
                $stmt2->bindParam(':raison', $raison);

                $stmt2->execute();
            ?>

                <script language='Javascript'>
                    alert("Merci, l'utilisateur a été banni !");
                    location.href = "index.php";
                </script>


                <?php
            }



            //Si c'est un ban temporaire    
        } else if ($_POST['Type'] == 2) {
            if ($_POST['raison'] && $_POST['datefin'] != null) {
                if ($_POST['datefin'] <= $dategood) {
                ?>
                    <a id="answer2">La date de fin du ban ne peut pas être inférieure ou égale a celle d'aujourd'hui !</a>

                <?php

                } else {



                    //Intéractions avec la base de données
                    $raison = $_POST["raison"];
                    $date_fin = $_POST["datefin"];
                    $rank2 = 4;

                    $stmt = $dtb->prepare("UPDATE Users SET id_Rank = :idRank
                    WHERE idUser = :userId");
                    $stmt->bindParam(':idRank', $rank2);
                    $stmt->bindParam(':userId', $user['idUser']);
                    $stmt->execute();

                    $stmt2 = $dtb->prepare("INSERT INTO BanTemporaire (user_id, raison, date_fin) VALUES (:auteur, :raison, :datefin)");

                    $stmt2->bindParam(':auteur', $user['idUser']);
                    $stmt2->bindParam(':raison', $raison);
                    $stmt2->bindParam(':datefin', $date_fin);

                    $stmt2->execute();

                    ?>

                    <script language='Javascript'>
                        alert("Merci, l'utilisateur a été banni !");
                        location.href = "index.php";
                    </script>
    
    
                    <?php
                }
            } else {
                ?>
                <a id="answer2">Il faut une raison et une date de fin pour bannir quelqu'un temporairement !</a>
            <?php
            }



            //Si jamais la valeur du type de ban ne correspond pas a définitif ni a temporaire (Ce qui ne devrait logiquement pas arriver mais au cas ou on sait jamais dans cette épreuve très dure qui est la vie)
        } else {
            ?>
            <a id="answer2">Le formulaire de ban n'est pas valide !</a>
    <?php
        }
    }
    ?>


</body>

</html>