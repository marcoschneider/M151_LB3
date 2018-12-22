$(document).ready(function () {
  let map = new OpenLayers.Map("map");
  let mapnik = new OpenLayers.Layer.OSM();
  map.addLayer(mapnik);

  let centerlat = 46.8517;
  let centerlon = 9.52585;
  let zoom = 10;

  let fromProjection = new OpenLayers.Projection("EPSG:4326");   // Transform from WGS 1984
  let toProjection = new OpenLayers.Projection("EPSG:900913"); // to Spherical Mercator Projection
  let centerposition = new OpenLayers.LonLat(centerlon, centerlat).transform(fromProjection, toProjection);

  map.setCenter(centerposition, zoom);
});