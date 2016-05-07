<?php
 
/**
 *A class file to handle all database interactions
 *Created and debugged by Samuel Cheung
 */
class database_handler {
	//Connection pointer
	var $con;


    // On creation, connect to database
    function __construct() {
        // connecting to database
        $this->connect();
    }
 
    // destructor
    function __destruct() {
        // closing db connection
        $this->close();
    }
 
    //Function to connect with database
    function connect() {
        // import database connection variables
        require_once __DIR__ . '/db_config.php';
        // Connecting to mysql database
		$this->con = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysql_error());
 
        // returing connection cursor
        //return $con;
    }
	//Working on generic version
	/*
	public function INSERT($tableName, $data){
		//Make sure data is valid
		if($data != NULL && count($data)>0){
			//"INSERT INTO USERS(id, username, password, email, type, gpa) VALUES (NULL, ?, ?, ?, ?, ?) "
			//"INSERT INTO USERS(id, username, password, email, type, gpa) VALUES(NULL, '$username', '$password','$email', '$type', '$gpa')
			$cols = '';
			$vals = '';
			$prepared = '';
			foreach($data as $key => $val){
				$cols .= $key.', ';
				$vals .= "'".$val."', ";
				$prepared = '?, ';
			}
			$prepared = "INSERT INTO '".$tableName."' (".$cols.") VALUES (".$prepared.")";
			echo $prepared;
		}
	}
	*/
 
    //Function to close db connection
    function close() {
        mysqli_close($this->con);
    }
 
}
 
?>