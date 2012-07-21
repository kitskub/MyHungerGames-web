<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>MyHungerGames</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/DT_bootstrap.css">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">

	<script src="js/jquery-1.7.2.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="DataTables/media/js/jquery.dataTables.min.js"></script>
	<script src="js/DT_bootstrap.js"></script>
  </head>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">MyHungerGames</a>
          <div class="nav-collapse">
            <ul class="nav">
              <li><a href="index.html"><i class="icon-home icon-white"></i>Home</a></li>
              <li><a href="tutorials.html">Tutorials</a></li>
              <li class="active"><a href="webstats.php">WebStats</a></li>
              <li><a href="index.html#contact">Contact</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

	<div class="hero-unit">
      <h1>WebStats!</h1>
      <p>Do you want to know where you stand among all the players of MyHungerGames? Well, you're in the right spot!</p>
    </div>
	  
    <div class="container">
	  <table class="table table-striped " id="playersTable">
  	    <thead>
          <tr>
            <th>Rank</th>
            <th>Name</th>
            <th>Last Login</th>
            <th>Total Games</th>
			<th>Total Time</th>
            <th>Wins</th>
            <th>Kills</th>
            <th>Deaths</th>
			<th>Kills/Deaths</th>
			<th>Percent Win</th>
          </tr>
        </thead>
        <tbody>
          <?php
            require("dbproxy.php");
			$mysql = newConnection();
			setupDB($mysql);
			if (!$mysql->ping()) {
			  die();
			}
			if (isset($_GET['page'])) {
			  $page = $_GET['page'];
			}
			else {
		      $page = 0;
			}
            $count = 1;
			$query = "SELECT *, wins/totalGames AS percent FROM players ORDER BY percent DESC, wins DESC, totalGames DESC, playerName ASC;";
			$result = $mysql->query($query);
            while ($row = mysqli_fetch_array($result)) {
              echo "<tr>\n";
              echo "<td>" . $count . "</td>\n";
              echo "<td>" . $row['playerName'] . "</td>\n";
              echo "<td>" . $row['lastLogin'] . "</td>\n";
              echo "<td>" . $row['totalGames'] . "</td>\n";
              echo "<td>" . $row['totalTime'] . "</td>\n";
              echo "<td>" . $row['wins'] . "</td>\n";
              echo "<td>" . $row['kills'] . "</td>\n";
              echo "<td>" . $row['deaths'] . "</td>\n";
			  if ($row['deaths'] == 0) {
			    echo "<td>N/A</td>\n";
			  }
			  else {
			    echo "<td>" . $row['kills']/$row['deaths'] . "</td>\n";
			  }
              echo "<td>" . round($row['percent'] * 100, 2) . "</td>\n";
              echo "</tr>\n";
              $count = $count + 1;
            }
          ?>
        </tbody>
      </table>
      <hr>

      <footer>
        <p>&copy; MyHungerGames</p>
      </footer>

    </div> <!-- /container -->

  </body>
</html>
