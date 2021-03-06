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

class Module_Plans_API extends Core_ModuleAPI {
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

    /* Weekly functoins */
    function getWeeklyPlans() 
    {
        $db = Zend_Registry::get('db');

        $select = $db->select()
                     ->from('t_exercise_plans_weekly',
                             array('userid',
                                 'week_date',
                                 'period',
                                 'description'))
                     ->where('userid = ?', Core_Common::getCurrentUserLogin())
                     ->order('week_date DESC');
        $stmt = $db->query($select);

        return $stmt->fetchAll();
    }

    function createWeeklyPlan($week_date, $period, $description, $comment = "") 
    {
        $db = Zend_Registry::get('db');

        $db->insert('t_exercise_plans_weekly',
                array('userid'      => Core_Common::getCurrentUserLogin(),
                      'week_date'   => $week_date,
                      'period'      => $period,
                      'description' => $description));
    }

    function updateWeeklyPlan($week_date, $period, $description, $comment = "") 
    {
        $db = Zend_Registry::get('db');

        $db->update('t_exercise_plans_weekly',
                array('period'      => $period,
                      'description' => $description,
                      '"comment"'   => $comment),
                array('userid    = \''.Core_Common::getCurrentUserLogin().'\'',
                      'week_date = \''.$weel_date.'\''));
    }

    function deleteWeeklyPlan($week_date) 
    {
        $db = Zend_Registry::get('db');

        $db->delete('t_exercise_plans_weekly',
                array('userid    = \''.Core_Common::getCurrentUserLogin().'\'',
                      'week_date = \''.$week_date.'\''));
    }

    /* Daily functoins */
    function getDailyPlans($week_date, $order = 'DESC') 
    {
        $db = Zend_Registry::get('db');

        $select = $db->select()
                     ->from('t_exercise_plans_daily',
                             array('userid',
                                   'week_date',
                                   'timestamp',
                                   '(extract(EPOCH from "timestamp")*1000) AS epoch',
                                   'category',
                                   'description',
                                   '(volume    * 100) AS volume',
                                   '(intensity * 100) AS intensity',
                                   'duration',
                                   'focus',
                                   'comment',
                                   'session_timestamp'))
                     ->where('userid    = ?', Core_Common::getCurrentUserLogin())
                     ->where('week_date = ?', $week_date)
                     ->order('timestamp '.$order);
        $stmt = $db->query($select);

        return $stmt->fetchAll();
    }

    function getDailyPlansDetails($week_date, $order = 'DESC') 
    {
        $db = Zend_Registry::get('db');

        $select = $db->select()
                     ->from(array('daily' => 't_exercise_plans_daily'),
                            array('userid',
                                  'week_date',
                                  'timestamp',
                                  '(extract(EPOCH from "timestamp")*1000) AS epoch',
                                  'category',
                                  'description',
                                  '(volume    * 100) AS volume',
                                  '(intensity * 100) AS intensity',
                                  'duration',
                                  'focus',
                                  'comment',
                                  '(extract(EPOCH from session_timestamp)*1000) AS session_epoch'))
                     ->where('daily.userid    = ?', Core_Common::getCurrentUserLogin())
                     ->where('daily.week_date = ?', $week_date)
                     ->joinLeft(array('totals' => 't_exercise_totals'),
                             'daily.userid            = totals.userid AND
                              daily.session_timestamp = totals.session_date',
                              array('duration AS session_duration'))
                     ->order('timestamp '.$order);
        $stmt = $db->query($select);

        return $stmt->fetchAll();
    }

    function addDailyPlan($week_date,   $timestamp,
                          $category,    $description,
                          $focus,       $duration,
                          $comment,     $volume,
                          $intensity) 
    {
        $db = Zend_Registry::get('db');

        $db->insert('t_exercise_plans_daily',
                array('userid'      => Core_Common::getCurrentUserLogin(),
                      'week_date'   => $week_date,
                      'timestamp'   => $timestamp,
                      'category'    => $category,
                      'description' => $description,
                      'volume'      => $volume,
                      'intensity'   => $intensity,
                      'duration'    => $duration,
                      'focus'       => $focus,
                      'comment'     => $comment));
    }

    /**
     *
     */
    function getClosestPlan($date) 
    {
        $db = Zend_Registry::get('db');

        $select = $db->select()
                     ->from('t_exercise_plans_daily',
                             array('@((extract(EPOCH from timestamp)) - extract(EPOCH from TIMESTAMP WITH TIME ZONE \''.$date.'\')) AS interval',
                                   'userid',
                                   'timestamp',
                                   'category',
                                   'description',
                                   'volume',
                                   'intensity',
                                   'duration',
                                   'focus',
                                   'comment',
                                   'week_date'))
                     ->where('userid = ?', Core_Common::getCurrentUserLogin())
                     ->order('interval ASC')
                     ->limit('1');
        $stmt = $db->query($select);

        return $stmt->fetch();
    }

    function updatePlanSession($plan_date, $session_date) 
    {
        $db = Zend_Registry::get('db');

        $db->update('t_exercise_plans_daily',
                array('session_timestamp' => $session_date),
                array('userid    = \''.Core_Common::getCurrentUserLogin().'\'',
                      'timestamp = \''.$plan_date.'\''));
    }
}

?>
