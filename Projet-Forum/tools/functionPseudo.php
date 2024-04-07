<?php 

function verifPseudo($pseudo, $dtb){
	$verif=[
		'erreur' =>NULL,
		'i' => 0
	];
	$state = $dtb->prepare(<<<SQL
		SELECT COUNT(*) 
		AS nbr 
		FROM users 
		WHERE username =?
		SQL);
	$state->execute(array($pseudo));
	$pseudo_free = ($state->fetchColumn() == 0) ? 1 : 0;
	$state->CloseCursor();
	if (!$pseudo_free) {
		$verif['erreur'] = "Votre pseudo est déjà utilisé par un membre <br>";
		$verif['i']++;
	}
	return $verif;
}

function changePseudo($pseudo, $id, $dtb){
	if(!empty($pseudo)){
        $query = $dtb->prepare(<<<SQL
            UPDATE users
            SET username = ?  
            WHERE idUser=?
            SQL);
        $query->execute(array($pseudo, $id));
        $query->CloseCursor();
    }

}
