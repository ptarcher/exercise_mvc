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

class Module_Sessions_API extends Core_ModuleAPI 
{
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
	
    function getSessions() 
    {
        $db = Zend_Registry::get('db');

        $select = $db->select()
                     ->from('t_exercise_totals',
                            array('userid','session_date','type_short',
                                  'description','duration','distance',
                                  'avg_heartrate','avg_speed','comment'))
                     ->where('userid = ?', Core_Common::getCurrentUserLogin())
                     ->order('session_date DESC');
        $stmt = $db->query($select);
        $result = $stmt->fetchAll();

        return $result;
    }

    function updateSession($session_date = "", $type_short = "",
                           $description  = "", $comment    = "") 
    {
        $db = Zend_Registry::get('db');

        $db->update('t_exercise_totals',
                array('type_short'    => $type_short,
                      'description'   => $description,
                      'comment'       => $comment),
                array('session_date = \''.$session_date.'\'',
                      'userid       = \''.Core_Common::getCurrentUserLogin().'\'',));
    }

    function createSessionFull($session_date,  $type_short, 
                               $description,   $duration, 
                               $distance,      $calories,
                               $avg_heartrate, $max_heartrate,
                               $avg_speed,     $max_speed,
                               $total_ascent,  $total_descent,
                               $comment) 
    {
        $db = Zend_Registry::get('db');

        $db->insert('t_exercise_totals',
                array('session_date'  => $session_date,
                      'type_short'    => $type_short,
                      'description'   => $description,
                      'duration'      => $duration,
                      'distance'      => $distance,
                      'calories'      => $calories,
                      'avg_heartrate' => $avg_heartrate,
                      'max_heartrate' => $max_heartrate,
                      'avg_speed'     => $avg_speed,
                      'max_speed'     => $max_speed,
                      'total_ascent'  => $total_ascent,
                      'total_descent' => $total_descent,
                      'comment'       => $comment,
                      'userid'        => Core_Common::getCurrentUserLogin()));
    }

    function insertLap($session_date,  $lap_num,
                       $start_time, 
                       $start_pos_lat, $start_pos_long,
                       $timer_duration, $total_duration,
                       $calories,
                       $avg_heartrate, $max_heartrate,
                       $avg_speed,     $max_speed,
                       $total_ascent,  $total_descent,
                       $distance) 
    {
        $db = Zend_Registry::get('db');

        $db->insert('t_exercise_laps',
                array('session_date'    => $session_date,
                      'lap_num'         => $lap_num,
                      'start_time'      => $start_time,
                      'start_pos_lat'   => $start_pos_lat,
                      'start_pos_long'  => $start_pos_long,
                      'duration'        => $timer_duration,
                      'total_duration'  => $total_duration,
                      'calories'        => $calories,
                      'distance'        => $distance,
                      'avg_heartrate'   => $avg_heartrate,
                      'max_heartrate'   => $max_heartrate,
                      'avg_speed'       => $avg_speed,
                      'max_speed'       => $max_speed,
                      'total_ascent'    => $total_ascent,
                      'total_descent'   => $total_descent,
                      'userid'          => Core_Common::getCurrentUserLogin()));
    }


    function deleteSession($session_date) 
    {
        $db = Zend_Registry::get('db');

        $db->beginTransaction();

        try {
            $where = array('session_date = \''.$session_date.'\'',
                           'userid       = \''.Core_Common::getCurrentUserLogin().'\'');

            /* Delete all different data associated with the session */
            $db->delete('t_exercise_data',   $where);
            $db->delete('t_exercise_laps',   $where);
            $db->delete('t_climbs_data',     $where);
            $db->delete('t_exercise_totals', $where);

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            $e->getMessage();
        }
    }

    function insertAllSessionData($session_date, $records) 
    {
        $db = Zend_Registry::get('db');

        $i = 0;
        $max_inserts = 100;

        for ($rows = 0; $rows < count($records); $rows += $max_inserts) {
            $values = array();
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

                $values[':time'.       $i] = $records[$i+$rows]->interval;
                $values[':distance'.   $i] = $records[$i+$rows]->distance;
                $values[':heartrate'.  $i] = $records[$i+$rows]->heart_rate;
                $values[':speed'.      $i] = $records[$i+$rows]->speed;
                $values[':latitude'.   $i] = $records[$i+$rows]->position_lat;
                $values[':longitude'.  $i] = $records[$i+$rows]->position_long;
                $values[':altitude'.   $i] = $records[$i+$rows]->altitude;
                $values[':cadence'.    $i] = $records[$i+$rows]->cadence;
                $values[':temperature'.$i] = $records[$i+$rows]->temperature;
                $values[':power'.      $i] = $records[$i+$rows]->power;
                $values[':gradient'.   $i] = $records[$i+$rows]->gradient;
            }

            //$stmt = new Zend_Db_Statement($db, $sql);
            $stmt = new Zend_Db_Statement_Pdo($db, $sql);

            /* Add the constant values for all rows */
            $values[':session_date'] = $session_date;
            $values[':userid']       = Core_Common::getCurrentUserLogin();

            $stmt->execute($values);
        }
    }

    function getTrainingTypes() 
    {
        $db = Zend_Registry::get('db');

        $select = $db->select()
                     ->from('t_training_types',
                             array('type_short',
                                   'type'))
                     ->order('type_short');

        $stmt = $db->query($select);
        $types =  $stmt->fetchAll();

        /* Convert into a nice display table */
        $exercise_types = array();
        foreach ($types as $type) {
            $description = $type['type_short'] . ' - ' . $type['type'];
            $exercise_types[$type['type_short']] = $description;
        }

        return $exercise_types;
    }

    function getSessionTypes() 
    {
        $db = Zend_Registry::get('db');

        $select = $db->select()
                     ->from('t_exercise_total_types',
                             array('total_type'))
                     ->order('total_type');
        $stmt = $db->query($select);

        return $stmt->fetchAll();
    }

    function getClimbCategories() 
    {
        $db = Zend_Registry::get('db');

        $select = $db->select()
                     ->from('t_climbs_categories',
                             array('rank',
                                   'category',
                                   'cat',
                                   'min_gradient',
                                   'min_distance',
                                   'min_height'))
                     ->order('rank DESC');
        $stmt = $db->query($select);

        return $stmt->fetchAll();
    }

    function insertClimb($session_date,   $climb_num,
                         $bottom,         $top,
                         $gradient_avg,   $gradient_max,
                         $total_distance, $total_climbed,
                         $min_altitude,   $max_altitude)
    {
        $db = Zend_Registry::get('db');

        $db->insert('t_climbs_data',
                array('session_date'   => $session_date,
                      'climb_num'      => $climb_num,
                      'bottom'         => $bottom,
                      'top'            => $top,
                      'gradient_avg'   => $gradient_avg,
                      'gradient_max'   => $gradient_max,
                      'total_distance' => $total_distance,
                      'total_climbed'  => $total_climbed,
                      'min_altitude'   => $min_altitude,
                      'max_altitude'   => $max_altitude,
                      'userid'         => Core_Common::getCurrentUserLogin()));
    }

    function getClimbs() 
    {
        $db = Zend_Registry::get('db');

        $select = $db->select()
                     ->from('v_climbs_details',
                             array('userid',
                                   'session_date',
                                   'climb_num',
                                   'name',
                                   'description',
                                   'name AS duration',
                                   'name AS distance'))
                     ->where('userid = ?', Core_Common::getCurrentUserLogin())
                     ->order('climb_num ASC');
        $stmt = $db->query($select);

        return $stmt->fetchAll();
    }

    function getWindDirections() 
    {
        $db = Zend_Registry::get('db');

        $select = $db->select()
                     ->from('t_wind_directions',
                             array('direction',
                                   'angle'))
                     ->order('angle ASC');
        $stmt = $db->query($select);
        $directions = $stmt->fetchAll();

        /* Convert into a nice display table */
        $wind_directions = array();
        foreach ($directions as $dir) {
            $wind_directions[] = $dir['direction'];
        }

        return $wind_directions;
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
