<?php
   session_start();
   ?>
<html>
   <head>
      <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
      <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
      <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
      <link rel="manifest" href="/manifest.json">
      <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#ff5722">
      <meta name="theme-color" content="#ff5722">
      <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
      <link type="text/css" rel="stylesheet" href="css/style.css" />
      <script defer src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
      <script defer type="text/javascript" src="js/materialize.min.js"></script>
      <script defer src="js/moment.js"></script>
      <script defer src="js/script.js"></script>
      <script>
         window.onload = function() {
             onStatoLoad();
         };
      </script>
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
      <meta charset="utf-8">
      <title>Stato</title>
   </head>
   <body>
      <nav class="deep-orange" role="navigation">
         <div class="nav-wrapper container">
            <a id="logo-container" href="index.php" class="brand-logo">Termostato</a>
            <ul class="right hide-on-med-and-down">
               <li class="active"><a href="index.php">Stato</a></li>
               <li><a href="controllo.php">Controllo</a></li>
               <li><a href="grafico.php">Grafico</a></li>
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
               <li class="active"><a href="index.php">Stato</a></li>
               <li><a href="controllo.php">Controllo</a></li>
               <li><a href="grafico.php">Grafico</a></li>
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
         <div id="temperature" class="center-align">°C</div>
         <div id="heating_status" class="center-align"><span id="heating_tooltip" class="tooltipped" data-delay="50" data-position="bottom" data-tooltip=""><img id="heating_image" src=""></span></div>
         <div id="last_update" class="center-align">Ultimo aggiornamento: </div>
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
</html>