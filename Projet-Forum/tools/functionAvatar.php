<?php

function moveAvatar($avatar)
{
    $extension_upload = strtolower(substr(strrchr($avatar['name'], '.'), 1));
    $name = time();
    $nomavatar = str_replace(' ', '', $name) . "." . $extension_upload;
    $name = "./img/avatars/" . str_replace(' ', '', $name) . "." . $extension_upload;
    move_uploaded_file($avatar['tmp_name'], $name);
    return $nomavatar;
}

function changeAvatar($membre, $avatar, $dtb)
{
    if (empty($_FILES['avatar']['size'])) return;
    if ($avatar != "basic.jpg")
        unlink("./img/avatars/" . $avatar);
    $nomavatar = moveAvatar($_FILES['avatar']);
    $query = $dtb->prepare(<<<SQL
        UPDATE users
        SET avatar = :avatar 
        WHERE idUser = :id
        SQL);
    $query->bindValue(':avatar', $nomavatar, PDO::PARAM_STR);
    $query->bindValue(':id', $membre, PDO::PARAM_INT);
    $query->execute();
    $query->CloseCursor();
    
}

function removeAvatar($membre ,$avatar, $dtb)
{
    if ($avatar != "basic.jpg")
        unlink("./img/avatars/" . $avatar);
    $query = $dtb->prepare(<<<SQL
        UPDATE users
        SET avatar="basic.jpg" 
        WHERE idUser = :id
        SQL);
    $query->bindValue(':id', $membre, PDO::PARAM_INT);
    $query->execute();
    $query->CloseCursor();
}

function verifAvatar(){
    $verifAvatar = [
        'avatar_erreur' => NULL,
        'avatar_erreur1' => NULL,
        'avatar_erreur2' => NULL,
        'avatar_erreur3' => NULL ,
        'i' => 0
    ];
    if (!empty($_FILES['avatar']['size'])) {
    $maxsize = 1000000000; //Poid de l'image
    $maxwidth = 1000; //Largeur de l'image
    $maxheight = 1000; //Longueur de l'image
    $extensions_valides = ['jpg', 'jpeg', 'gif', 'png', 'bmp'];
    if ($_FILES['avatar']['error'] > 0) 
        $verifAvatar['avatar_erreur'] = "Erreur lors du tranfsert de l'avatar : ";
    if ($_FILES['avatar']['size'] > $maxsize) {
        $verifAvatar['i']++;
        $verifAvatar['avatar_erreur1'] = "Le fichier est trop gros :
        (<strong>" . $_FILES['avatar']['size'] . " Octets</strong>
        contre <strong>" . $maxsize . " Octets</strong>)";
    }
    $extension_upload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
    if (!in_array($extension_upload, $extensions_valides)) {
        $verifAvatar['i']++;
        $verifAvatar['avatar_erreur3'] = "Extension de l'avatar incorrecte";
    }
    if (empty($verifAvatar['avatar_erreur3'])) {
        $image_sizes = getimagesize($_FILES['avatar']['tmp_name']);
        if ($image_sizes[0] > $maxwidth or $image_sizes[1] > $maxheight) {
            $verifAvatar['i']++;
            $verifAvatar['avatar_erreur2'] = "Image trop large ou trop longue :
            (<strong>" . $image_sizes[0] . "x" . $image_sizes[1] . "</strong> contre
            <strong>" . $maxwidth . "x" . $maxheight . "</strong>)";
        }
    }
}
    return $verifAvatar;
}