<?php
/*
 *  Description: Display simple single digits of the current weather.
 *  Date:        02/06/2009
 *  
 *  Author:      Paul Archer <ptarcher@gmail.com>
 *
 * Copyright (C) 2009  Paul Archer
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.

 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once('core/ModuleAPI.php');

class ModuleSessionsAPI extends CoreModuleAPI {
	static private $instance = null;
	/**
	 * Returns the singleton ModuleSessionsAPI
	 *
	 * @return ModuleSessionsAPI
	 */
	static public function getInstance()
	{
		if (self::$instance == null)
		{			
			$c = __CLASS__;
			self::$instance = new $c();
		}
		return self::$instance;
	}
	
    function getSessions() {
        $sql = 'SELECT
                    userid,
                    session_date,
                    type_short,
                    description,
                    duration,
                    distance,
                    avg_heartrate,
                    avg_speed,
                    comment
                FROM 
                    t_exercise_totals
                WHERE 
                    userid = :userid
                ORDER BY
                    session_date DESC
                LIMIT 
                    100';
        $stmt = $this->dbQueries->dbh->prepare($sql);

        $stmt->bindParam(':userid', $_SESSION['userid'], PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function updateSession($session_date, $type_short, $description,
                           $duration,     $distance,   $avg_heartrate,
                           $avg_speed,    $comment) {
        $sql = 'UPDATE t_exercise_totals
                SET
                    type_short    = :type_short,
                    description   = :description,
                    duration      = :duration,
                    distance      = :distance,
                    avg_heartrate = :avg_heartrate,
                    avg_speed     = :avg_speed,
                    comment       = :comment
                WHERE 
                    session_date = :session_date AND
                    userid       = :userid';
        $stmt = $this->dbQueries->dbh->prepare($sql);

        // TODO: Add the types
        $stmt->bindParam(':session_date',  $session_date);
        $stmt->bindParam(':type_short',    $type_short,         PDO::PARAM_STR);
        $stmt->bindParam(':description',   $description,        PDO::PARAM_STR);
        $stmt->bindParam(':duration',      $duration);
        $stmt->bindParam(':distance',      $distance);
        $stmt->bindParam(':avg_heartrate', $avg_heartrate);
        $stmt->bindParam(':avg_speed',     $avg_speed);
        $stmt->bindParam(':comment',       $comment,            PDO::PARAM_STR);
        $stmt->bindParam(':userid',        $_SESSION['userid'], PDO::PARAM_STR);

        $stmt->execute() or die(print_r($this->dbQueries->dbh->errorInfo(), true));
    }


    function createSession($session_date, $type_short, 
                           $description,  $duration, 
                           $distance,     $avg_heartrate, 
                           $avg_speed,    $comment) {
        $sql = 'INSERT INTO t_exercise_totals
                   (session_date,
                    type_short,
                    description,
                    duration,
                    distance,
                    avg_heartrate,
                    avg_speed,
                    comment,
                    userid)
                VALUES 
                   (:session_date,
                    :type_short,
                    :description,
                    :duration,
                    :distance,
                    :avg_heartrate,
                    :avg_speed,
                    :comment,
                    :userid)';
        $stmt = $this->dbQueries->dbh->prepare($sql);

        // TODO: Add the types
        $stmt->bindParam(':session_date',  $session_date);
        $stmt->bindParam(':type_short',    $type_short,         PDO::PARAM_STR);
        $stmt->bindParam(':description',   $description,        PDO::PARAM_STR);
        $stmt->bindParam(':duration',      $duration);
        $stmt->bindParam(':distance',      $distance);
        $stmt->bindParam(':avg_heartrate', $avg_heartrate);
        $stmt->bindParam(':avg_speed',     $avg_speed);
        $stmt->bindParam(':comment',       $comment,            PDO::PARAM_STR);
        $stmt->bindParam(':userid',        $_SESSION['userid'], PDO::PARAM_STR);

        $stmt->execute();
    }

    function createSessionFull($session_date,  $type_short, 
                               $description,   $duration, 
                               $distance,      $calories,
                               $avg_heartrate, $max_heartrate,
                               $avg_speed,     $max_speed,
                               $total_ascent,  $total_descent,
                               $comment) {
        $sql = 'INSERT INTO t_exercise_totals
                   (session_date,
                    type_short,
                    description,
                    duration,
                    distance,
                    calories,
                    avg_heartrate,
                    max_heartrate,
                    avg_speed,
                    max_speed,
                    total_ascent,
                    total_descent,
                    comment,
                    userid)
                VALUES 
                   (:session_date,
                    :type_short,
                    :description,
                    :duration,
                    :distance,
                    :calories,
                    :avg_heartrate,
                    :max_heartrate,
                    :avg_speed,
                    :max_speed,
                    :total_ascent,
                    :total_descent,
                    :comment,
                    :userid)';
        $stmt = $this->dbQueries->dbh->prepare($sql);

        // TODO: Add the types
        $stmt->bindParam(':session_date',  $session_date);
        $stmt->bindParam(':type_short',    $type_short,         PDO::PARAM_STR);
        $stmt->bindParam(':description',   $description,        PDO::PARAM_STR);
        $stmt->bindParam(':duration',      $duration);
        $stmt->bindParam(':distance',      $distance);
        $stmt->bindParam(':calories',      $calories);
        $stmt->bindParam(':avg_heartrate', $avg_heartrate);
        $stmt->bindParam(':max_heartrate', $max_heartrate);
        $stmt->bindParam(':avg_speed',     $avg_speed);
        $stmt->bindParam(':max_speed',     $max_speed);
        $stmt->bindParam(':total_ascent',  $total_ascent);
        $stmt->bindParam(':total_descent', $total_descent);
        $stmt->bindParam(':comment',       $comment,            PDO::PARAM_STR);
        $stmt->bindParam(':userid',        $_SESSION['userid'], PDO::PARAM_STR);

        $stmt->execute();
    }

    function insertLap($session_date,  $lap_num,
                       $start_time, 
                       $start_pos_lat, $start_pos_long,
                       $duration,      $calories,
                       $avg_heartrate, $max_heartrate,
                       $avg_speed,     $max_speed,
                       $total_ascent,  $total_descent,
                       $distance) {
        $sql = 'INSERT INTO t_exercise_laps
                   (session_date,
                    lap_num,
                    start_time,
                    start_pos_lat,
                    start_pos_long,
                    duration,
                    calories,
                    distance,
                    avg_heartrate,
                    max_heartrate,
                    avg_speed,
                    max_speed,
                    total_ascent,
                    total_descent,
                    userid)
                VALUES 
                   (:session_date,
                    :lap_num,
                    :start_time,
                    :start_pos_lat,
                    :start_pos_long,
                    :duration,
                    :calories,
                    :distance,
                    :avg_heartrate,
                    :max_heartrate,
                    :avg_speed,
                    :max_speed,
                    :total_ascent,
                    :total_descent,
                    :userid)';
        $stmt = $this->dbQueries->dbh->prepare($sql);

        // TODO: Add the types
        $stmt->bindParam(':session_date',  $session_date);
        $stmt->bindParam(':lap_num',       $lap_num);
        $stmt->bindParam(':start_time',    $start_time);
        $stmt->bindParam(':start_pos_lat', $start_pos_lat);
        $stmt->bindParam(':start_pos_long',$start_pos_long);
        $stmt->bindParam(':duration',      $duration);
        $stmt->bindParam(':calories',      $calories);
        $stmt->bindParam(':distance',      $distance);
        $stmt->bindParam(':avg_heartrate', $avg_heartrate);
        $stmt->bindParam(':max_heartrate', $max_heartrate);
        $stmt->bindParam(':avg_speed',     $avg_speed);
        $stmt->bindParam(':max_speed',     $avg_speed);
        $stmt->bindParam(':total_ascent',  $total_ascent);
        $stmt->bindParam(':total_descent', $total_descent);
        $stmt->bindParam(':userid',        $_SESSION['userid'], PDO::PARAM_STR);

        echo "\n";
        echo $session_date;
        echo "\n";
        echo $lap_num;
        echo "\n";
        echo $start_time;
        echo "\n";
        echo $start_pos_lat;
        echo "\n";
        echo $start_pos_long;
        echo "\n";
        echo $duration;
        echo "\n";
        echo $calories;
        echo "\n";
        echo $distance;
        echo "\n";
        echo $avg_heartrate;
        echo "\n";
        echo $max_heartrate;
        echo "\n";
        echo $avg_speed;
        echo "\n";
        echo $avg_speed;
        echo "\n";
        echo $total_ascent;
        echo "\n";
        echo $total_descent;
        echo "\n";
        echo $_SESSION['userid'];
        echo "\n";
        echo "\n";

        //print_r($stmt);

        $stmt->execute() or die(print_r($this->dbQueries->dbh->errorInfo(), true));
    }


    function deleteSession($session_date) {
        /* Start the changes */
        $this->dbQueries->dbh->beginTransaction();

        /* Remove all the data points */
        $sql = 'DELETE FROM t_exercise_data
                WHERE
                    session_date = :session_date AND
                    userid       = :userid;';
        $stmt = $this->dbQueries->dbh->prepare($sql);
        $stmt->bindParam(':session_date',  $session_date);
        $stmt->bindParam(':userid',        $_SESSION['userid'], PDO::PARAM_STR);
        $stmt->execute() or die(print_r($this->dbQueries->dbh->errorInfo(), true));
        
        $sql = 'DELETE FROM t_exercise_laps
                WHERE
                    session_date = :session_date AND
                    userid       = :userid;';
        $stmt = $this->dbQueries->dbh->prepare($sql);
        $stmt->bindParam(':session_date',  $session_date);
        $stmt->bindParam(':userid',        $_SESSION['userid'], PDO::PARAM_STR);
        $stmt->execute() or die("Unable to execute $sql");


        /* Remove the session totals */
        $sql = 'DELETE FROM t_exercise_totals
                WHERE
                    session_date = :session_date AND
                    userid       = :userid;';
        $stmt = $this->dbQueries->dbh->prepare($sql);
        $stmt->bindParam(':session_date',  $session_date);
        $stmt->bindParam(':userid',        $_SESSION['userid'], PDO::PARAM_STR);
        $stmt->execute() or die("Unable to execute $sql");

        /* Finalise */
        $this->dbQueries->dbh->commit();
    }

    function insertSessionData($session_date, $time, $distance, 
                               $heartrate,    $speed, 
                               $latitude,     $longitude,
                               $altitude,     $cadence,
                               $temperature,  $power) {
        $sql = 'INSERT INTO t_exercise_data
                   (session_date,
                    time,
                    distance,
                    heartrate,
                    speed,
                    latitude,
                    longitude,
                    altitude,
                    cadence,
                    temperature,
                    power,
                    userid)
                VALUES 
                   (:session_date,
                    :time,
                    :distance,
                    :heartrate,
                    :speed,
                    :latitude,
                    :longitude,
                    :altitude,
                    :cadence,
                    :temperature,
                    :power,
                    :userid)';
        $stmt = $this->dbQueries->dbh->prepare($sql);

        // TODO: Add the types
        $stmt->bindParam(':session_date',  $session_date);
        $stmt->bindParam(':time',          $time);
        $stmt->bindParam(':distance',      $distance);
        $stmt->bindParam(':heartrate',     $heartrate);
        $stmt->bindParam(':speed',         $speed);
        $stmt->bindParam(':latitude',      $latitude);
        $stmt->bindParam(':longitude',     $longitude);
        $stmt->bindParam(':altitude',      $altitude);
        $stmt->bindParam(':cadence',       $cadence);
        $stmt->bindParam(':temperature',   $temperature);
        $stmt->bindParam(':power',         $power);
        $stmt->bindParam(':userid',        $_SESSION['userid'], PDO::PARAM_STR);

        $stmt->execute() or die(print_r($this->dbQueries->dbh->errorInfo(), true));
    }

    function getTrainingTypes() {
        $sql = 'SELECT 
                    type_short,
                    type
                FROM 
                    t_training_types
                ORDER BY
                    type_short';
        $stmt = $this->dbQueries->dbh->prepare($sql);
        $stmt->execute() or die(print_r($this->dbQueries->dbh->errorInfo(), true));

        $types = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /* Convert into a nice display table */
        $exercise_types = array();
        foreach ($types as $type) {
            $description = $type['type_short'] . ' - ' . $type['type'];
            $exercise_types[$type['type_short']] = $description;
        }

        return $exercise_types;
    }

    function getSessionTypes() {
        $sql = 'SELECT 
                    total_type,
                FROM 
                    t_exercise_total_types
                ORDER BY
                    total_type';
        $stmt = $this->dbQueries->dbh->prepare($sql);
        $stmt->execute() or die(print_r($this->dbQueries->dbh->errorInfo(), true));

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
