<?php

class GPXBounds extends GPXBaseClass {
	public $min_latitude; // float
	public $min_longitude; // float
	public $max_latitude; // float
	public $max_longitude; // float
	
	private $objectDBName = "go_gpx_bounds";
	private $dbID = "UNDEFINED_BOUNDS_ID";

	public function GPXBounds() {
		$this->debug("GPXBounds");
	}
	
	public function XMLin($gpxDocument){
		$this->min_latitude = $gpxDocument->getAttribute("minlat");
		$this->min_longitude = $gpxDocument->getAttribute("minlon");
		$this->max_latitude = $gpxDocument->getAttribute("maxlat");
		$this->max_longitude = $gpxDocument->getAttribute("maxlon");
	}
	
	public function mySQLin($row) {
		//TODO
	}

	public function mySQLout($dbConnection) {

		$sqlInsertItems = NULL;
		if ($this->min_latitude != NULL) { $sqlInsertItems["min_latitude"] = $this->min_latitude; }
		if ($this->min_longitude != NULL) { $sqlInsertItems["min_longitude"] = $this->min_longitude; }
		if ($this->max_latitude != NULL) { $sqlInsertItems["max_latitude"] = $this->max_latitude; }
		if ($this->max_longitude != NULL) { $sqlInsertItems["max_longitude"] = $this->max_longitude; }
		
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