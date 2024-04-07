<?php

function getUserById($id, $dtb){
    $query = $dtb->prepare(<<<SQL
        SELECT idUser,username, avatar, email, id_Rank
        FROM users 
        WHERE idUser=:id
        SQL);
    $query->bindValue(':id', $id , PDO::PARAM_INT);
    $query->execute();
    $data = $query->fetch();
    $query->CloseCursor();
    return $data;
}

function createAccount($pseudo, $pass, $email, $dtb){
    $state = $dtb->prepare(<<<SQL
        INSERT INTO users (username, password, email, id_Rank, avatar)
        VALUES (?, SHA2(?, 256), ?, ?, ?)
        SQL);
    $state->execute(array($pseudo, $pass, $email, 2, 'basic.jpg'));
    return "Votre compte a été crée";
}

function randomColor(){
    $colors = ['info', 'success', 'warning', 'danger', 'primary'];
    $rdm = rand(0, 4);
    $rdmColor = $colors[$rdm];
    return $rdmColor;
}

function isUserInDTB($membre, $dtb){
    $query = $dtb->prepare(<<<SQL
        SELECT idUser
        FROM users
        SQL);
    $query->execute();
    $data = $query->fetchAll();
    foreach ($data as $user){
        if($membre == $user['idUser']) 
            return;
    }
    header('Location: index.php');
}

function getRank($rank, $dtb){
    $query = $dtb->prepare(<<<SQL
        SELECT libelle
        FROM Rank
        WHERE idRank = ?
        SQL);
    $query->execute(array($rank));
    $data= $query->fetch();
    $query->CloseCursor();
    return $data;
}

?>