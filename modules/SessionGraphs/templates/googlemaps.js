var MyMap;

function LoadGPXFileIntoGoogleMap(map, filename)
{
    // Remove any existing overlays from the map.
    map.clearOverlays();

    var request = GXmlHttp.create();
    request.open("GET", filename, true);
    request.onreadystatechange = function()
    {
        if (request.readyState == 4)
        {
            parser = new GPXParser(request.responseXML, map);
            parser.SetTrackColour("#ff0000");                   // Set the track line colour
            parser.SetTrackWidth(5);                            // Set the track line width
            parser.SetMinTrackPointDelta(0.001);                // Set the minimum distance between track points
            parser.CenterAndZoom(request.responseXML, G_NORMAL_MAP); // Center and Zoom the map over all the points.
            parser.AddTrackpointsToMap();                       // Add the trackpoints
            parser.AddWaypointsToMap();                         // Add the waypoints
        }
    }
    request.send(null);
}

function GoogleMaps_Init(url)
{
    MyMap = new GMap2(document.getElementById("map"));
    MyMap.addControl(new GLargeMapControl());
    MyMap.addControl(new GMapTypeControl());
    LoadGPXFileIntoGoogleMap(MyMap, url);
}
