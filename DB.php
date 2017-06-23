<?php
class DB {
    private $pdo;
    //Contrustor function
    public function __construct($host, $dbname, $username, $password) {
        //Create a new PDO object
        $pdo = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo = $pdo;
    }
    //Runs a query and returns the data inside of an array object
    public function query($query, $params = array()) {
        $statement = $this->pdo->prepare($query);
        $statement->execute($params);

        if (explode(' ', $query)[0] == 'SELECT') {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

    }
}
?>
