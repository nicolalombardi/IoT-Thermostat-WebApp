<?php
    session_start();
?>
<html>
<head>
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="css/style.css" />

    <script defer src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script defer type="text/javascript" src="js/materialize.min.js"></script>
    <script defer src="js/jquery.flot.min.js"></script>
    <script defer src="js/jquery.flot.axislabels.js"></script>
    <script defer src="js/moment.js"></script>
    <script defer src="js/script.js"></script>
    <script defer src="js/jquery.flot.downsample.js"></script>
    <script>
        window.onload = function() {
            onGraficoLoad();
        };
    </script>

    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta charset="utf-8">
    <title>Grafico</title>
</head>

<body>
    <nav class="deep-orange" role="navigation">
        <div class="nav-wrapper container"><a id="logo-container" href="index.html" class="brand-logo">Termostato</a>
            <ul class="right hide-on-med-and-down">
                <li><a href="index.php">Stato</a></li>
                <li><a href="controllo.php">Controllo</a></li>
                <li class="active"><a href="grafico.php">Grafico</a></li>
                <li><a href="elenco.php">Elenco</a></li>
                <?php
                        if(isset($_SESSION['logged'])){
                            echo '<li><a onclick="logout()"class="waves-effect waves-light btn">Logout</a></li>';
                        }else{
                            echo '<li><a class="waves-effect waves-light btn" href="#modal1">Login</a></li>';
                        }
                     ?>
            </ul>

            <ul id="nav-mobile" class="side-nav">
                <li>
                    <div class="nav-header deep-orange"></div>
                </li>
                <li><a href="index.php">Stato</a></li>
                <li><a href="controllo.php">Controllo</a></li>
                <li class="active"><a href="grafico.php">Grafico</a></li>
                <li><a href="elenco.php">Elenco</a></li>
                <?php
                        if(isset($_SESSION['logged'])){
                            echo '<li><a onclick="logout()"class="waves-effect waves-light btn">Logout</a></li>';
                        }else{
                            echo '<li><a class="waves-effect waves-light btn" href="#modal1">Login</a></li>';
                        }
                     ?>
            </ul>
            <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
        </div>
    </nav>

    <div class="container page-content">
        <h2 class="center-align">Grafico</h2>

        <ul class="collapsible" data-collapsible="accordion">
            <li>
              <div class="collapsible-header"><i class="material-icons icon">settings</i>Opzioni</div>
              <div class="collapsible-body"><div class="row">
                  <div class="col s12 m4">
                      <label for="from_date">Data di inizio:</label>
                      <input id="from_date" class="datepicker" onchange="updateGraph()" type="date">
                  </div>
                  <div class="col s12 m4">
                      <label for="to_date">Data di fine: </label>
                      <input id="to_date" class="datepicker" onchange="updateGraph()" type="date">
                  </div>
                  <div class="col s12 m4">
                      <label for="points-slider">Numero punti: </label>
                      <p class="range-field">
                          <input type="range" id="points-slider" min="2" max="200" onchange="updateGraph()"/>
                      </p>
                  </div>
              </div></div>
            </li>
        </ul>



        <div id="graph-wrapper">
            <div class="graph-container">
                <div id="graph-lines"></div>
            </div>
        </div>
    </div>
    <div id="modal1" class="modal">
            <div class="login-header">
                <div>
                    <h4 style="line-height: 70px;">Login</h4>
                </div>
            </div>
            <div class="modal-content">


                <div class="input-field">
                    <input type="password" id="password">
                    <label for="password">Codice</label>
                </div>
            </div>
                <div class="center-align" style="margin-bottom: 10px">
                    <a onclick="login()" class="modal-action waves-effect waves-light btn">Login</a>
                </div>

                <div id="login-error" >
            <h7 style="line-height: 40px;">Password errata!</h7>
        </div>
        </div>


</body>
