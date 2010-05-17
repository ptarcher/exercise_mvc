<?php

class GPXLink extends GPXBaseClass {
	public $href;
	public $text;
	public $type;
	
	private $objectType = "GPXLink";
	private $objectDBName = "go_gpx_link";
	private $dbID = "UNDEFINED_LINK_ID";

	public function GPXLink() {
		$this->debug("GPXLink");
	}

	public function XMLin($gpxDocument){
		$this->href = $gpxDocument->getAttribute("href");
		$this->readToNextOpen($gpxDocument);
	
		if ($gpxDocument->name == "text"){
			$gpxDocument->read();
			$this->text = $gpxDocument->value;
			$this->readToNextOpen($gpxDocument);
		}
		if ($gpxDocument->name == "type"){
			$gpxDocument->read();
			$this->type = $gpxDocument->value;
			$this->readToNextOpen($gpxDocument);
		}
	}
	
	public function mySQLin($row) {
		$this->href = $row["href"];
		$this->text = $row["text"];
		$this->type = $row["type"];
	}

	public function mySQLout($dbConnection, $parentType, $parentID) {
		
		$thisDBID = false;
		$sqlInsertItems = NULL;
		if ($this->href != NULL) { $sqlInsertItems["href"] = $this->href; }
		if ($this->text != NULL) { $sqlInsertItems["text"] = $this->text; }
		if ($this->type != NULL) { $sqlInsertItems["type"] = $this->type; }

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
		
		$sql = NULL;
		if ($parentType == "WAYPOINT") { $sql = "INSERT INTO go_gpx_waypoint_links (waypoint_id, link_id) VALUES (" . $parentID . ", " . $this->dbID . ");"; }
		elseif ($parentType == "TRACK") { $sql = "INSERT INTO go_gpx_track_links (track_id, link_id) VALUES (" . $parentID . ", " . $this->dbID . ");"; }
		elseif ($parentType == "ROUTE") { $sql = "INSERT INTO go_gpx_route_links (route_id, link_id) VALUES (" . $parentID . ", " . $this->dbID . ");"; }
		elseif ($parentType == "METADATA") { $sql = "INSERT INTO go_gpx_metadata_links (metadata_id, link_id) VALUES (" . $parentID . ", " . $this->dbID . ");"; }
		elseif ($parentType == "PERSON") { $sql = "INSERT INTO go_gpx_person_links (person_id, link_id) VALUES (" . $parentID . ", " . $this->dbID . ");"; }
		mysql_query($sql, $dbConnection);
		
		$this->debug($this->objectType . " ID:" . $this->dbID);
		return $this->dbID;
	} 
}

?>