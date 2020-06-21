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
     table, th, td {
        border: 1px solid black;
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
    <?php
  require_once 'pdo.php';
  $sql = "SELECT * FROM bed_trcker.Hospital_details where 1";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  echo "
    <table>
      <tr>
        <th>Hospital ID</th>
        <th>Hospital Name</th>
        <th>Total bed available</th>
        <th>Unoccupied beds</th>
      </tr>";

  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      echo "<tr>";
      echo "<td>".$row['hospital_id']."</td>";
      echo "<td>".$row['hospital_name']."</td>";
      if($row['option_selected']==2){
        $sql1 = "SELECT * FROM bed_trcker.option_b where hosp_id=:hid";
        $stmt1 = $pdo->prepare($sql1);
        $stmt1->execute(array('hid'=>$row['hospital_id']));
        $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
        echo "<td>".$row1['tot_bed']."</td>";
        echo "<td>".$row1['unocc_bed']."</td>"; 
      }
      else{
        $sql1 = "SELECT * FROM bed_trcker.Hospital_option_a where hospital_ref=:hid";
        $stmt1 = $pdo->prepare($sql1);
        $stmt1->execute(array('hid'=>$row['hospital_id']));
        $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
        $sql2 = "SELECT ".$row1['fiel_name_tot_bed'].",".$row1['fiel_name_unoc_bed']." FROM ".$row1['dbname'].".".$row1['tablename'];
        $pdo1 = new PDO("mysql:host = ".$row1['ip_add'].";dbname = ".$row1['dbname'].";",$row1['username'],$row1['pass']);
        $stmt2 = $pdo1->prepare($sql2);
        $stmt2->execute();
        $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        // echo "<td>".$row2['tot_bed']."</td>";
        // echo "<td>".$row2['unocc_bed']."</td>"; 
      }
      // echo "<td>".."</td>";
      // echo "<td>".."</td>";
      echo "</tr>";
  }
  echo "</table>"
?>

  </body>
</html>