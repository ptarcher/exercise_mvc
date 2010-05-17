// Start position for the map (hardcoded here for simplicity,
// but maybe you want to get from URL params)
// TODO: Get these dynamically
var lat=-34.05
var lon=151.04
var zoom=13

var map; //complex object of type OpenLayers.Map

//Initialise the 'map' object
function OSM_Init() {
    map = new OpenLayers.Map ("map", {
            controls:[
                new OpenLayers.Control.Navigation(),
                new OpenLayers.Control.PanZoomBar(),
                new OpenLayers.Control.LayerSwitcher(),
                new OpenLayers.Control.Attribution()
            ],
            maxExtent:         new OpenLayers.Bounds(-20037508.34,-20037508.34,20037508.34,20037508.34), maxResolution: 156543.0399, numZoomLevels: 19, units: 'm',
            projection:        new OpenLayers.Projection("EPSG:900913"),
            displayProjection: new OpenLayers.Projection("EPSG:4326")
    } );


    // Define the map layer
    // Note that we use a predefined layer that will be
    // kept up to date with URL changes
    // Here we define just one layer, but providing a choice
    // of several layers is also quite simple
    // Other defined layers are OpenLayers.Layer.OSM.Mapnik, OpenLayers.Layer.OSM.Maplint and OpenLayers.Layer.OSM.CycleMap
    layerMapnik      = new OpenLayers.Layer.OSM.Mapnik("Mapnik");
    layerTilesAtHome = new OpenLayers.Layer.OSM.Osmarender("Osmarender");
    layerCycleMap    = new OpenLayers.Layer.OSM.CycleMap("CycleMap");
    layerMarkers     = new OpenLayers.Layer.Markers("Markers");
    map.addLayer(layerMapnik);
    map.addLayer(layerTilesAtHome);
    map.addLayer(layerCycleMap);
    map.addLayer(layerMarkers);

    // Add the Layer with GPX Track
    var url  = "http://localhost/~ptarcher/exercise_mvc/index.php?module=APIAccess&method=SessionGraphs.getGPXData&format=gpx&session_date="+encodeURIComponent(session_date);
    var lgpx = new OpenLayers.Layer.GML("MB Bruderholz", url, 
        {format: OpenLayers.Format.GPX,
         style: {strokeColor: "green", strokeWidth: 5, strokeOpacity: 0.5},
         projection: new OpenLayers.Projection("EPSG:4326")
        });
    map.addLayer(lgpx);

    var lonLat = new OpenLayers.LonLat(lon, lat).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());
    map.setCenter (lonLat, zoom);

    var size   = new OpenLayers.Size(21,25);
    var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
    var icon   = new OpenLayers.Icon('http://www.openstreetmap.org/openlayers/img/marker.png',size,offset);
    layerMarkers.addMarker(new OpenLayers.Marker(lonLat,icon));
}

