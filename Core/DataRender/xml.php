<?php

require_once('Core/DataRender.php');

class Core_DataRender_Xml extends Core_DataRender {
    function render() {
        header("Content-type: text/xml");
    }
}

?>
