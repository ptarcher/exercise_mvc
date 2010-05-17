<?php

class GPXPerson extends GPXBaseClass {

	public $name;
	public $email; // GPXAuthor
	public $links; // GPXLinka

	private $objectDBName = "go_gpx_person";
	private $objectType = "PERSON";
	private $dbID = "UNDEFINED_PERSON_ID";

	public function GPXPerson() {
		$this->debug("GPXPerson");
	}

	public function XMLin($gpxDocument) {
		$this->readToNextOpen($gpxDocument);

		if ($gpxDocument->name == "name") {
			$gpxDocument->read();
			$this->name = $gpxDocument->value;
			$this->readToNextOpen($gpxDocument);
		}
		if ($gpxDocument->name == "email") {
			$this->email = new GPXEmail($gpxDocument);
			$this->readToNextOpen($gpxDocument);
		}
		if ($gpxDocument->name == "link") {
			$linkCount = 0;
			do {
				$this->links[$linkCount] = new GPXLink();
				$this->links[$linkCount]->XMLin($gpxDocument);
				$linkCount++;
			} while ($gpxDocument->name == "link");
		}
	}
	
	public function mySQLin($row) {
	}
	
	public function mySQLout($dbConnection) {
	
		$sqlInsertItems = NULL;
		if ($this->name != NULL) { $sqlInsertItems["name"] = $this->name; }
		if ($this->email != NULL) { $sqlInsertItems["email_id"] = $this->email->mySQLin($dbConnection); }

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

		// Iterate through links and insert into database
		if ($this->links != null) {
			foreach ($this->links as $link) { 
				$linkID = $link->mySQLout($dbConnection, $this->objectType, $this->dbID);
			}
		}

		$this->debug($this->objectType . " ID:" . $this->dbID);
		return $this->dbID;
	}
}

?>