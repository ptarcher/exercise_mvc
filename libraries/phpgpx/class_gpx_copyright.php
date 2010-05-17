<?php

class GPXCopyright extends GPXBaseClass {

	public $year;
	public $license;
	public $author;
	
	private $objectDBName = "go_gpx_copyright";
	private $objectType = "COPYRIGHT";
	private $dbID = "UNDEFINED_COPYRIGHT_ID";

	public function GPXCopyright() {
		$this->debug("GPXCopyright");
	}

	public function XMLin($gpxDocument) {
		$this->author = $gpxDocument->getAttribute("author");
		$this->readToNextOpen($gpxDocument);
		if ($gpxDocument->name == "year") {
			$gpxDocument->read();
			$this->year = $gpxDocument->value;
			$this->readToNextOpen($gpxDocument);
		}
		$this->readToNextOpen($gpxDocument);
		if ($gpxDocument->name == "license") {
			$gpxDocument->read();
			$this->license = $gpxDocument->value;
		}
	}

	public function mySQLin($row) {
		//TODO
	}

	public function mySQLout($dbConnection) {
		$sqlInsertItems = NULL;
		if ($this->year != NULL) { $sqlInsertItems["year"] = $this->year; }
		if ($this->license != NULL) { $sqlInsertItems["license"] = $this->license; }
		if ($this->author != NULL) { $sqlInsertItems["author"] = $this->author; }

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