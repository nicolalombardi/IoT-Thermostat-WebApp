<?PHP
session_start();
require_once('DB.php');
require_once('c.php');

//Istanzia l'oggetto per eseguire le query sul database
$db = new DB($host, $db, $user, $pass);

$result = $db->query("SELECT * FROM login", []);

if(password_verify($_POST["pass"], $result[0]["password"])){
	$_SESSION['logged'] = 1;

	echo json_encode(["error" => "none"]);
}else{
	echo json_encode(["error" => "auth"]);
}

    
?>
