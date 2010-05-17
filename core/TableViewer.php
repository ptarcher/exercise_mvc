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

class TableViewer {
    var $html;
    var $sessions;
    var $headers;
    var $fields;

    private function renderTableHeader($headers) {
        /* Print the header */
        $this->html->draw('<tr>');
        foreach ($headers as $header) {
            $this->html->drawHeaderCol($header);
        }
        $this->html->draw('</tr>');
    }

    private function drawRow($row, $fields) {
        $this->html->draw('<tr>');
        foreach ($fields as $field) {
            if ($field == 'session_date') {
                $this->html->drawCol("<a href=\"index.php?module=SessionGraphsAJAX&action=view&session_date=".urlencode($row['session_date'])."\">" . htmlentities($row['session_date']) . "</a>");
            } else {
                $this->html->drawCol($row[$field]);
            }
        }
        $this->html->draw('</tr>');
    }

    function __construct($table) {
        global $html;

        $this->html    = $html;
        $this->table   = $table;
        $this->fields  = array();
        $this->headers = array();
    }

    function selectHeaders($headers) {
        foreach ($headers as $header) {
            $this->headers[] = $header;
        }
    }

    function selectFields($fields) {
        foreach ($fields as $field) {
            $this->fields[] = $field;
        }
    }

    function addLinks($fields) {
    }

    function renderTable() {
        $this->html->draw('<table border=1>');

        $this->renderTableHeader($this->headers);

        /* Print Entries */
        if ($this->table) {
            foreach ($this->table as $row) {
                $this->drawRow($row, $this->fields);
            }
        }

        $this->html->draw('</table>');

    }


    function __destruct() {
        $this->session = NULL;
    }
}

?>
