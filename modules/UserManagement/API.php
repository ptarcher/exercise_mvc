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

class ModuleUserManagementAPI extends CoreModuleAPI {
    function getUsers() {
        $sql = 'SELECT 
                    userid
                FROM 
                    t_users
                ORDER BY
                    userid DESC';
        $stmt = $this->dbQueries->dbh->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function createUser($userid, $password) {
        $password_salt = Common::getRandomString(64);
        $password_hash = sha1($password . $password_salt);

        $sql = 'INSERT INTO t_users
                   (userid,
                    password_hash,
                    password_salt)
                VALUES 
                   (:userid,
                    :password_hash,
                    :password_salt
                   )';

        $stmt = $this->dbQueries->dbh->prepare($sql);

        $stmt->bindParam(':userid',        $userid,        PDO::PARAM_STR);
        $stmt->bindParam(':password_hash', $password_hash, PDO::PARAM_STR);
        $stmt->bindParam(':password_salt', $password_salt, PDO::PARAM_STR);

        $stmt->execute() or die("failed to execute $SQL");
    }

    // TODO: Convert this into user groups
    function getExerciseTypes() {
        $sql = 'SELECT 
                    type_short,
                    type
                FROM 
                    t_training_types
                ORDER BY
                    type_short';
        $stmt = $this->dbQueries->dbh->prepare($sql);
        $stmt->execute();

        $types = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /* Convert into a nice display table */
        $exercise_types = array();
        foreach ($types as $type) {
            $description = $type['type_short'] . ' - ' . $type['type'];
            $exercise_types[$type['type_short']] = $description;
        }

        return $exercise_types;
    }
}

?>
