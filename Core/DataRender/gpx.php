<?php

require_once('Core/DataRender.php');

class CoreDataRender_Gpx extends CoreDataRender {
    function render() {
        $dom = new DOMDocument("1.0");
        
        $root = $dom->createElement("gpx");
        $dom->appendChild($root);

        $creator = $dom->createAttribute("createor");
        $version = $dom->createAttribute("version");
        $version_value = $dom->createTextNode("1.0");
        $version->appendChild($version_value);

        $root->appendChild($creator);
        $root->appendChild($version);

        $trk = $dom->createElement("trk");
        $root->appendChild($trk);

        $trkseg = $dom->createElement("trkseg");
        $trk->appendChild($trkseg);

        /* Create the items */
        foreach ($this->data as $point) {
            $trkpt = $dom->createElement("trkpt");
            foreach($point as $key => $value) {
                $attribute = $dom->createAttribute($key);
                $text      = $dom->createTextNode($value);
                $attribute->appendChild($text);
                $trkpt->appendChild($attribute);
            }
            $trkseg->appendChild($trkpt);
        }

        header("Content-type: text/xml");
        return $dom->saveXML();
    }
}

?>
