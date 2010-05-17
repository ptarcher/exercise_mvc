<?php

abstract class GPXBaseClass {

	public $debugMode = false;

	// Function to read to the next opening tag
	public function readToNextOpen($input) {
		do {
			if (!$input->read()) { return false; }	
		} while ($input->nodeType != 1);
		return true;
	}
	
	public function skipExtensions($gpxDocument) {
		// Skip extensions
		while ($gpxDocument->name == "extensions" 
			|| $gpxDocument->name == "gpxx:WaypointExtension"
			|| $gpxDocument->name == "gpxx:DisplayMode"
			|| $gpxDocument->name == "gpxx:RouteExtension"
			|| $gpxDocument->name == "gpxx:TrackExtension"
			|| $gpxDocument->name == "gpxx:IsAutoNamed"
			|| $gpxDocument->name == "gpxx:RoutePointExtension"  
			|| $gpxDocument->name == "gpxx:Subclass"
			|| $gpxDocument->name == "gpxx:DisplayColor"
			|| $gpxDocument->name == "gpxx:Categories" 
			|| $gpxDocument->name == "gpxx:Category") {
			$this->readToNextOpen($gpxDocument);
		}
	}
	
	public function debug($input) {
		if ($this->debugMode) { echo "{ " . $input . " } <br/>"; }
	}
}

?>