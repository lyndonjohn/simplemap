<!DOCTYPE html>
<html>
<head>
    <title>Geolocation</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
<div class="container-fluid">
    <h4 class="mt-2">Click on the map to add a marker or use the form below show specific location.</h4>
    <div class="row mt-3 mb-3">
        <div class="col-md-6">
            <form method="get">
                <div class="form-group mb-3 row">
                    <label for="lat" class="col-sm-2">Latitude</label>
                    <div class="col-sm-6">
                        <input type="text" name="lat" id="lat" class="form-control form-control-sm"
                               placeholder="enter latitude">
                    </div>
                </div>
                <div class="form-group mb-3 row">
                    <label for="long" class="col-sm-2">Latitude</label>
                    <div class="col-sm-6">
                        <input type="text" name="long" id="long" class="form-control form-control-sm"
                               placeholder="enter longitude">
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-sm btn-primary">Show Location</button>
                    <a href="/" class="btn btn-sm btn-outline-primary">Reset Map</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="map"></div>

<script src="index.js"></script>
<!-- Async script executes immediately and must be after any DOM elements used in callback. -->
<script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAkcWyFRTX1aoOGQwx8BTZMZEa1jOkQEU4&callback=initMap&v=weekly"
        async
></script>
<script>
    function initMap() {

        const myLatLng = {
            lat: <?php if (isset($_GET['lat'])) {
                echo $_GET['lat'];
            } else {
                echo -34.397;
            } ?>, lng: <?php if (isset($_GET['long'])) {
                echo $_GET['long'];
            } else {
                echo 150.644;
            } ?> };

        map = new google.maps.Map(document.getElementById("map"), {
            center: myLatLng,
            zoom: 5,
        });

        // This event listener will call addMarker() when the map is clicked.
        map.addListener("click", (event) => {
            addMarker(event.latLng);
        });

        addMarker(myLatLng);

        infoWindow = new google.maps.InfoWindow();

        const locationButton = document.createElement("button");
        const deleteMarkerButton = document.createElement("button");

        locationButton.textContent = "Show Current Location";
        deleteMarkerButton.textContent = "Delete Markers";

        locationButton.classList.add("custom-map-control-button");
        deleteMarkerButton.classList.add("custom-map-control-button");

        map.controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);
        map.controls[google.maps.ControlPosition.TOP_CENTER].push(deleteMarkerButton);

        locationButton.addEventListener("click", () => {
            // Try HTML5 geolocation.
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };

                        const marker = new google.maps.Marker({
                            position: pos,
                            map: map,
                            title: 'Current location'
                        });

                        infoWindow.setPosition(pos);
                        infoWindow.setContent("Current location");
                        //infoWindow.open(map);
                        map.setCenter(pos);
                    },
                    () => {
                        handleLocationError(true, infoWindow, map.getCenter());
                    }
                );

            } else {
                // Browser doesn't support Geolocation
                handleLocationError(false, infoWindow, map.getCenter());
            }
        });

        deleteMarkerButton.addEventListener('click', deleteMarkers);
    }
</script>
</body>
</html>