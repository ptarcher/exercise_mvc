<?php

require_once('core/DataRender.php');

class CoreDataRender_Xml extends CoreDataRender {
    function render() {
        header("Content-type: text/xml");
    }
}

?>
