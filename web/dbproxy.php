<?php
require('config.php');
$mysql = newConnection();
setupDB($mysql);
if (isset($_POST['requestType'])) {
   $requestType = $_POST['requestType'];
   if ($requestType == 'update') {
       update();
   }
}

function newConnection() {
   $mysql = new mysqli();
   $mysql->connect($GLOBALS['url'], $GLOBALS['dbUser'], $GLOBALS['dbPass'], $GLOBALS['dbName']);
   return $mysql;
}

function update() {
	$playerName = $_POST['playerName'];
	$login = $_POST['login'];
	$wins = $_POST['wins'];
	$deaths = $_POST['deaths'];
	$kills = $_POST['kills'];
	$GLOBALS['mysql']->query('INSERT INTO players
		(name, lastLogin, totalGames, wins, kills, deaths) 
		VALUES 
		(' + $playerName + ', ' + $login + ', 1, ' + $wins + ', ' + $kills + ', ' + $deaths + ')
		ON DUPLICATE KEY UPDATE 
		lastLogin =  ' + $login + ' AND 
		totalGames = totalGames + 1 AND 
		wins = wins + ' + $wins + ' AND
		kills = kills + ' + $kills + ' AND
		deaths = deaths + ' + $deaths
		);
}
function setupDB($mysql) {
	$sql = 'CREATE TABLE IF NOT EXISTS players (
		playerName varchar(16) NOT NULL,
		PRIMARY KEY(playerName),
		lastLogin DATE,
		totalGames SMALLINT,
		wins SMALLINT,
		kills SMALLINT,
		deaths SMALLINT
		);';
	$mysql->query($sql);
	if ($mysql->errno) {
		printf("Create failed with error code %s: %s\n", $mysql->connect_errno, $mysql->connect_error);
		exit();
	}
}
?>