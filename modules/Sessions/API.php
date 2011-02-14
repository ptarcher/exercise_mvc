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
require_once('core/Db.php');

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
        $db = CoreDb::getInstance();
        $select = $db->select()
                     ->from('t_exercise_totals',
                            array('userid','session_date','type_short',
                                  'description','duration','distance',
                                  'avg_heartrate','avg_speed','comment'))
                     ->where('userid = ?', $_SESSION['userid'])
                     ->order('session_date DESC');
        $stmt = $db->query($select);
        $result = $stmt->fetchAll();

        return $result;
    }

    function updateSession($session_date = "", $type_short = "", $description     = "",
                           $duration     = "", $distance   = "",   $avg_heartrate = "",
                           $avg_speed    = "", $comment    = "") {
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
                       $timer_duration, $total_duration,
                       $calories,
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
                    total_duration,
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
                    :total_duration,
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
        $stmt->bindParam(':duration',      $timer_duration);
        $stmt->bindParam(':total_duration', $total_duration);
        $stmt->bindParam(':calories',      $calories);
        $stmt->bindParam(':distance',      $distance);
        $stmt->bindParam(':avg_heartrate', $avg_heartrate);
        $stmt->bindParam(':max_heartrate', $max_heartrate);
        $stmt->bindParam(':avg_speed',     $avg_speed);
        $stmt->bindParam(':max_speed',     $max_speed);
        $stmt->bindParam(':total_ascent',  $total_ascent);
        $stmt->bindParam(':total_descent', $total_descent);
        $stmt->bindParam(':userid',        $_SESSION['userid'], PDO::PARAM_STR);

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
        
        /* Delete all the laps */
        $sql = 'DELETE FROM t_exercise_laps
                WHERE
                    session_date = :session_date AND
                    userid       = :userid;';
        $stmt = $this->dbQueries->dbh->prepare($sql);
        $stmt->bindParam(':session_date',  $session_date);
        $stmt->bindParam(':userid',        $_SESSION['userid'], PDO::PARAM_STR);
        $stmt->execute() or die("Unable to execute $sql");

        /* Delete all the climbs */
        $sql = 'DELETE FROM t_climbs_data
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

    function insertAllSessionData($session_date, $records) 
    {
        $i = 0;
        $max_inserts = 9999;

        for ($rows = 0; $rows < count($records); $rows += $max_inserts) {
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
                        gradient,
                        userid)
                    VALUES ';

            /* Do a multi insert */
            for ($i = 0; ($i < $max_inserts) && 
                         ($i + $rows < count($records)); $i++) {
                if ($i != 0) {
                    $sql .= ', ';
                }
                $sql .= '(:session_date,
                          :time'        .$i.',
                          :distance'    .$i.',
                          :heartrate'   .$i.',
                          :speed'       .$i.',
                          :latitude'    .$i.',
                          :longitude'   .$i.',
                          :altitude'    .$i.',
                          :cadence'     .$i.',
                          :temperature' .$i.',
                          :power'       .$i.',
                          :gradient'    .$i.',
                          :userid) ';
            }

            $stmt = $this->dbQueries->dbh->prepare($sql);

            /* Add the constant values for all rows */
            $stmt->bindParam(':session_date',  $session_date);
            $stmt->bindParam(':userid',        $_SESSION['userid'], PDO::PARAM_STR);

            /* Now places the values in */
            for ($i = 0; ($i < $max_inserts) && 
                         ($i + $rows < count($records)); $i++) {
                $stmt->bindParam(':time'.$i,        $records[$i+$rows]->interval);
                $stmt->bindParam(':distance'.$i,    $records[$i+$rows]->distance);
                $stmt->bindParam(':heartrate'.$i,   $records[$i+$rows]->heart_rate);
                $stmt->bindParam(':speed'.$i,       $records[$i+$rows]->speed);
                $stmt->bindParam(':latitude'.$i,    $records[$i+$rows]->position_lat);
                $stmt->bindParam(':longitude'.$i,   $records[$i+$rows]->position_long);
                $stmt->bindParam(':altitude'.$i,    $records[$i+$rows]->altitude);
                $stmt->bindParam(':cadence'.$i,     $records[$i+$rows]->cadence);
                $stmt->bindParam(':temperature'.$i, $records[$i+$rows]->temperature);
                $stmt->bindParam(':power'.$i,       $records[$i+$rows]->power);
                $stmt->bindParam(':gradient'.$i,    $records[$i+$rows]->gradient);
            }

            $stmt->execute() or die(print_r($this->dbQueries->dbh->errorInfo(), true));
        }
    }

    function getTrainingTypes() 
    {
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

    function getClimbCategories() {
        $sql = 'SELECT 
                    rank,
                    category,
                    cat,
                    min_gradient,
                    min_distance,
                    min_height
                FROM 
                    t_climbs_categories
                ORDER BY
                    rank DESC';
        $stmt = $this->dbQueries->dbh->prepare($sql);
        $stmt->execute() or die(print_r($this->dbQueries->dbh->errorInfo(), true));

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function insertClimb($session_date,   $climb_num,
                         $bottom,         $top,
                         $gradient_avg,   $gradient_max,
                         $total_distance, $total_climbed,
                         $min_altitude,   $max_altitude)
    {
        $sql = 'INSERT INTO t_climbs_data
                   (session_date,
                    climb_num,
                    bottom,
                    top,
                    gradient_avg,
                    gradient_max,
                    total_distance,
                    total_climbed,
                    min_altitude,
                    max_altitude,
                    userid)
                VALUES 
                   (:session_date,
                    :climb_num,
                    :bottom,
                    :top,
                    :gradient_avg,
                    :gradient_max,
                    :total_distance,
                    :total_climbed,
                    :min_altitude,
                    :max_altitude,
                    :userid)';
        $stmt = $this->dbQueries->dbh->prepare($sql);

        // TODO: Add the types
        $stmt->bindParam(':session_date',   $session_date);
        $stmt->bindParam(':climb_num',      $climb_num);
        $stmt->bindParam(':bottom',         $bottom);
        $stmt->bindParam(':top',            $top);
        $stmt->bindParam(':gradient_avg',   $gradient_avg);
        $stmt->bindParam(':gradient_max',   $gradient_max);
        $stmt->bindParam(':total_distance', $total_distance);
        $stmt->bindParam(':total_climbed',  $total_climbed);
        $stmt->bindParam(':min_altitude',   $min_altitude);
        $stmt->bindParam(':max_altitude',   $max_altitude);
        $stmt->bindParam(':userid',         $_SESSION['userid'], PDO::PARAM_STR);

        $stmt->execute() or die(print_r($this->dbQueries->dbh->errorInfo(), true));
    }

    function getClimbs() {
        $sql = 'SELECT 
                    userid,
                    session_date,
                    climb_num,
                    name,
                    description,
                    \'0\' AS duration,
                    \'0\' AS distance
                FROM 
                    v_climbs_details
                WHERE
                    userid = :userid
                ORDER BY
                    climb_num ASC';
        $stmt = $this->dbQueries->dbh->prepare($sql);
        $stmt->bindParam(':userid', $_SESSION['userid'], PDO::PARAM_STR);

        $stmt->execute() or die(print_r($this->dbQueries->dbh->errorInfo(), true));

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    function getPower($gradient,       $temperature, 
                      $altitude,       $velocity,
                      $velocity_delta, $time_delta,
                      $rider_weight,   $bike_weight) 
    {
        /* Calculate static power */
        $GRAVITY              = 9.80665;    /* meters per second ^ 2 */
        $SEA_LEVE_AIR_DENSITY = 1.293;      /* kg/m^3 */

        $rolling_resistance = 0.005;        /* clinchers */
        $frontal_area       = 0.388;        /* m^2 - bar hoods */
        $headwind           = 0 / 3.6;      /* in meters per second */
        $altitude           = 100;          /* meters above sea level */
        $transmission       = 0.95;         /* in percent, ie 95% = 0.95 */

        /*
        echo "rolling res  = $rolling_resistance\n";
        echo "frontal_area = $frontal_area\n";
        echo "gradient     = $gradient\n";
        echo "temperature  = $temperature\n";
        echo "headwind     = $headwind\n";
        echo "altitude     = $altitude\n";
        echo "transmission = $transmission\n";
        */

        /* Variables */
        $velocity           = $velocity / 3.6;      /* convert to m/s */
        $velocity_delta     = $velocity_delta / 3.6;/* convert to m/s */
        $gradient           = $gradient * 0.01;     /* convert from percent */

        /*
        echo "velocity     = $velocity\n";
        echo "rider_weight = $rider_weight\n";
        echo "bike_weight  = $bike_weight\n";
        */

        /* Start the calculations */
        /* Full air resistance */
        $density = ($SEA_LEVE_AIR_DENSITY - 0.00426 * $temperature) * pow(M_E, -$altitude / 7000.0);
        $A2      = 0.5 * $frontal_area * $density;

        /*
        echo "density     = $density\n";
        echo "A2          = $A2\n";
        */

        /* Gravity and rolling resistance */
        /* Weight in newtons */
        $total_weight     = $rider_weight + $bike_weight;
        $total_weightn    = $GRAVITY * $total_weight;
        $total_resistance = $total_weightn * ($gradient + $rolling_resistance);
        $total_air_velocity = $velocity + $headwind;

        $static_power = ($velocity * $total_resistance + 
                 pow($total_air_velocity, 3) * $A2) / $transmission;

        /*
        echo "total_weightn       = $total_weightn\n";
        echo "total_resistance   = $total_resistance\n";
        echo "total_air_velocity = $total_air_velocity\n";
        */

        /* Calculate dynamic power = 1/2m(v^2) */
        $dynamic_enery = 0.5 * $total_weight * abs($velocity_delta) * $velocity_delta;
        if ($time_delta > 0) {
            $dynamic_power = $dynamic_enery / $time_delta;
        } else {
            $dynamic_power = 0;
        }

        return round($static_power + $dynamic_power, 1);
    }
}

?>
