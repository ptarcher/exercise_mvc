<?php

class GPXRoute extends GPXBaseClass {
	public $name = NULL;
	public $cmt = NULL;
	public $desc = NULL;
	public $src = NULL;
	public $links = NULL;
	public $number = NULL;
	public $type = NULL;
	public $routepoints = NULL;
	
	public $objectDBName = "go_gpx_route";
	public $objectType = "ROUTE";
	public $dbID = "UNDEFINED_ROUTE_ID";

	public function GPXRoute() {
		$this->debug("GPXRoute");
	}	

	public function XMLin($gpxDocument) {
		$this->readToNextOpen($gpxDocument);
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
		if ($gpxDocument->name == "number") {
			$gpxDocument->read();
			$this->number = $gpxDocument->value;
			$this->readToNextOpen($gpxDocument);
		}
		if ($gpxDocument->name == "type") {
			$gpxDocument->read();
			$this->type = $gpxDocument->value;
			$this->readToNextOpen($gpxDocument);
		}
		$this->skipExtensions($gpxDocument);

		if ($gpxDocument->name == "rtept") {
			$rteptCount = 0;
			do {
				$this->routepoints[$rteptCount] = new GPXWaypoint();
				$this->routepoints[$rteptCount]->XMLin($gpxDocument);
				$rteptCount++;
			} while ($gpxDocument->name == "rtept");
		}
	}
	
	public function mySQLin($row) {	
		$this->name = $row["name"];
		$this->cmt = $row["cmt"];
		$this->desc = $row["desc"];
		$this->src = $row["src"];
		$this->number = $row["number"];
		$this->type = $row["type"];
		$this->dbID = $row["id"];
		
		$sql = "SELECT * FROM go_gpx_waypoint WHERE id IN (SELECT `waypoint_id` FROM go_gpx_route_waypoints WHERE route_id = " . $this->dbID . ");";
		if ($result = mysql_query($sql)) {
			$rteCount = 0;
			while ($rteRow = mysql_fetch_assoc($result)) {
				$this->routepoints[$rteCount] = new GPXWaypoint();
				$this->routepoints[$rteCount]->mySQLin($rteRow);
				$rteCount++;
			}
		}
		
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
	
	public function mySQLout($dbConnection, $parentID) {
		$sqlInsertItems = NULL;
		if ($this->name != NULL) { $sqlInsertItems["name"] = $this->name; }
		if ($this->cmt != NULL) { $sqlInsertItems["cmt"] = $this->cmt; }
		if ($this->desc != NULL) { $sqlInsertItems["desc"] = $this->desc; }
		if ($this->src != NULL) { $sqlInsertItems["src"] = $this->src; }
		if ($this->number != NULL) { $sqlInsertItems["number"] = $this->number; }
		if ($this->type != NULL) { $sqlInsertItems["type"] = $this->type; }
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
		$sql = "INSERT INTO go_gpx_routes (gpx_id, route_id) VALUES (" . $parentID . ", " . $this->dbID . ");)";
		mysql_query($sql, $dbConnection);

		if ($this->links != null) {
			foreach ($this->links as $link) { 
				$linkID = $link->mySQLout($dbConnection, $this->objectType, $this->dbID);
			}
		}

		// Commit route points
		if ($this->routepoints != NULL) {
			foreach ($this->routepoints as $routepoint) { 
				$routepointID = $routepoint->mySQLout($dbConnection, $this->objectType, $this->dbID);
			}
		}
		
		$this->debug($this->objectType . " ID:" . $this->dbID);
		return $this->dbID;
	}
	
	// Bounds calculation methods //////////////////////////////////////////////
	public function maxLat() {
		$maxLat = NULL;
		foreach ($this->routepoints as $routepoint) {
			if ($maxLat == NULL || $routepoint->latitude > $maxLat) { $maxLat = $routepoint->latitude; } 
		}
		return $maxLat;
	}
	public function maxLon() {
		$maxLon = NULL;
		foreach ($this->routepoints as $routepoint) {
			if ($maxLon == NULL || $routepoint->longitude > $maxLon) { $maxLon = $routepoint->longitude; } 
		}
		return $maxLon;
	}
	public function minLat() {
		$minLat = NULL;
		foreach ($this->routepoints as $routepoint) {
			if ($minLat == NULL || $routepoint->latitude < $minLat) { $minLat = $routepoint->latitude; } 
		}
		return $minLat;
	}
	public function minLon() {
		$minLon = NULL;
		foreach ($this->routepoints as $routepoint) {
			if ($minLon == NULL || $routepoint->longitude < $minLon) { $minLon = $routepoint->longitude; } 
		}
		return $minLon;
	}
	public function midLat() {
		return $this->minLat() + ($this->maxLat() - $this->minLat()) / 2;
	}
	public function midLon() {
		return $this->minLon() + ($this->maxLon() - $this->minLon()) / 2;
	}
}

?>