<?php
require('config.php');
$mysqli = newConnection();
setupDB($mysqli);
if (isset($_REQUEST['requestType'])) {
   $requestType = $_REQUEST['requestType'];
   if ($requestType == 'updatePlayers') {
	   updatePlayers();
   }
   if ($mysqli->errno) {
	error_log("updatePlayers failed with error code " . $mysqli->connect_errno . ": " . $mysqli->connect_error . "\n");
	die("updatePlayers failed with error code " . $mysqli->connect_errno . ": " . $mysqli->connect_error . "\n");
   }
   if ($requestType == 'updateGames') {
	   updateGames();
   }
   if ($mysqli->errno) {
	error_log("updateGames failed with error code " . $mysqli->connect_errno . ": " . $mysqli->connect_error . "\n");
	die("updateGames failed with error code " . $mysqli->connect_errno . ": " . $mysqli->connect_error . "\n");
   }
   if ($requestType == 'requestPlayer') {
	   echo "<?xml version=\"1.0\"?>" . PHP_EOL;
	   $playerName = $_REQUEST['playerName'];
	   $query = "SET @row:=0; 
		SELECT *, rank FROM
		(SELECT *, wins/totalGames AS percent, @row:=@row+1 'rank' FROM players ORDER BY percent DESC, wins DESC, totalGames DESC, playerName ASC) 
		as row_to_return
		WHERE row_to_return.playerName='" . $playerName . "';";
	   $mysqli->multi_query($query);
	   do {
		$res = $mysqli->store_result();
		if ($res) {
			$result=$res;
		}
	   } while ($mysqli->more_results() && $mysqli->next_result());
	   
	   if ($mysqli->errno) {
		error_log("requestPlayer failed with error code " . $mysqli->connect_errno . ": " . $mysqli->connect_error . "\n");
		die("requestPlayer failed with error code " . $mysqli->connect_errno . ": " . $mysqli->connect_error . "\n");
           }
	   echo "<global>" . PHP_EOL;
	   while ($row = $result->fetch_array()) {
		surroundAndPrint($row['rank'], "rank");
		surroundAndPrint($row['lastLogin'], "lastLogin");
		surroundAndPrint($row['totalGames'], "totalGames");
		surroundAndPrint($row['totalTime'], "totalTime");
		surroundAndPrint($row['wins'], "wins");
		surroundAndPrint($row['kills'], "kills");
		surroundAndPrint($row['deaths'], "deaths");
	   }
	   echo "</global>" . PHP_EOL;
	   $query = "SELECT * FROM games WHERE players LIKE '%{" . $playerName . "}%';";
	   $result = $mysqli->query($query);
	   if ($mysqli->errno) {
		error_log("requestPlayer failed with error code " . $mysqli->connect_errno . ": " . $mysqli->connect_error . "\n");
		die("requestPlayer failed with error code " . $mysqli->connect_errno . ": " . $mysqli->connect_error . "\n");
           }
	   while ($row = mysqli_fetch_array($result)) {
		echo "<game>" . PHP_EOL;
		surroundAndPrint($row['startTime'], "startTime");
		surroundAndPrint($row['totalDuration'], "totalDuration");
		surroundAndPrint($row['winner'], "winner");
		surroundAndPrint($row['totalPlayers'], "totalPlayers");
		$matches = getPlayers($row['players']);
		foreach ($matches[0] as $i => $value) {
			surroundAndPrint($value, "player");
		}
		$matches = getPlayers($row['sponsors']);
		foreach ($matches[0] as $i => $value) {
			surroundAndPrint($value, "sponsor");
		}
		echo "</game>" . PHP_EOL;
	   }
   }
}

function getPlayers($all = "") {
	$matches = array();
	$regex = "{[a-zA-Z0-9_]+}";
	preg_match_all($regex, $all, $matches);
	return $matches;
}

function surroundAndPrint($text, $node) {
	echo "<" . $node . ">";
	echo  $text;
	echo "</" . $node . ">";
	echo PHP_EOL;
}

function newConnection() {
   $mysql = mysqli_init();
   $mysql->real_connect($GLOBALS['url'], $GLOBALS['dbUser'], $GLOBALS['dbPass'], $GLOBALS['dbName']);
   return $mysql;
}

function updatePlayers() {
	$playerName = $_REQUEST['playerName'];
	$totalTime = $_REQUEST['totalTime'];
	$wins = $_REQUEST['wins'];
	$deaths = $_REQUEST['deaths'];
	$kills = $_REQUEST['kills'];
	$query = "INSERT INTO players
		(playerName, lastLogin, totalGames, totalTime, wins, kills, deaths) VALUES 
		('" . $playerName . "', now(), 1, '" . $totalTime . "', '" . $wins . "', '" . $kills . "', '" . $deaths . "')
		ON DUPLICATE KEY UPDATE 
		lastLogin =  now(),
		totalGames = totalGames + 1,
		totalTime = ADDTIME(totalTime, '" . $totalTime . "'),
		wins = wins + " . $wins . ",
		kills = kills + " . $kills . ",
		deaths = deaths + " . $deaths .
		";";
	$GLOBALS['mysqli']->query($query);
}

function updateGames() {
	$startTime = $_REQUEST['startTime'];
	$totalPlayers = $_REQUEST['totalPlayers'];
	$winner = $_REQUEST['winner'];
	$players = $_REQUEST['players'];
	$totalDuration = $_REQUEST['totalDuration'];
	$sponsors = $_REQUEST['sponsors'];
	$query = "INSERT INTO games
		(startTime, totalDuration, winner, totalPlayers, players, sponsors) VALUES 
		('" . $startTime . "', '" . $totalDuration . "', '" . $winner . "', '" . $totalPlayers . "', '" . $players . "', '" . $sponsors . "')
		;";
	$GLOBALS['mysqli']->query($query);
}

function setupDB($mysql) {
	$sql = "CREATE TABLE IF NOT EXISTS players (
		playerName varchar(16) NOT NULL,
		PRIMARY KEY(playerName),
		lastLogin DATE,
		totalGames SMALLINT,
		totalTime TIME,
		wins SMALLINT,
		kills SMALLINT,
		deaths SMALLINT
		);";
	$mysql->query($sql);
	if ($mysql->errno) {
		error_log("setupDB failed for players with error code " . $mysql->connect_errno . ": " . $mysql->connect_error . "\n");
		die("setupDB failed for players with error code " . $mysql->connect_errno . ": " . $mysql->connect_error . "\n");
	}
	$sql = "CREATE TABLE IF NOT EXISTS games (
		startTime TIMESTAMP NOT NULL,
		totalDuration TIME,
		winner VARCHAR(16),
		totalPlayers SMALLINT,
		players TEXT,
		sponsors TEXT
		);";
	$mysql->query($sql);
	if ($mysql->errno) {
		error_log("setupDB failed for games with error code " . $mysql->connect_errno . ": " . $mysql->connect_error . "\n");
		die("setuDB failed for games with error code " . $mysql->connect_errno . ": " . $mysql->connect_error . "\n");
	}
}
?>