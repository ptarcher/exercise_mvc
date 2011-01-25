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

class ModuleSessionGraphsAPI extends CoreModuleAPI {
	static private $instance = null;
	/**
	 * Returns the singleton ModuleSessionGraphsAPI
	 *
	 * @return ModuleSessionGraphsAPI
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
	
    function getSessionDataField($session_date, $field, $min_time = NULL, $max_time = NULL) 
    //function getSessionDataField($session_date, $field) 
    {
        $valid_fields = array('distance','speed','heartrate',
                              'altitude','power','temperature',
                              'cadence', 'gradient');

        // Make sure field is a valid field
        if (!in_array($field, $valid_fields)) {
            return;
        }

    	// Get time in seconds since the start of the session
        $sql = 'SELECT 
                    (extract(EPOCH from "time") * 1000) AS "time",
                    '.$field.'
                FROM 
                    t_exercise_data
                WHERE 
                    userid       = :userid  AND
                    session_date = :session_date ';
        if (!is_null($min_time)) {
            $sql .= 'AND "time" >= :min_time ';
        }
        if (!is_null($max_time)) {
            $sql .= 'AND "time" <= :max_time ';
        }
        $sql .= 'ORDER BY
                    "time"     DESC';
        $stmt = $this->dbQueries->dbh->prepare($sql);

        $stmt->bindParam(':userid',       $_SESSION['userid'], PDO::PARAM_STR);
        $stmt->bindParam(':session_date', $session_date,       PDO::PARAM_STR);

        if (!is_null($min_time)) {
            $stmt->bindParam(':min_time', $min_time, PDO::PARAM_STR);
        }
        if (!is_null($max_time)) {
            $stmt->bindParam(':max_time', $max_time, PDO::PARAM_STR);
        }

        $stmt->execute();

        /* TODO: Do this in a more generic way */
        $data = $stmt->fetchAll(PDO::FETCH_NUM);
        $rows = array();
        foreach ($data as $row) {
            for ($i = 0; $i < count($row); $i++) {
                $myrow[$i] = doubleval($row[$i]);
            }
            $rows[] = $myrow;
        }
        return $rows;
    }

    function getGPXData($session_date, $min_time = null, $max_time = null) 
    {
    	// Get time in seconds since the start of the session
        $sql = 'SELECT 
                    latitude as lat,
                    longitude as lon
                FROM 
                    t_exercise_data
                WHERE 
                    userid       = :userid  AND
                    session_date = :session_date ';
        if (!is_null($min_time)) {
            $sql .= 'AND "time" >= :min_time ';
        }
        if (!is_null($max_time)) {
            $sql .= 'AND "time" <= :max_time ';
        }
        $sql .= 'ORDER BY
                    "time"     DESC';
        $stmt = $this->dbQueries->dbh->prepare($sql);

        $stmt->bindParam(':userid',       $_SESSION['userid'], PDO::PARAM_STR);
        $stmt->bindParam(':session_date', $session_date,       PDO::PARAM_STR);

        if (!is_null($min_time)) {
            $stmt->bindParam(':min_time', $min_time, PDO::PARAM_STR);
        }
        if (!is_null($max_time)) {
            $stmt->bindParam(':max_time', $max_time, PDO::PARAM_STR);
        }

        $stmt->execute();

        return $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getSession($session_date) 
    {
        $sql = 'SELECT
                    userid,
                    session_date,
                    type_short,
                    description,
                    duration,
                    distance,
                    avg_heartrate,
                    max_heartrate,
                    avg_heartrate_percent,
                    max_heartrate_percent,
                    avg_speed,
                    max_speed,
                    calories,
                    total_ascent,
                    total_descent,
                    comment
                FROM 
                    v_exercise_totals
                WHERE 
                    userid =       :userid       AND
                    session_date = :session_date
                ORDER BY
                    session_date DESC';
        $stmt = $this->dbQueries->dbh->prepare($sql);

        $stmt->bindParam(':userid',       $_SESSION['userid'], PDO::PARAM_STR);
        $stmt->bindParam(':session_date', $session_date);

        $stmt->execute();

        $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (is_array($sessions)) {
            return $sessions[0];
        } else {
            return array();
        }
    }

    function getLaps($session_date) 
    {
        $sql = 'SELECT
                    userid,
                    session_date,
                    lap_num,
                    start_time,
                    start_time + duration AS end_time,
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
                    total_descent
                FROM 
                    t_exercise_laps
                WHERE 
                    userid       = :userid       AND
                    session_date = :session_date
                ORDER BY
                    lap_num ASC';
        $stmt = $this->dbQueries->dbh->prepare($sql);

        $stmt->bindParam(':session_date', $session_date,       PDO::PARAM_STR);
        $stmt->bindParam(':userid',       $_SESSION['userid'], PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getZones($session_date, $min_time = NULL, $max_time = NULL) 
    {
        $sql = 'SELECT
                    userid,
                    zone,
                    SUM(length) as length
                FROM 
                    v_exercise_data
                WHERE 
                    userid       = :userid       AND
                    session_date = :session_date ';
        if (!is_null($min_time)) {
            $sql .= 'AND "time" >= :min_time ';
        }
        if (!is_null($max_time)) {
            $sql .= 'AND "time" <= :max_time ';
        }
        $sql .= 'GROUP BY
                    zone, userid
                ORDER BY
                    zone ASC';
        $stmt = $this->dbQueries->dbh->prepare($sql);

        $stmt->bindParam(':session_date', $session_date,       PDO::PARAM_STR);
        $stmt->bindParam(':userid',       $_SESSION['userid'], PDO::PARAM_STR);

        if (!is_null($min_time)) {
            $stmt->bindParam(':min_time', $min_time, PDO::PARAM_STR);
        }
        if (!is_null($max_time)) {
            $stmt->bindParam(':max_time', $max_time, PDO::PARAM_STR);
        }

        $stmt->execute() or die(print_r($this->dbQueries->dbh->errorInfo(), true));

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getClimbs($session_date) 
    {
        $sql = 'SELECT
                    userid,
                    session_date,
                    climb_num,
                    cat AS category,
                    top - bottom AS duration,
                    total_distance,
                    total_climbed,
                    gradient_avg,
                    gradient_max,
                    min_altitude,
                    max_altitude
                FROM 
                    v_climbs_data
                WHERE 
                    userid       = :userid       AND
                    session_date = :session_date
                ORDER BY
                    climb_num';
        $stmt = $this->dbQueries->dbh->prepare($sql);

        $stmt->bindParam(':session_date', $session_date,       PDO::PARAM_STR);
        $stmt->bindParam(':userid',       $_SESSION['userid'], PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getClimb($session_date, $climb_num) 
    {
        $sql = 'SELECT
                    userid,
                    session_date,
                    climb_num,
                    cat AS category,
                    top,
                    bottom,
                    total_distance,
                    total_climbed,
                    gradient_avg,
                    gradient_max,
                    min_altitude,
                    max_altitude
                FROM 
                    v_climbs_data
                WHERE 
                    userid       = :userid       AND
                    session_date = :session_date AND
                    climb_num    = :climb_num';
        $stmt = $this->dbQueries->dbh->prepare($sql);

        $stmt->bindParam(':session_date', $session_date,       PDO::PARAM_STR);
        $stmt->bindParam(':userid',       $_SESSION['userid'], PDO::PARAM_STR);
        $stmt->bindParam(':climb_num',    $climb_num,          PDO::PARAM_INT);

        $stmt->execute();

        $climbs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (is_array($climbs)) {
            return $climbs[0];
        } else {
            return array();
        }
    }

    function getGPXClimbData($session_date, $climb_num) 
    {
        $sql = 'SELECT 
                    latitude  AS lat,
                    longitude AS lon
                FROM 
                    t_exercise_data exercise,
                    (SELECT 
                         *
                     FROM
                         t_climbs_data
                     WHERE
                         userid       = :userid       AND
                         session_date = :session_date AND
                         climb_num    = :climb_num) climb
                WHERE 
                    exercise.userid        = :userid       AND
                    exercise.session_date  = :session_date AND
                    exercise.time         >= climb.bottom  AND
                    exercise.time         <= climb.top
                ORDER BY
                    "time"     DESC;';
        $stmt = $this->dbQueries->dbh->prepare($sql);

        $stmt->bindParam(':userid',       $_SESSION['userid'], PDO::PARAM_STR);
        $stmt->bindParam(':session_date', $session_date,       PDO::PARAM_STR);
        $stmt->bindParam(':climb_num',    $climb_num,          PDO::PARAM_INT);

        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
}

?>
