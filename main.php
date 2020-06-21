<?php
  require_once 'pdo.php';
  $sql = "SELECT * FROM bed_trcker.Hospital_details where 1";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();

?>
<!DOCTYPE html>
<html>
<head>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
<meta content="utf-8" http-equiv="encoding">
	<style type="text/css">
		#map{
			width: 500px;
			height: 500px;
		}
	</style>
	<title>Bed Tracker</title>
</head>
<body>
	<h1> Welcome to bed tracker</h1><br>
	Hospital registration: <a href="./hospital_register.php">link</a><br>
	Hospital login: <a href="./hospital_login.php">link</a><br>
	Available nearby hospital:<br>
	<div id="map"></div><br>
	<!-- <script type="text/javascript" src="main_map.js"></script> -->
	<script type="text/javascript">
		function initMap(){
			var map = new google.maps.Map(document.getElementById('map'),{
				center: new google.maps.LatLng(22.7196,75.8577),zoom:12
			});
			var infowindow = new google.maps.InfoWindow;
			downloadUrl('http://localhost/hospital_bed/marker_from_mysql.php',function(data){
				var xml = data.responseXML;
				var markers = xml.documentElement.getElementsByTagName('marker');
				Array.prototype.forEach.call(markers,function(markerElement){
					var id = markerElement.getAttribute('id');
					var name = markerElement.getAttribute('name');
					var point = new google.maps.LatLng(
                  parseFloat(markerElement.getAttribute('lat')),
                  parseFloat(markerElement.getAttribute('lng')));
					var infowincontent = document.createElement('div');
              // var strong = document.createElement('strong');
              // strong.textContent = name
              // infowincontent.appendChild(strong);
              // infowincontent.appendChild(document.createElement('br'));

              // var text = document.createElement('text');
              // text.textContent = address
              // infowincontent.appendChild(text);
              var marker = new google.maps.Marker({
                map: map,
                position: point,
                label: 'H'
              });
              marker.addListener('click', function() {
                infoWindow.setContent(infowincontent);
                infoWindow.open(map, marker);
              });
				});
			});
		}

    function downloadUrl(url, callback) {
      var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

      request.onreadystatechange = function() {
        if (request.readyState == 4) {
          request.onreadystatechange = doNothing;
          callback(request, request.status);
        }
      };

      request.open('GET', url, true);
      request.send(null);
    }

    function doNothing() {}
	</script>

    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAh5C6eO57f7niZB8Pjmcgwazhg9F-eKfM&callback=initMap">
    </script>
    
</body>
</html>