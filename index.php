<!DOCTYPE html>
<html>
<head>
    <title>Delivery Boy GPS Tracking</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>

<h2>Delivery Boy Location Tracking</h2>

<div id="map" style="height: 400px; width: 100%;"></div>

<script>
$(document).ready(function(){
    // Function to update map with delivery boy's location
    function updateMap() {
        $.ajax({
            url: 'get_location.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.latitude && data.longitude) {
                    var location = {lat: parseFloat(data.latitude), lng: parseFloat(data.longitude)};
                    var map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 15,
                        center: location
                    });
                    var marker = new google.maps.Marker({
                        position: location,
                        map: map
                    });
                } else {
                    $('#map').html('No location data available.');
                }
            },
            error: function() {
                $('#map').html('Error fetching location data.');
            }
        });
    }

    // Update map initially
    updateMap();

    // Update map every 10 seconds
    setInterval(updateMap, 10000);
});
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBTjp1qFdVP-_NIz-zSP7k8Zu_D-TDwz70&callback=initMap" async defer></script>
</body>
</html>
