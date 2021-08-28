<script type="application/javascript" defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4YXeD4XTeNieaKWam43diRHXjsGg7aVY&callback=initMap"></script>
<script type="application/javascript">
    server_side_datatable('#datatable-with-btns');
    function filterFunction($this) {
        $("#loaderSearch").show();
        $url = '{{url()->full()}}?&isDataTable=true&' + $this.serialize();
        $datatable.ajax.url($url).load();
        $(".js__card_minus").trigger("click");
        setTimeout(function () {
            $("#loaderSearch").hide();
        }, 1000)
    }
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
