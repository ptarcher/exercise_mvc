<?php

class GPXEmail extends GPXBaseClass {
	public $email_id;
	public $domain;
	
	private $objectDBName = "go_gpx_email";
	private $objectType = "EMAIL";
	private $dbID = "UNDEFINED_EMAIL_ID";

	public function GPXEmail() {
		$this->debug("GPXEmail");
	}

	public function XMLin($gpxDocument) {
		$this->email_id = $gpxDocument->getAttribute("id");
		$this->domain = $gpxDocument->getAttribute("domain");
	}
	
	public function mySQLin($row) {
		//TODO
	}

	public function mySQLout($dbConnection) {
	
		$sqlInsertItems = NULL;
		if ($this->email_id != NULL) { $sqlInsertItems["email_id"] = $this->email_id; }
		if ($this->domain != NULL) { $sqlInsertItems["domain"] = $this->domain; }

		// Prepare the SQL statement FIELD/VALUES lists
		$sql = "INSERT INTO " . $this->objectDBName . " (";
		for ($i = 0; $i < sizeof($sqlInsertItems); $i++) {
			$sql .= "`" . key($sqlInsertItems) . "`";
			if ($i < sizeof($sqlInsertItems)-1) { $sql .= ", "; }
			next($sqlInsertItems);
		}
		reset($sqlInsertItems);
		$sql .= ") VALUES (";
		for ($i = 0; $i < sizeof($sqlInsertItems); $i++) {
			$sql .= "'" . $sqlInsertItems[key($sqlInsertItems)] . "'";
			if ($i < sizeof($sqlInsertItems)-1) { $sql .= ", "; }
			next($sqlInsertItems);
		}
		$sql .= ");";
		mysql_query($sql, $dbConnection);
		
		// Get the inserted item ID
		$sql = "SELECT @@IDENTITY FROM " . $this->objectDBName . ";";
		$this->dbID = mysql_result(mysql_query($sql, $dbConnection), 0, 0);

		$this->debug($this->objectType . " ID:" . $this->dbID);
		return $this->dbID;
	}
}

?>