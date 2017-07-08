<?php
    session_start();
    require_once('DB.php');
    require_once('c.php');

    //Istanzia l'oggetto per eseguire le query sul database
    $db = new DB($host, $db, $user, $pass);
    header('Access-Control-Allow-Origin: *');
    //Se la richiesta è GET
    if ($_SERVER['REQUEST_METHOD'] == "GET"){

        if($_GET[url] == 'measurements'){
            //Indica che il contenuto della risposta sarà di tipo JSON
            header('Content-Type: application/json');

            //Se entrambi i limiti sono stati impostati
            if(isset($_GET[start]) && isset($_GET[end])){
                $query = "SELECT * FROM measurements WHERE timestamp >= :startDate AND timestamp <= :endDate ORDER BY timestamp";
                echo json_encode($db->query($query, array('startDate' => $_GET[start], 'endDate' => $_GET[end])));
            }
            //Se entrambi i limiti NON sono stati impostati
            else if(!isset($_GET[start]) && !isset($_GET[end])){
                $query = "SELECT * FROM measurements ORDER BY timestamp";
                echo json_encode($db->query($query));
            }
            //Se è stato impostato solo l'inizio
            else if(isset($_GET[start])){
                $query = "SELECT * FROM measurements WHERE timestamp >= :startDate ORDER BY timestamp";
                echo json_encode($db->query($query, array('startDate' => $_GET[start])));
            }
            //se è stata impostata solo la fine
            else if(isset($_GET[end])){
                $query = "SELECT * FROM measurements WHERE timestamp <= :endDate ORDER BY timestamp";
                echo json_encode($db->query($query, array('endDate' => $_GET[end])));
            }

            http_response_code(200);
        }
        if($_GET[url] == 'live'){
            //Indica che il contenuto della risposta sarà di tipo JSON
            header('Content-Type: application/json');

            $query = "SELECT timestamp, temperature, isOn FROM measurements WHERE ID = (SELECT MAX(ID) FROM measurements)";
            echo json_encode($db->query($query));
        }
        if($_GET[url] == 'controls'){
            header('Content-Type: application/json');
            $query = "SELECT * FROM controls WHERE ID = (SELECT MAX(ID) FROM controls)";
            echo json_encode($db -> query($query, null));
            http_response_code(200);

            $db->query("DELETE FROM controls");
        }

    }
    //Se la richiesta è POST
    else if ($_SERVER['REQUEST_METHOD'] == "POST"){
        if($_GET[url] == 'measurements'){
            //Legge il contenuto del body della richiesta
            $postBody = file_get_contents('php://input');
            //Decodifica il JSON trasformandolo in un array
            $postBody = json_decode($postBody);

            $timestamp = $postBody->timestamp;
            $temperature = $postBody->temperature;
            $isOn = $postBody->isOn;

            //Esegue la query sul database utilizzando i dati presenti nel body
            $db->query("INSERT INTO measurements(`timestamp`, `temperature`, `isOn`) VALUES(:cioa, :temperature, :isOn)", array('cioa' => $timestamp, 'temperature' => $temperature, 'isOn' => $isOn));

            header('Content-Type: application/json');
            echo json_encode(array("error" => "none"));
            http_response_code(200);
        }
        else if($_GET[url] == 'controls'){
            if(isset($_SESSION['logged'])){
                //Legge il contenuto del body della richiesta
                $postBody = file_get_contents('php://input');
                //Decodifica il JSON trasformandolo in un array
                $postBody = json_decode($postBody);

                $type = $postBody->type;
                $value = $postBody->value;

                $db->query("INSERT INTO controls(`type`, `value`) VALUES(:type, :value)", array("type" => $type, "value" => $value));

                //Risponde al client
                header('Content-Type: application/json');
                echo json_encode(array("error" => "none"));
                http_response_code(200);
            }else{
                header('Content-Type: application/json');
                echo json_encode(array("error" => "auth"));
                http_response_code(200);
            }
            
        }
    }
    //Se la tipologia di richiesta non è supportata ritorna un codice di errore opportuno
    else{
        header('Content-Type: application/json');
        echo $_SERVER['REQUEST_METHOD'];
        http_response_code(405);
    }
 ?>
