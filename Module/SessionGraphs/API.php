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

class Module_SessionGraphs_API extends Core_ModuleAPI {
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
    {
        $valid_fields = array('distance','speed','heartrate',
                              'altitude','power','temperature',
                              'cadence', 'gradient');

        // Make sure field is a valid field
        if (!in_array($field, $valid_fields)) {
            return;
        }
        $db = Zend_Registry::get('db');

        $select = $db->select()
                     ->from('t_exercise_data',
                             array('(extract(EPOCH from "time")*1000) AS time',
                                   $field))
                     ->where('userid = ?', Core_User::getUserId())
                     ->where('session_date = ?', $session_date)
                     ->order('time DESC');
        if (!is_null($min_time)) {
            $select->where('time >= ?', $min_time);
        }
        if (!is_null($max_time)) {
            $select->where('time <= ?', $max_time);
        }
        $stmt = $db->query($select);

        /* TODO: Do this in a more generic way */
        $data = $stmt->fetchAll();
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
        $db = Zend_Registry::get('db');

        $select = $db->select()
                     ->from('t_exercise_data',
                             array('latitude as lat',
                                   'longitude as lon'))
                     ->where('userid       = ?', Core_User::getUserId())
                     ->where('session_date = ?', $session_date)
                     ->order('time DESC');

        if (!is_null($min_time)) {
            $select->where('time >= ?', $min_time);
        }
        if (!is_null($max_time)) {
            $select->where('time <= ?', $max_time);
        }
        $stmt = $db->query($select);

        return $stmt->fetchAll();
    }

    function getSession($session_date) 
    {
        $db = Zend_Registry::get('db');

        $select = $db->select()
                     ->from('v_exercise_totals',
                             array('userid',
                                   'session_date',
                                   'type_short',
                                   'description',
                                   'duration',
                                   'distance',
                                   'avg_heartrate',
                                   'max_heartrate',
                                   'avg_heartrate_percent',
                                   'max_heartrate_percent',
                                   'avg_speed',
                                   'max_speed',
                                   'calories',
                                   'total_ascent',
                                   'total_descent',
                                   'comment'))
                     ->where('userid = ?', Core_User::getUserId())
                     ->where('session_date = ?', $session_date);
        $stmt = $db->query($select);

        $sessions = $stmt->fetchAll();
        return $sessions[0];
    }

    function getLaps($session_date) 
    {
        $db = Zend_Registry::get('db');

        $select = $db->select()
                     ->from('t_exercise_laps',
                             array('userid',
                                   'session_date',
                                   'lap_num',
                                   'start_time',
                                   '(start_time + total_duration) AS end_time',
                                   'start_pos_lat',
                                   'start_pos_long',
                                   'duration',
                                   'calories',
                                   'distance',
                                   'avg_heartrate',
                                   'max_heartrate',
                                   'avg_speed',
                                   'max_speed',
                                   'total_ascent'))
                     ->where('userid = ?', Core_User::getUserId())
                     ->where('session_date = ?', $session_date)
                     ->order('lap_num ASC');
        $stmt = $db->query($select);

        return $stmt->fetchAll();
    }

    function getZones($session_date, $min_time = NULL, $max_time = NULL) 
    {
        $db = Zend_Registry::get('db');

        $select = $db->select()
                     ->from(array('data' => 'v_exercise_data'),
                             array('userid',
                                   'zone',
                                   'SUM(length) AS length'))
                     ->where('userid       = ?', Core_User::getUserId())
                     ->where('session_date = ?', $session_date)
                     ->group('zone')
                     ->group('userid')
                     ->order('zone ASC');
        if (!is_null($min_time)) {
            $select->where('time >= ?', $min_time);
        }
        if (!is_null($max_time)) {
            $select->where('time <= ?', $max_time);
        }

        $stmt = $db->query($select);

        return $stmt->fetchAll();
    }

    function getClimbs($session_date) 
    {
        $db = Zend_Registry::get('db');

        $select = $db->select()
                     ->from('v_climbs_data',
                             array('userid',
                                   'session_date',
                                   'climb_num',
                                   'cat AS category',
                                   '(top - bottom) AS duration',
                                   'total_distance',
                                   'total_climbed',
                                   'gradient_avg',
                                   'gradient_max',
                                   'min_altitude',
                                   'max_altitude'))
                     ->where('userid = ?', Core_User::getUserId())
                     ->where('session_date = ?', $session_date)
                     ->order('climb_num');
        $stmt = $db->query($select);

        return $stmt->fetchAll();
    }

    function getClimb($session_date, $climb_num) 
    {
        $db = Zend_Registry::get('db');

        $select = $db->select()
                     ->from('v_climbs_data',
                             array('userid',
                                   'session_date',
                                   'climb_num',
                                   'cat AS category',
                                   'top',
                                   'bottom',
                                   'total_distance',
                                   'total_climbed',
                                   'gradient_avg',
                                   'gradient_max',
                                   'min_altitude',
                                   'max_altitude'))
                     ->where('userid       = ?', Core_User::getUserId())
                     ->where('session_date = ?', $session_date)
                     ->where('climb_num    = ?', $climb_num);
        $stmt = $db->query($select);

        $climbs = $stmt->fetchAll();
        return $climbs[0];
    }

    function getGPXClimbData($session_date, $climb_num) 
    {
        $db = Zend_Registry::get('db');

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

        $user = new Zend_Session_Namespace('user');
        $stmt->bindParam(':userid',       $user->userid, PDO::PARAM_STR);
        $stmt->bindParam(':session_date', $session_date, PDO::PARAM_STR);
        $stmt->bindParam(':climb_num',    $climb_num,    PDO::PARAM_INT);

        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
}

?>
