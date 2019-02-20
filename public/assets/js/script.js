$(document).ready(function () {

  let map = new OpenLayers.Map("map");
  let osmLayer = new OpenLayers.Layer.OSM();
  map.addLayer(osmLayer);

  let centerLat = 46.621081;
  let centerLon = 8.1596857;
  let zoom = 8;

  let fromProjection = new OpenLayers.Projection("EPSG:4326");   // Transform from WGS 1984
  let toProjection = new OpenLayers.Projection("EPSG:900913"); // to Spherical Mercator Projection
  let centerPosition = new OpenLayers.LonLat(centerLon, centerLat).transform(fromProjection, toProjection);

  map.setCenter(centerPosition, zoom);

  getAllStudents(function (res) {
    for (let i = 0; i < res.length; i++) {
      let value = res[i];
      let lat = Number(value.latitude);
      let lon = Number(value.longitude);

      let position = new OpenLayers.LonLat(lon, lat).transform(fromProjection, toProjection);
      let markersLayer = new OpenLayers.Layer.Markers("Markers");

      let marker = new OpenLayers.Marker(position);
      let markerDiv = $(marker.icon.imageDiv);
      let tooltip = $(`<span class="tooltiptext">${value.firstname} ${value.lastname} <br> ${value.placeid} ${value.placename}</span>`);

      markerDiv.addClass('tooltip');
      tooltip.appendTo(markerDiv);

      map.addLayer(markersLayer);
      markersLayer.addMarker(marker);
    }
  });

  $('.tabs').tabs();

  $('#reload-map').on("click", function () {
    setTimeout(function () {
      $.getJSON('http://localhost/MarcoSchneiderM151_LB3/students/places/to-update?format=json', function (values) {
        if (values.length > 0) {
          M.toast({
            html: '<span>Es gibt Ortschaften ohne Koordinaten.</span><button class="btn green" id="update-trigger">Update ausführen!</button>',
            classes: 'orange',
            displayLength: 10000
          });
          $('#update-trigger').on("click", function () {
            updatePlaces();
            setTimeout(function () {
              location.reload();
            }, 1000);
          });
        } else {
          location.reload();
        }
      });
    }, 100);
  });
});