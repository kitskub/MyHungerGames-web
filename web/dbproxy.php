<?php
require 'config.php';
$con = mysqli_connect($url,$dbUser,$dbPass);
mysqli_select_db($dbName, $con);
$authId = $_POST["authID"];
$requestType = $_POST["requestType"];
if ($requestType == "update") {
	$playerName = $_POST["playerName"];
	$login = $_POST["login"];
	$wins = $_POST["wins"];
	$deaths = $_POST["deaths"];
	$kills = $_POST["kills"];
	setupDB();
	mysqli_query("INSERT INTO players
		(name, lastLogin, totalGames, wins, kills, deaths) 
		VALUES 
		('$playerName', '$login', '1', '$wins', '$kills', '$deaths')
		ON DUPLICATE KEY UPDATE 
		lastLogin =  '$login' AND 
		totalGames = totalGames + 1 AND 
		wins = wins + $wins AND
		kills = kills + $kills AND
		deaths = deaths + $deaths
		");
	
}

function setupDB() {
	$sql = "CREATE TABLE IF NOT EXIST players (
		playerName varchar(16) NOT NULL,
		PRIMARY KEY(playerName),
		lastLogin DATE(),
		totalGames SMALLINT(),
		wins SMALLINT(),
		kills SMALLINT(),
		deaths SMALLINT()
		)";
	mysqli_query($sql, $GLOBALS["con"]);
}

mysqli_close($con);
?>