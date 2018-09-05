<?php

class Db_Connection extends PDO
{
    public $DBCON;
	
	public function __construct()
	{
		require '_config.php';
		
		try {
			$this -> DBCON = new PDO("mysql:host=".$db['sa_db']['hostname'].";dbname=".$db['sa_db']['database'].";charset=".$db['sa_db']['charset'], $db['sa_db']['username'],$db['sa_db']['password']);
		}

		catch(PDOException $Exception ) {
			return false;			
		}
		
		// Set errormode to exceptions
		$this -> DBCON -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
 
		// Create new database in memory
		$memory_db = new PDO('sqlite::memory:');
    
		// Set errormode to exceptions
		$memory_db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
}

?>