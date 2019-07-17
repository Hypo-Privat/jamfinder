var map, baseLayer;
var countryGeoJsonUrl = "js/Countries.js";

var backgroundColor = new Microsoft.Maps.Color(255, 244, 242, 238); //Off White
var countryColor = new Microsoft.Maps.Color(255, 156, 156, 156); //Gray

function GetMap() {
    // Initialize the map
    map = new Microsoft.Maps.Map(document.getElementById("myMap"), {
        credentials: "YOUR_BING_MAPS_KEY",
        mapTypeId: Microsoft.Maps.MapTypeId.mercator, //Disable base maps
        backgroundColor: backgroundColor, //White
        showDashboard: false,
        showMapTypeSelector: false,
        showScalebar: false,
        enableSearchLogo: false,
        disableBirdseye: true,
        showBreadcrumb : true
    });

    //Create a base layer for the country polygons
    baseLayer = new Microsoft.Maps.EntityCollection();
    map.entities.push(baseLayer);

    //Register and load the GeoJSON Module
    Microsoft.Maps.registerModule("GeoJSONModule", "js/GeoJSONModule.min.js");
    Microsoft.Maps.loadModule("GeoJSONModule", {
        callback: LoadCountryData
    });
}

function LoadCountryData() {
    //Define default shape options
    var polygonOptions = {
        fillColor: countryColor,
        strokeColor: backgroundColor,
        strokeThickness : 2
    };

    new GeoJSONModule().ImportGeoJSON(countryGeoJsonUrl, GeoJSONImportedCallback, { polygonOptions: polygonOptions });
}

function GeoJSONImportedCallback(items, bounds) {
    //Add countries to map
    baseLayer.push(items);
}