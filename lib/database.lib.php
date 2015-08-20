<?php 

class MySQLConnection extends mysqli{
	
	public function __construct($host, $user, $pass, $db, $port){
		parent::__construct($host, $user, $pass, $db, $port);
		
		if (mysqli_connect_error()) {
			$_SESSION['connect_errno'] = mysqli_connect_errno();
			$_SESSION['connect_error'] = mysqli_connect_error();
			header("Location: ".$_SESSION['__config__']['root_site']."/shared/connection_error.php");
	    }
	}
	
	/*
	 * generic query with generic error handling
	 */
	public function execute($query){
		$bool = parent::query($query);
		if(!$bool){
			$errors = array();
			$errors['data'] = date("d/m/Y");
			$errors['ora'] = date("H:i:s");
			$errors['ip_address'] = $_SERVER['REMOTE_ADDR'];
			$errors['referer'] = $_SERVER['HTTP_REFERER'];
			$errors['script'] = $_SERVER['SCRIPT_NAME'];
			$errors['query'] = $query;
			$errors['errno'] = $this->errno;
			$errors['error'] = $this->error;
			$_SESSION['__mysql_error__'] = $errors;
			header("Location: ".$_SESSION['__config__']['root_site']."/shared/mysql_error.php");
		}
		return $bool;
	}

	/*
	 * query that return a resultset (select)
	 */
	public function executeQuery($query) {
		$bool = parent::query($query);
		if(!$bool)
			throw new MySQLException($this->error, 1, $query);
		return $bool;
	}
	
	/*
	 * query that does not return a resultset (insert, update, delete, ...)
	 */
	public function executeUpdate($query) {
		if(!parent::query($query))
			throw new MySQLException($this->error, 2, $query);
		$index = strpos(strtolower($query), "insert");
		if ($index !== false && $index == 0)
			return $this->insert_id;
		else
			return $this->affected_rows;
	}
	
	/*
	 * for queries that return a single value, like sum() or count()
	 */
	public function executeCount($query){
		if (!($result = parent::query($query))){
			throw new MySQLException($this->error, 1, $query);
		}
		if ($result->num_rows > 0){
			$record = $result->fetch_assoc();
			return array_pop($record);
		}
		else{
			return null;
		}
	}

	/*
	 * prepared statement
	 */
	public function prepare($query){
		if (!($result = parent::prepare($query))){
			throw new MySQLException($this->error, 1, $query);
		}
		return $result;
	}
}
