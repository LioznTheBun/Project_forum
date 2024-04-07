<?php

function verifEmail($email, $dtb){
$verif =[
    'email_erreur1' => NULL,
    'email_erreur2' => NULL,
    'i' => NULL
];
if(!empty($email)){
    //Il faut que l'adresse email n'ait jamais été utilisée
    $query=$dtb->prepare(<<<SQL
        SELECT COUNT(*) 
        AS nbr 
        FROM users 
        WHERE email =:mail
        SQL);
    $query->bindValue(':mail',$email,PDO::PARAM_STR);
    $query->execute();
    $mail_free=($query->fetchColumn()==0)?1:0;
    $query->CloseCursor();
    if(!$mail_free)
    {
        $verif['email_erreur1'] = "Votre adresse email est déjà utilisé par un membre";
        $verif['i']++;
    }

    //On vérifie la forme maintenant
    if (!preg_match("#^[a-z0-9A-Z._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $email) || empty($email))
    {
        $verif['email_erreur2'] = "Votre nouvelle adresse E-Mail n'a pas un format valide";
        $verif['i']++;
    }
}
return $verif ;
}

function changeEmail($email, $membre, $dtb){
    if(!empty($email)){
        $query = $dtb->prepare(<<<SQL
            UPDATE users
            SET email = ?  
            WHERE idUser=?
            SQL);
        $query->execute(array($email, $membre));
        $query->CloseCursor();
    }
}

?>
