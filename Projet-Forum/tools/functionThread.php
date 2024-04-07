<?php

function getLastThreadsLimit($dtb){
	$fetchtopics = $dtb->prepare(<<<SQL
		SELECT * 
		FROM Thread 
		ORDER BY date_creation 
		DESC
		LIMIT 3
		SQL);
    $fetchtopics->execute();
    $topics = $fetchtopics->fetchAll();
	return $topics;
}

function getAllThreads($dtb){
	$fetchtopics = $dtb->prepare(<<<SQL
		SELECT * 
		FROM Thread 
		ORDER BY date_creation 
		DESC
		SQL);
    $fetchtopics->execute();
    $topics = $fetchtopics->fetchAll();
	return $topics;
}

function getThreadByUser($membre, $dtb){
	$fetchtopics = $dtb->prepare(<<<SQL
		SELECT * 
		FROM Thread 
		WHERE auteur_id = ? 
		ORDER BY date_creation 
		DESC
		SQL);
    $fetchtopics->execute(array($membre));
    $topics = $fetchtopics->fetchAll();
	return $topics;
}