<script type="application/javascript" defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4YXeD4XTeNieaKWam43diRHXjsGg7aVY&callback=initMap"></script>
<script type="text/javascript">
    var map; //Will contain map object.
    var marker = false; ////Has the user plotted their location marker?
    function initMap() {
        var centerOfMap = new google.maps.LatLng(24.68731563631883, 46.719044971885445);
        var options = {
            center: centerOfMap,
            zoom: 7
        };
        map = new google.maps.Map(document.getElementById('map'), options);
        google.maps.event.addListener(map, 'click', function (event) {
            var clickedLocation = event.latLng;
            if (marker === false) {
                marker = new google.maps.Marker({
                    position: clickedLocation,
                    map: map,
                    draggable: true //make it draggable
                });
                google.maps.event.addListener(marker, 'dragend', function (event) {
                    markerLocation();
                });
            } else {
                marker.setPosition(clickedLocation);
            }
            markerLocation();
        });
    }
    function markerLocation() {
        var currentLocation = marker.getPosition();
        document.getElementById('lat').value = currentLocation.lat(); //latitude
        document.getElementById('lng').value = currentLocation.lng(); //longitude
    }
    google.maps.event.addDomListener(window, 'load', initMap);
</script>
