<script type="application/javascript" defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4YXeD4XTeNieaKWam43diRHXjsGg7aVY&callback=initMap"></script>
<script type="application/javascript">

    var map;
    var marker = false;
    function initMap(lat, long) {
        let longVal = parseFloat(long);
        let latVal = parseFloat(lat);
        const myLatLng = { lat: latVal, lng: longVal };
        var centerOfMap = new google.maps.LatLng(latVal, longVal);
        var options = {
            center: centerOfMap,
            zoom: 7
        };
        map = new google.maps.Map(document.getElementById('map'), options);
        new google.maps.Marker({
            position: myLatLng,
            map:map
        });
    }
    function OpenLocation(lat, long) {
        $('#show_location').modal('show');
        google.maps.event.addDomListener(window, 'load', initMap(lat, long));
    }
</script>
