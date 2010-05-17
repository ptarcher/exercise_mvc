<?php

class GPXTrack extends GPXBaseClass {
	public $name = NULL;
	public $cmt = NULL;
	public $desc = NULL;
	public $src = NULL;
	public $links = NULL;
	public $number = NULL;
	public $type = NULL;
	public $segments = NULL;
	
	public $objectDBName = "go_gpx_track";
	public $objectType = "TRACK";
	public $dbID = "UNDEFINED_TRACK_ID";
	public $created = NULL;

	public function GPXTrack() {
		$this->debug("GPXTrack");
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
			$this->cmt = $gpxDocument->value;
			$this->readToNextOpen($gpxDocument);
		}
		if ($gpxDocument->name == "desc") {
			$gpxDocument->read();
			$this->desc = $gpxDocument->value;
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
		
		if ($gpxDocument->name == "trkseg") {
			$trksegCount = 0;
			do {
				$this->segments[$trksegCount] = new GPXTrackSegment();
				$this->segments[$trksegCount]->XMLin($gpxDocument);
				$trksegCount++;
			} while ($gpxDocument->name == "trkseg");
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
		$this->created = $row["created"];

		$sql = "SELECT * FROM go_gpx_link WHERE id IN (SELECT `link_id` FROM go_gpx_track_links WHERE track_id = " . $this->dbID . ");";
		if ($result = mysql_query($sql)) {
			$linkCount = 0;
			while ($linkRow = mysql_fetch_assoc($result)) {
				$this->links[$linkCount] = new GPXLink();
				$this->links[$linkCount]->mySQLin($linkRow);
				$linkCount++;
			}
		}
		
		$sql = "SELECT `id`,`track_id` FROM go_gpx_track_segment WHERE track_id = " . $this->dbID . ";";
		if ($result = mysql_query($sql)) {
			$segmentCount = 0;
			while ($segmentRow = mysql_fetch_assoc($result)) {
				$this->segments[$segmentCount] = new GPXTrackSegment();
				$this->segments[$segmentCount]->mySQLin($segmentRow);
				$segmentCount++;
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
		$sql = "INSERT INTO go_gpx_tracks (`gpx_id`, `track_id`) VALUES (" . $parentID . ", " . $this->dbID . ");";
		mysql_query($sql, $dbConnection);

		// Iterate through links
		if ($this->links != null) {
			foreach ($this->links as $link) { 
				$linkID = $link->mySQLout($dbConnection, $this->objectType, $this->dbID);
			}
		}

		// Iterate through track segments
		if ($this->segments != null) {
			foreach ($this->segments as $segment) { 
				$segmentID = $segment->mySQLout($dbConnection, $this->dbID);
			}
		}
		
		$this->debug($this->objectType . " ID:" . $this->dbID);
		return $this->dbID;
	}
	
	// Bounds calculation methods //////////////////////////////////////////////////////////////////
	public function maxLat() {
		$maxLat = NULL;
		foreach ($this->segments as $segment) {
			foreach ($segment->trkpts as $trkpoint) {
				if ($maxLat == NULL || $trkpoint->latitude > $maxLat) { $maxLat = $trkpoint->latitude; } 
			}
		}
		return $maxLat;
	}
	public function maxLon() {
		$maxLon = NULL;
		foreach ($this->segments as $segment) {
			foreach ($segment->trkpts as $trkpoint) {
				if ($maxLon == NULL || $trkpoint->longitude > $maxLon) { $maxLon = $trkpoint->longitude; } 
			}
		}
		return $maxLon;
	}
	public function minLat() {
		$minLat = NULL;
		foreach ($this->segments as $segment) {
			foreach ($segment->trkpts as $trkpoint) {
				if ($minLat == NULL || $trkpoint->latitude < $minLat) { $minLat = $trkpoint->latitude; } 
			}
		}
		return $minLat;
	}
	public function minLon() {
		$minLon = NULL;
		foreach ($this->segments as $segment) {
			foreach ($segment->trkpts as $trkpoint) {
				if ($minLon == NULL || $trkpoint->longitude < $minLon) { $minLon = $trkpoint->longitude; } 
			}
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