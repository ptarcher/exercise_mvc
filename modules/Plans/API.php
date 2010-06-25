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

class ModulePlansAPI extends CoreModuleAPI {
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

    function getWeeklyPlans() {
        $sql = 'SELECT
                    userid,
                    week_date,
                    period,
                    description,
                    "comment"
                FROM 
                    t_exercise_plans_weekly
                WHERE 
                    userid = :userid
                ORDER BY
                    week_date DESC';
        $stmt = $this->dbQueries->dbh->prepare($sql);

        $stmt->bindParam(':userid', $_SESSION['userid'], PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

	
    function getDailyPlans($week_date) {
        $sql = 'SELECT 
                    userid,
                    week_date,
                    timestamp,
                    category,
                    description,
                    volume    * 100 AS volume,
                    intensity * 100 AS intensity,
                    duration,
                    focus,
                    comment
                FROM 
                    t_exercise_plans_daily
                WHERE 
                    userid    = :userid AND
                    week_date = :week_date
                ORDER BY
                    timestamp DESC';
        $stmt = $this->dbQueries->dbh->prepare($sql);

        $stmt->bindParam(':week_date', $week_date);
        $stmt->bindParam(':userid',    $_SESSION['userid'], PDO::PARAM_STR);

        $stmt->execute() or die(print_r($this->dbQueries->dbh->errorInfo(), true));

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
