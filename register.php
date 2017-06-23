<?PHP
require_once('DB.php');

//Istanzia l'oggetto per eseguire le query sul database
$db = new DB('localhost', 'my_tesinaiot', 'root', '');
$password = password_hash("pass123", PASSWORD_DEFAULT);
echo $password;
$db->query("INSERT INTO login values('1', :pass)", ["pass" => $password]);

?>
