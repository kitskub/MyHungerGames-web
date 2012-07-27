<?php
require('config.php');
$mysql = newConnection();
setupDB($mysql);
if (isset($_POST['requestType'])) {
   $requestType = $_POST['requestType'];
   if ($requestType == 'updatePlayers') {
	   updatePlayers();
   }
   else if ($requestType == 'updateGames') {
	   updateGames();
   }
   if ($mysql->errno) {
	error_log("Create failed with error code " . $mysql->connect_errno . ": " . $mysql->connect_error . "\n");
	die("Create failed with error code " . $mysql->connect_errno . ": " . $mysql->connect_error . "\n");
   }
   if ($requestType == 'requestPlayer') {
	   $playerName = $_POST['playerName'];
	   $query = "SELECT * FROM players WHERE playerName=" . $playerName . ";";
	   $result = $mysql->query($query);
	   while ($row = mysqli_fetch_array($result)) {
		   echo "{" . $row['lastLogin'] . "," . $row['totalGames'] . "," . $row['totalTime'] . "," . $row['wins'] . "," . $row['kills'] . "," . $row['deaths'] . PHP_EOL;
	   }
	   if ($mysql->errno) {
		error_log("Create failed with error code " . $mysql->connect_errno . ": " . $mysql->connect_error . "\n");		   exit();
		die("Create failed with error code " . $mysql->connect_errno . ": " . $mysql->connect_error . "\n");		   exit();
           }
	   $query = "SELECT * FROM games WHERE players LIKE {" . $playerName . "};";
	   $result = $mysql->query($query);
	   while ($row = mysqli_fetch_array($result)) {
		   echo "{" . $row['startTime'] . "," . $row['totalDuration'] . "," . $row['winner'] . "," . $row['wins'] . "," . $row['totalPlayers'] . "," . $row['players'] . "," . $row['sponsors'] . PHP_EOL;
	   }
	   if ($mysql->errno) {
		error_log("Create failed with error code " . $mysql->connect_errno . ": " . $mysql->connect_error . "\n");		   exit();
		die("Create failed with error code " . $mysql->connect_errno . ": " . $mysql->connect_error . "\n");		   exit();
           }
   }
}


function newConnection() {
   $mysql = new mysqli();
   $mysql->connect($GLOBALS['url'], $GLOBALS['dbUser'], $GLOBALS['dbPass'], $GLOBALS['dbName']);
   return $mysql;
}

function updatePlayers() {
	$playerName = $_POST['playerName'];
	$login = $_POST['login'];
	$totalTime = $_POST['totalTime'];
	$wins = $_POST['wins'];
	$deaths = $_POST['deaths'];
	$kills = $_POST['kills'];
	$GLOBALS['mysql']->query('INSERT INTO players
		(playerName, lastLogin, totalGames, totalTime, wins, kills, deaths) VALUES 
		(\'' . $playerName . '\', \'' . $login . '\', 1, \'' . $totalTime . '\', \'' . $wins . '\', \'' . $kills . '\', \'' . $deaths . '\')
		ON DUPLICATE KEY UPDATE 
		lastLogin =  ' . $login . ' AND 
		totalGames = totalGames + 1 AND
		totalTime = ADDTIME(total, ' . $totalTime . ' AND
		wins = wins + ' . $wins . ' AND
		kills = kills + ' . $kills . ' AND
		deaths = deaths + ' . $deaths
		);
}

function updateGames() {
	$startTime = $_POST['startTime'];
	$totalPlayers = $_POST['totalPlayers'];
	$winner = $_POST['winner'];
	$players = $_POST['players'];
	$totalDuration = $_POST['totalDuration'];
	$sponsors = $_POST['sponsors'];
	$GLOBALS['mysql']->query('INSERT INTO games
		(startTime, totalDuration, winner, totalPlayers, players, sponsors) VALUES 
		(\'' . $startTime . '\', \'' . $totalDuration . '\', 1, \'' . $winner . '\', \'' . $totalPlayers . '\', \'' . $players . '\', \'' . $sponsors . '\')
		');
}

function setupDB($mysql) {
	$sql = 'CREATE TABLE IF NOT EXISTS players (
		playerName varchar(16) NOT NULL,
		PRIMARY KEY(playerName),
		lastLogin DATE,
		totalGames SMALLINT,
		totalTime TIME,
		wins SMALLINT,
		kills SMALLINT,
		deaths SMALLINT
		);';
	$mysql->query($sql);
	if ($mysql->errno) {
		error_log("Create failed with error code " . $mysql->connect_errno . ": " . $mysql->connect_error . "\n");
		die("Create failed with error code " . $mysql->connect_errno . ": " . $mysql->connect_error . "\n");
	}
	$sql = 'CREATE TABLE IF NOT EXISTS games (
		startTime DATETIME NOT NULL,
		totalDuration TIME,
		winner VARCHAR(16),
		totalPlayers SMALLINT,
		players BLOB,
		sponsors BLOB
		);';
	$mysql->query($sql);
	if ($mysql->errno) {
		error_log("Create failed with error code " . $mysql->connect_errno . ": " . $mysql->connect_error . "\n");
		die("Create failed with error code " . $mysql->connect_errno . ": " . $mysql->connect_error . "\n");
	}
}
?>