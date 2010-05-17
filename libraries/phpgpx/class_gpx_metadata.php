<?php

class GPXMetadata extends GPXBaseClass {
	public $name = NULL;
	public $description = NULL;
	public $author = NULL;
	public $copyright = NULL;
	public $links = NULL;
	public $time = NULL;
	public $keywords = NULL;
	public $bounds = NULL;
	
	private $objectDBName = "go_gpx_metadata";
	private $objectType = "METADATA";
	private $dbID = "UNDEFINED_METADATA_ID";

	public function GPXMetadata() {
		$this->debug("GPXMetaData");
	}
	
	public function XMLin ($gpxDocument) {
		$this->readToNextOpen($gpxDocument);

		if ($gpxDocument->name == "name") {
			$gpxDocument->read();
			$this->name = $gpxDocument->value;
			$this->readToNextOpen($gpxDocument);
		}
		if ($gpxDocument->name == "description") {
			$gpxDocument->read();
			$this->description = $gpxDocument->value;
			$this->readToNextOpen($gpxDocument);
		}
		if ($gpxDocument->name == "author") {
			$this->author = new GPXPerson();
			$this->author->XMLin($gpxDocument);
		}
		if ($gpxDocument->name == "copyright") {
			$this->copyright = new GPXCopyright();
			$this->copyright->XMLin($gpxDocument);
	}
		if ($gpxDocument->name == "link") {
			$linkCount = 0;
			do {
				$this->links[$linkCount] = new GPXLink();
				$this->links[$linkCount]->XMLin($gpxDocument);
				$linkCount++;
			} while ($gpxDocument->name == "link");
			$this->readToNextOpen($gpxDocument);
		}
		if ($gpxDocument->name == "time") {
			$gpxDocument->read();
			$this->time = $gpxDocument->value;
			$this->readToNextOpen($gpxDocument);
		}
		if ($gpxDocument->name == "keywords") {
			$gpxDocument->read();
			$this->keywords = $gpxDocument->value;
			$this->readToNextOpen($gpxDocument);
		}
		if ($gpxDocument->name == "bounds") {
			$this->bounds = new GPXBounds($gpxDocument);
		}
	}
	
	public function mySQLin($row) {
		$this->dbID = $row["id"];
		$this->name = $row["name"];
		$this->description = $row["description"];
		$this->time = $row["time"];
		$this->keywords = $row["keywords"];
	}
	
	public function mySQLout($dbConnection, $parentID) {
		$sqlInsertItems = NULL;
		if ($this->name != NULL) { $sqlInsertItems["name"] = $this->name; }
		if ($this->description != NULL) { $sqlInsertItems["description"] = $this->description; }
		if ($this->author != NULL) { $sqlInsertItems["author_id"] = $this->author->mySQLin($dbConnection); }
		if ($this->copyright != NULL) { $sqlInsertItems["copyright_id"] = $this->copyright->mySQLin($dbConnection); }
		if ($this->time != NULL) { $sqlInsertItems["time"] = $this->time; }
		if ($this->keywords!= NULL) { $sqlInsertItems["keywords"] = $this->keywords; }
		if ($this->bounds != NULL) { $sqlInsertItems["bounds_id"] = $this->bounds->mySQLin($dbConnection); }

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