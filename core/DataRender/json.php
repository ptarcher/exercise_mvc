<?php

require_once('core/DataRender.php');

class CoreDataRender_Json extends CoreDataRender {
    function render() {
        // TODO: Set the headers correctly
        return json_encode($this->data);
    }
}

?>
