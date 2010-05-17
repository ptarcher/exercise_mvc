<?php

class GPXWaypoint extends GPXBaseClass {
	public $latitude = NULL;
	public $longitude = NULL;	
	public $elevation = NULL;
	public $time = NULL;
	public $magvar = NULL;
	public $geoIDHeight = NULL;
	public $name = NULL;
	public $comment = NULL;
	public $description = NULL;
	public $source = NULL;
	public $links = NULL;
	public $symbol = NULL;
	public $type = NULL;
	public $fix = NULL;
	public $satellites = NULL;
	public $hdop = NULL;
	public $vdop = NULL;
	public $pdop = NULL;
	public $ageofdgpsdata = NULL;
	public $dgpsid = NULL;
	
	public $objectDBName = "go_gpx_waypoint";
	public $objectType = "WAYPOINT";
	public $dbID = "UNDEFINED_WAYPOINT_ID";
	
	public function GPXWaypoint() {
		$this->debug("GPXWaypoint");
	}
	
	public function XMLin($gpxDocument) {
		$this->latitude = $gpxDocument->getAttribute("lat");
		$this->longitude = $gpxDocument->getAttribute("lon");

		$this->readToNextOpen($gpxDocument);
		if ($gpxDocument->name == "ele") {
			$gpxDocument->read();
			$this->elevation = $gpxDocument->value;
			$this->readToNextOpen($gpxDocument);
		}
		if ($gpxDocument->name == "time") {
			$gpxDocument->read();
			$this->time = $gpxDocument->value;
			$this->readToNextOpen($gpxDocument);
		}
		if ($gpxDocument->name == "magvar") {
			$gpxDocument->read();
			$this->magvar = $gpxDocument->value;
			$this->readToNextOpen($gpxDocument);
		}
		if ($gpxDocument->name == "geoidheight") {
			$gpxDocument->read();
			$this->geoIDHeight = $gpxDocument->value;
			$this->readToNextOpen($gpxDocument);
		}
		if ($gpxDocument->name == "name") {
			$gpxDocument->read();
			$this->name = $gpxDocument->value;
			$this->readToNextOpen($gpxDocument);
		}
		if ($gpxDocument->name == "cmt") {
			$gpxDocument->read();
			$this->comment = $gpxDocument->value;
			$this->readToNextOpen($gpxDocument);
		}
		if ($gpxDocument->name == "desc") {
			$gpxDocument->read();
			$this->description = $gpxDocument->value;
			$this->readToNextOpen($gpxDocument);
		}
		if ($gpxDocument->name == "src") {
			$gpxDocument->read();
			$this->source = $gpxDocument->value;
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
		if ($gpxDocument->name == "sym") {
			$gpxDocument->read();
			$this->symbol = $gpxDocument->value;
			$this->readToNextOpen($gpxDocument);
		}
		if ($gpxDocument->name == "type") {
			$gpxDocument->read();
			$this->type = $gpxDocument->value;
			$this->readToNextOpen($gpxDocument);
		}
		if ($gpxDocument->name == "fix") {
			$gpxDocument->read();
			$this->fix = $gpxDocument->value;
			$this->readToNextOpen($gpxDocument);
		}
		if ($gpxDocument->name == "sat") {
			$gpxDocument->read();
			$this->satellites = $gpxDocument->value;
			$this->readToNextOpen($gpxDocument);
		}
		if ($gpxDocument->name == "hdop") {
			$gpxDocument->read();
			$this->hdop = $gpxDocument->value;

			$this->readToNextOpen($gpxDocument);
		}
		if ($gpxDocument->name == "vdop") {
			$gpxDocument->read();
			$this->vdop = $gpxDocument->value;
			$this->readToNextOpen($gpxDocument);
		}
		if ($gpxDocument->name == "pdop") {
			$gpxDocument->read();
			$this->pdop = $gpxDocument->value;
			$this->readToNextOpen($gpxDocument);
		}
		if ($gpxDocument->name == "ageofdgpsdata") {
			$gpxDocument->read();
			$this->ageofdgpsdata = $gpxDocument->value;
			$this->readToNextOpen($gpxDocument);
		}
		if ($gpxDocument->name == "dgpsid") {
			$gpxDocument->read();
			$this->dgpsid = $gpxDocument->value;
			$this->readToNextOpen($gpxDocument);
		}
		$this->skipExtensions($gpxDocument);
	}
	
	public function mySQLin($row) {
		$this->dbID = $row["id"];
		$this->name = $row["name"];
		$this->latitude = $row["latitude"];
		$this->longitude = $row["longitude"];
		$this->elevation = $row["elevation"];
		$this->time = $row["time"];
		$this->magvar = $row["magvar"];
		$this->geoIDHeight = $row["geoidheight"];
		$this->comment = $row["comment"];
		$this->description = $row["description"];
		$this->source = $row["source"];
		$this->symbol = $row["symbol"];
		$this->type = $row["type"];
		$this->fix = $row["fix"];
		$this->satellites = $row["satellites"];
		$this->hdop = $row["hdop"];
		$this->vdop = $row["vdop"];
		$this->pdop = $row["pdop"];
		$this->ageofdgpsdata = $row["ageofdgpsdata"];


		$sql = "SELECT * FROM go_gpx_link WHERE id IN (SELECT `link_id` FROM go_gpx_route_links WHERE route_id = " . $this->dbID . ");";
		if ($result = mysql_query($sql)) {
			$linkCount = 0;
			while ($linkRow = mysql_fetch_assoc($result)) {
				$this->links[$linkCount] = new GPXLink();
				$this->links[$linkCount]->mySQLin($linkRow);
				$linkCount++;
			}
		}
	}
	
	public function mySQLout($dbConnection, $parentType, $parentID) {
		$sqlInsertItems = NULL;
		if ($this->latitude != NULL) { $sqlInsertItems["latitude"] = $this->latitude; }
		if ($this->longitude != NULL) { $sqlInsertItems["longitude"] = $this->longitude; }
		if ($this->elevation != NULL) { $sqlInsertItems["elevation"] = $this->elevation; }
		if ($this->time != NULL) { $sqlInsertItems["time"] = date('Y-m-d H:i:s', strtotime($this->time)); }  // Convert ISO 8601 time for mySQL (bug in mySQL)
		if ($this->magvar != NULL) { $sqlInsertItems["magvar"] = $this->magvar; }
		if ($this->geoIDHeight != NULL) { $sqlInsertItems["geoIDHeight"] = $this->geoIDHeight; }
		if ($this->name != NULL) { $sqlInsertItems["name"] = $this->name; }
		if ($this->comment != NULL) { $sqlInsertItems["comment"] = $this->comment; }
		if ($this->description != NULL) { $sqlInsertItems["description"] = $this->description; }
		if ($this->source != NULL) { $sqlInsertItems["source"] = $this->source; }
		if ($this->symbol != NULL) { $sqlInsertItems["symbol"] = $this->symbol; }
		if ($this->type != NULL) { $sqlInsertItems["type"] = $this->type; }
		if ($this->fix != NULL) { $sqlInsertItems["fix"] = $this->fix; }
		if ($this->satellites != NULL) { $sqlInsertItems["satellites"] = $this->satellites; }
		if ($this->hdop != NULL) { $sqlInsertItems["hdop"] = $this->hdop; }
		if ($this->vdop != NULL) { $sqlInsertItems["vdop"] = $this->vdop; }
		if ($this->pdop != NULL) { $sqlInsertItems["pdop"] = $this->pdop; }
		if ($this->ageofdgpsdata != NULL) { $sqlInsertItems["ageofdgpsdata"] = $this->ageofdgpsdata; }
		if ($this->dgpsid != NULL) { $sqlInsertItems["dgpsid"] = $this->dgpsid; }
		$sqlInsertItems["created"] = date('Y-m-d H:i:s', time());

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
		if ($parentType == "GPX") { $sql = "INSERT INTO go_gpx_waypoints (gpx_id, waypoint_id) VALUES (" . $parentID . ", " . $this->dbID . ");"; }
		elseif ($parentType == "TRACK_SEGMENT") { $sql = "INSERT INTO go_gpx_track_segment_waypoints (track_segment_id, waypoint_id) VALUES (" . $parentID . ", " . $this->dbID . ");"; }
		elseif ($parentType == "ROUTE") { $sql = "INSERT INTO go_gpx_route_waypoints (route_id, waypoint_id) VALUES (" . $parentID . ", " . $this->dbID . ");"; }
		mysql_query($sql, $dbConnection);

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