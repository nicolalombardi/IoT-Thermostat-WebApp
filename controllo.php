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
      <script defer src="js/moment.js"></script>
      <script defer src="js/script.js"></script>
      <script>
         window.onload = function() {
             onControlloLoad();
         };
      </script>
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
      <meta charset="utf-8">
      <title>Controllo</title>
   </head>
   <body>
      <nav class="deep-orange" role="navigation">
         <div class="nav-wrapper container">
            <a id="logo-container" href="index.html" class="brand-logo">Termostato</a>
            <ul class="right hide-on-med-and-down">
               <li><a href="index.php">Stato</a></li>
               <li class="active"><a href="controllo.php">Controllo</a></li>
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
               <?php
                  if(isset($_SESSION['logged'])){
                      echo '<li><a onclick="logout()"class="waves-effect waves-light btn">Logout</a></li>';
                  }else{
                      echo '<li><a class="waves-effect waves-light btn" href="#modal1">Login</a></li>';
                  }
                  ?>
               <li>
                  <div class="divider"></div>
               </li>
               <li><a href="index.php">Stato</a></li>
               <li class="active"><a href="controllo.php">Controllo</a></li>
               <li><a href="grafico.php">Grafico</a></li>
               <li><a href="elenco.php">Elenco</a></li>
            </ul>
            <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
         </div>
      </nav>
      <div class="container page-content">
         <h2 class="center-align">Controllo</h2>
         <ul class="collapsible" data-collapsible="accordion">
            <li>
               <div class="collapsible-header active"><i class="material-icons icon">settings</i>Temperatura</div>
               <div class="collapsible-body">
                  <div class="row">
                     <div class="col s4 center-align">
                        <button class="control-button waves-effect waves-light" onclick="changeTemp('-')">-</button>
                     </div>
                     <div class="col s4">
                        <div id="temperature" class="center-align">20.0</div>
                     </div>
                     <div class="col s4 center-align">
                        <button class="control-button waves-effect waves-light" onclick="changeTemp('+')">+</button>
                     </div>
                  </div>
                  <div class="center-align">
                     <?php
                        if(isset($_SESSION['logged'])){
                            echo '<a class="waves-effect waves-light btn" onclick="setTemperature()">Imposta</a>';
                        }else{
                            echo '<span class="tooltipped" data-delay="50" data-position="bottom" data-tooltip="Login necessario!"> <a class="waves-effect waves-light btn disabled" onclick="setTemperature()">Imposta</a></span>';
                        }
                        ?>
                  </div>
               </div>
            </li>
            <li>
               <div class="collapsible-header"><i class="material-icons icon">settings</i>Toggle</div>
               <div class="collapsible-body center-align">
                  <div class="switch">
                     <label>
                     Off
                     <?php
                        if(isset($_SESSION['logged'])){
                            echo '<input type="checkbox" id="thermostat_state" onclick="setToggle()"><span class="lever"></span>';
                        }else{
                            echo '<input disabled type="checkbox" id="thermostat_state" onclick="setToggle()"><span class="tooltipped" data-delay="50" data-position="bottom" data-tooltip="Login necessario!"><span class="lever"></span> </span> ';
                        }
                        ?>
                     On
                     </label>
                  </div>
               </div>
            </li>
            <li>
               <div class="collapsible-header"><i class="material-icons icon">settings</i>Timer</div>
               <div class="collapsible-body">
                  <div class="row">
                     <div class="col s4 center-align">
                        <button class="control-button waves-effect waves-light" onclick="changeTimer('-')">-</button>
                     </div>
                     <div class="col s4">
                        <div id="timer" class="center-align">0h 0m</div>
                     </div>
                     <div class="col s4 center-align">
                        <button class="control-button waves-effect waves-light" onclick="changeTimer('+')">+</button>
                     </div>
                  </div>
                  <div class="center-align">
                     <?php
                        if(isset($_SESSION['logged'])){
                            echo '<a class="waves-effect waves-light btn" onclick="setTimer()">Imposta</a>';
                        }else{
                            echo '<span class="tooltipped" data-delay="50" data-position="bottom" data-tooltip="Login necessario!"><a class="waves-effect waves-light btn disabled" onclick="setTimer()">Imposta</a></span>';
                        }
                        ?>
                  </div>
               </div>
            </li>
         </ul>
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