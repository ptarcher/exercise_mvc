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

class Module_LoginAPI extends Core_ModuleAPI {
    function getUser($userid) 
    {
        $sql = 'SELECT 
                    userid,
                    password_hash,
                    password_salt,
                    athlete,
                    coach,
                    superuser
                FROM 
                    t_users
                WHERE
                    userid = :userid';
        $stmt = $this->dbQueries->dbh->prepare($sql);
        $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);

        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $users[0];
    }

    function checkLogin($userid, $password) 
    {
        $user = $this->getUser($userid);
        $password_hash = sha1($password . $user['password_salt']);

        return (0 == strcmp($password_hash, $user['password_hash']));
    }
}

?>
