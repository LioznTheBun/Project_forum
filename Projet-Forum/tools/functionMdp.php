<?php

function verifMdp($pass, $confirm){
    $verifMdp=[
        'erreur' => NULL,
        'i' => 0
    ];
    if ($pass != $confirm && !empty($confirm) && !empty($pass) || !empty($confirm) && empty($pass) || empty($confirm) && !empty($pass)) {
        $verifMdp['erreur'] = "Votre mot de passe et votre confirmation diffèrent ou sont vides";
        $verifMdp['i']++;
    }
    return $verifMdp;
}

function changeMdp($membre, $pass, $confirm, $dtb){
    if (!empty($confirm) && !empty($pass) && $pass == $confirm) {
        $query = $dtb->prepare(<<<SQL
            UPDATE users
            SET  password = SHA2(?, 256)
            WHERE idUser=?
            SQL);
        $query->execute(array($pass, $membre));
        $query->CloseCursor();
    }
}

?>