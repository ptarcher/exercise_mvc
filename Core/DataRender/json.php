<?php

require_once('Core/DataRender.php');

class Core_DataRender_Json extends Core_DataRender {
    function render() {
        // Set the headers correctly
        header('Content-Type: text/javascript; charset=utf8');
        header('Access-Control-Allow-Origin: http://www.example.com/');
        header('Access-Control-Max-Age: 3628800');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        return json_encode($this->data);
    }
}

?>
