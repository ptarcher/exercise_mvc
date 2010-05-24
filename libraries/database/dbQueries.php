<?php
/*
 *  Description: Queries for grabbing database results
 *  Date:        04/06/2009
 *  
 *  Author:      Paul Archer <ptarcher@gmail.com>
 *
 * Copyright (C) 2009 Paul Archer
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

require_once('config.php');

class dbQueries
{
    function __construct() {
        /* Connect to the database */
        try {
            $this->dbh = new PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME.';port='.DB_PORT,DB_USER,DB_PASSWORD);

        } catch (PDOException $e) {
            $debug=true;
            if ($debug) {
                printf("Error!: %s<br>", $e->getMessage());
            } else {
                printf("Unable to connect to database");
            }
            die();
        }
    }

    function __destruct() {
        $this->dbh = null;
    }
}

$GLOBALS['dbQueries'] = new dbQueries();

?>
