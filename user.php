<?php
include_once 'Database.php';
class user { 		 	
	private $name;
	private $firstName;
	private $lastName;
	private $fullName;
	function __construct($id) { 		
		$db = new Database();
		$result = $db->prepare("SELECT * FROM users WHERE user_id=:id;");
		$result->bindValue(':id', $id, SQLITE3_INTEGER);
		$row = $result->execute();
		while ($r = $row->fetchArray()) {
			$this->name = $r['username'];
			$this->firstName = $r['FirstName'];
			$this->lastName = $r['LastName'];
			$this->fullName = $r['FirstName']." ".$r['LastName'];
		}
	} 	 	
	function name() {
		return $this->name;
	}
	function firstName() {
		return $this->firstName;
	}
	
	function fullName() {
		return $this->fullName;
	}
}
?>