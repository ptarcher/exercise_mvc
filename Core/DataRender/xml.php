<?php

require_once('Core/DataRender.php');

class CoreDataRender_Xml extends CoreDataRender {
    function render() {
        header("Content-type: text/xml");
    }
}

?>
