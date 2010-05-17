<?php

include "class_gpx_base_class.php";
include "class_gpx_waypoint.php";
include "class_gpx_route.php";
include "class_gpx_track.php";
include "class_gpx_track_segment.php";
include "class_gpx_metadata.php";
include "class_gpx_person.php";
include "class_gpx_email.php";
include "class_gpx_copyright.php";
include "class_gpx_bounds.php";
include "class_gpx_link.php";

class GPX extends GPXBaseClass {
	public $name = NULL;
	public $version = NULL;
	public $creator = NULL;
	public $metadata = NULL;
	public $waypoints = NULL;
	public $routes = NULL;
	public $tracks = NULL;

	public $userID = NULL;
	public $public = true;
	
	public $objectType = "GPX";
	public $dbID = "UNDEFINED_GPX_ID";
	public $objectDBName = "go_gpx";
	
	public function GPX () {
		$this->debug("GPX");
	}

	public function XMLin($file) {
		$gpxDocument = new XMLReader;
		$gpxDocument->open($file); 

		$gpxDocument->read();
		if ($gpxDocument->name != "gpx") {
			echo "The top node of the GPX document must be GPX";
			return false;
		}

		$this->version = $gpxDocument->getAttribute("version");
		$this->creator = $gpxDocument->getAttribute("creator");
		$this->readToNextOpen($gpxDocument);

		if ($gpxDocument->name == "metadata") {
			$this->metadata = new GPXMetadata();
			$this->metadata->XMLin($gpxDocument);
			$this->readToNextOpen($gpxDocument);
		}

		if ($gpxDocument->name == "wpt") {
			$wptCount = 0;
			do {
				$this->waypoints[$wptCount] = new GPXWaypoint();
				$this->waypoints[$wptCount]->XMLin($gpxDocument);
				$wptCount++;
			} while ($gpxDocument->name == "wpt");
		}

		if ($gpxDocument->name == "rte") {
			$rteCount = 0;
			do {
				$this->routes[$rteCount] = new GPXRoute();
				$this->routes[$rteCount]->XMLin($gpxDocument);
				$rteCount++;
			} while ($gpxDocument->name == "rte");
		}

		if ($gpxDocument->name == "trk") {
			$trkCount = 0;
			do {
				$this->tracks[$trkCount] = new GPXTrack();
				$this->tracks[$trkCount]->XMLin($gpxDocument);
				$trkCount++;
			} while ($gpxDocument->name == "trk");
		}
	}

	public function mySQLin($row) {
		$this->name = $row["name"];
		$this->version = $row["version"];
		$this->creator = $row["creator"];
		$this->public = $row["public"];
		$this->dbID = $row["id"];
		
		$sql = "SELECT * FROM go_gpx_metadata WHERE id = " . $row["metadata_id"] . ";";
		if ($result = mysql_query($sql)) {
			$this->metadata = new GPXMetaData();
			$this->metadata->mySQLin(mysql_fetch_assoc($result));
		}

		$sql = "SELECT * FROM go_gpx_waypoint WHERE id IN (SELECT `waypoint_id` FROM go_gpx_waypoints WHERE gpx_id = " . $this->dbID . ");";
		if ($result = mysql_query($sql)) {
			$wptCount = 0;
			while ($wptRow = mysql_fetch_assoc($result)) {
				$this->waypoints[$wptCount] = new GPXWaypoint();
				$this->waypoints[$wptCount]->mySQLin($wptRow);
				$wptCount++;
			}
		}

		$sql = "SELECT * FROM go_gpx_route WHERE id IN (SELECT `route_id` FROM go_gpx_routes WHERE gpx_id = " . $this->dbID . ");";
		if ($result = mysql_query($sql)) {
			$rteCount = 0;
			while ($rteRow = mysql_fetch_assoc($result)) {
				$this->routes[$rteCount] = new GPXRoute();
				$this->routes[$rteCount]->mySQLin($rteRow);
				$rteCount++;
			}
		}

		$sql = "SELECT * FROM go_gpx_track WHERE id IN (SELECT `track_id` FROM go_gpx_tracks WHERE gpx_id = " . $this->dbID . ");";
		if ($result = mysql_query($sql)) {
			$trkCount = 0;
			while ($trkRow = mysql_fetch_assoc($result)) {
				$this->tracks[$trkCount] = new GPXTrack();
				$this->tracks[$trkCount]->mySQLin($trkRow);
				$trkCount++;
			}
		}
	}

	public function mySQLout($dbConnection) {
		$sqlInsertItems = NULL;
		if ($this->name != NULL) { $sqlInsertItems["name"] = $this->name; }
		if ($this->version != NULL) { $sqlInsertItems["version"] = $this->version; }
		if ($this->creator != NULL) { $sqlInsertItems["creator"] = $this->creator; }
		if ($this->public != NULL) { $sqlInsertItems["public"] = $this->public; }
		if ($this->metadata != NULL) { $sqlInsertItems["metadata_id"] = $this->metadata->mySQLout($dbConnection, $this->dbID); }
		if (isset($_SESSION["uid"])) { $sqlInsertItems["user_id"] = $_SESSION["uid"]; }
		$sqlInsertItems["created"] = date('Y-m-d H:i:s', time());

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

		$sql = "SELECT @@IDENTITY FROM " . $this->objectDBName . ";";
		$this->dbID = mysql_result(mysql_query($sql, $dbConnection), 0, 0);

		if ($this->waypoints != NULL) {
			foreach ($this->waypoints as $waypoint) { 
				$waypoint->mySQLout($dbConnection, $this->objectType, $this->dbID);
			}
		}

		if ($this->routes != NULL) {
			foreach ($this->routes as $route) { 
				$route->mySQLout($dbConnection, $this->dbID);
			}
		}

		if ($this->tracks != NULL) {
			foreach ($this->tracks as $track) { 
				$track->mySQLout($dbConnection, $this->dbID);
			}
		}

		$this->debug($this->objectType . " ID:" . $this->dbID);
		return $this->dbID;
	}
}

?>