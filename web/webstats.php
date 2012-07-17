<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>MyHungerGames</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
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
              <li class="active"><a href="index.html"><i class="icon-home icon-white"></i>Home</a></li>
              <li><a href="tutorials.html">Tutorials</a></li>
              <li><a href="webstats.php">WebStats</a></li>
              <li><a href="index.html#contact">Contact</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">

      <!-- Main hero unit for a primary marketing message or call to action -->
      <div class="hero-unit">
        <h1>Hello, world!</h1>
        <p>This is a template for a simple marketing or informational website. It includes a large callout called the hero unit and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
        <p><a class="btn btn-primary btn-large">Learn more &raquo;</a></p>
      </div>

	  <table class="table table-striped">
  	    <thead>
          <tr>
            <th>Rank</th>
            <th>Name</th>
            <th>Last Login</th>
            <th>Total Games</th>
            <th>Wins</th>
            <th>Kills</th>
            <th>Deaths</th>
          </tr>
        </thead>
        <tbody>
          <?php
            require("dbproxy.php");
            $count = 1;
            while (list($name, $login, $games, $wins, $allKills, $allDeaths) = mysql_fetch_row(mysqli_query("SELCT * FROM players ORDER BY totalGames ASC"))) {
              echo "<tr>\n";
              echo "<td>" + $count + "</td>\n";
              echo "<td> + " + $name + "</td>\n";
              echo "<td> + " + $login + "</td>\n";
              echo "<td> + " + $games + "</td>\n";
              echo "<td> + " + $wins + "</td>\n";
              echo "<td> + " + $allKills + "</td>\n";
              echo "<td> + " + $allDeaths + "</td>\n";
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

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-1.7.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

  </body>
</html>
