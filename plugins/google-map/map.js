function initialize() {
  const mainStreet = { lat: parseFloat(document.getElementById("map").getAttribute("data-latitude")), lng: parseFloat(document.getElementById("map").getAttribute("data-longitude")) };

  const map = new google.maps.Map(document.getElementById("map"), {
    center: mainStreet,
    zoom: 14,
  });

  const panorama = new google.maps.StreetViewPanorama(
    document.getElementById("pano"),
    {
      position: mainStreet,
      pov: {
        heading: 34,
        pitch: 10,
      },
    }
  );

  map.setStreetView(panorama);
}

window.initialize = initialize;