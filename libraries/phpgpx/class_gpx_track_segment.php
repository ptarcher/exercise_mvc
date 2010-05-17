<?php

class GPXTrackSegment extends GPXBaseClass {

	public $trkpts = NULL;

	public $objectType = "TRACK_SEGMENT";
	public $dbID = "UNDEFINED_TRACK_SEGMENT_ID";
	public $objectDBName = "go_gpx_track_segment";
	
	public function GPXTrackSegment() {
		$this->debug("GPXTrackSegment");
	}
	
	public function XMLin($gpxDocument) {

		$this->readToNextOpen($gpxDocument);
		if ($gpxDocument->name == "trkpt") {
			$trkPtCount = 0;
			do {
				$this->trkpts[$trkPtCount] = new GPXWaypoint();
				$this->trkpts[$trkPtCount]->XMLin($gpxDocument);
				$trkPtCount++;
			} while ($gpxDocument->name == "trkpt");
		}
		$this->skipExtensions($gpxDocument);		
	}
	
	public function mySQLin($row) {
		$this->dbID = $row["id"];

		$sql = "SELECT w.* FROM go_gpx_waypoint w JOIN go_gpx_track_segment_waypoints tsw ON tsw.waypoint_id = w.id WHERE tsw.track_segment_id = " . $this->dbID . ";";
		if ($result = mysql_query($sql)) {
			$trkptCount = 0;
			while ($trkptRow = mysql_fetch_assoc($result)) {
				$this->trkpts[$trkptCount] = new GPXWaypoint();
				$this->trkpts[$trkptCount]->mySQLin($trkptRow);
				$trkptCount++;
			}
		}
	}
	
	public function mySQLout($dbConnection, $parentID) {
	
		$sqlInsertItems = NULL;

		$sql = "INSERT INTO go_gpx_track_segment (`track_id`) VALUE (" . $parentID . ");";
		mysql_query($sql, $dbConnection);
		
		// Get the inserted item ID
		$sql = "SELECT @@IDENTITY FROM " . $this->objectDBName . ";";
		$this->dbID = mysql_result(mysql_query($sql, $dbConnection), 0, 0);
	
		// Commit track points
		if ($this->trkpts != NULL) {
			foreach ($this->trkpts as $trackpoint) { 
				$trackpoint->mySQLout($dbConnection, $this->objectType, $this->dbID);
			}
		}
		
		$this->debug($this->objectType . " ID:" . $this->dbID);
		return $this->dbID;
	}
}

?>