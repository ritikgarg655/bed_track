<?php
  session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>

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
    th {
        cursor: pointer;
    }
	</style>
	<title>Bed Tracker</title>
</head>
<body>
	<h1> Welcome to bed tracker</h1><br>
	Hospital registration: <a href="./hospital_register.php">link</a><br>
	Hospital login: <a href="./hospital_login.php">link</a><br>
  <?php
    if(isset($_SESSION['error'])){
      echo "<p style = 'color : red;'>".$_SESSION['error']."</p>";
      unset($_SESSION['error']);
    }
  ?>
  <?php
    if(isset($_SESSION['user_id'])){
      echo "<p> <a href='./user_logout.php'> Logout </a> </p>";
    }
    else{
      echo "<p> User register: <a href='./user_register.php'> Link </a> <br>";
      echo "User Login: <a href='./user_login.php'> Link </a> </p>";
    }
  ?>
	Available nearby hospital:<br>
	<div id="map"></div><br>
  <?php

  echo "
    <table id='data'>
      <tr>
        <th>Hospital Name</th>
        <th>Total bed available</th>
        <th>Unoccupied beds</th>
        <th>Rating</th>
      ";
  if(isset($_SESSION['user_id'])){
    echo "<th>Insert/Update rating</th>";
  }
  echo "<th>Hospital distance</th>
        <th>Direction link</th>
        </tr>";
  require_once 'pdo.php';
  $sql = "SELECT * FROM bed_trcker.hospital_details where 1";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $hid = $row['hospital_id'];
      echo "<tr id='a'.'$hid'>";
      // echo "<td>".$row['hospital_id']."</td>";
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
        $sql1 = "SELECT * FROM bed_trcker.hospital_option_a where hospital_ref=:hid";
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
      echo "<td>";
      $sql_rating = "SELECT avg(rating) FROM bed_trcker.rating WHERE hospital_id = :hid";
      $stmt_rating = $pdo->prepare($sql_rating);
      $stmt_rating->execute(array('hid'=>$row['hospital_id']));
      $row_rating = $stmt_rating->fetch(PDO::FETCH_ASSOC);
      if($row_rating === false){
        echo "Give rating";
      }
      else if(($row_rating["avg(rating)"]) === null){
        echo "No rating"; 
      }
      else{
        $sql_rating = "SELECT count(rating) FROM bed_trcker.rating WHERE hospital_id = :hid";
        $stmt_rating = $pdo->prepare($sql_rating);
        $stmt_rating->execute(array('hid'=>$row['hospital_id']));
        $count = $stmt_rating->fetch(PDO::FETCH_ASSOC);
        echo $row_rating["avg(rating)"];
        echo " (";
        echo $count["count(rating)"];
        echo " reviews)";
      }
      echo"</td>";

      if(isset($_SESSION['user_id'])){
        echo "<td>";
        $usr_rat = "SELECT rating FROM bed_trcker.rating WHERE hospital_id = :hid and user_id = :uid";
        $stmt_usr_rat = $pdo->prepare($usr_rat);
        $stmt_usr_rat->execute(array('hid'=>$row['hospital_id'],'uid'=>$_SESSION['user_id']));
        $data = $stmt_usr_rat->fetch(PDO::FETCH_ASSOC);
        $hid = $row['hospital_id'];
        if($data == null){
          echo "<a href='./rating.php?hid=$hid'>Give rating</a> :)";
        } 
        else{
          echo ($data["rating"]);
          echo " <a href='./rating.php?hid=$hid'>update</a>";        
        }
        echo "</td>";
      }
      $hid = $row['hospital_id'];
      echo "<td id='$hid'>"."UDEFINED"."</td>";
      echo "<td > <a id='link$hid' href = ''>"."link"."</a></td>";
      // echo "<td>".."</td>";
      echo "</tr>";
  }
  echo "</table>";
?>
	<!-- <script type="text/javascript" src="main_map.js"></script> -->
	<script  type="text/javascript">
    // $(document).ready( function () {
    //     $('#myTable').DataTable();
    // } );
    // $(document).ready( function () {
    //     $('#data').DataTable();
    // } );

    // sorting
    const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;

    const comparer = (idx, asc) => (a, b) => ((v1, v2) => 
        v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)
        )(getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx));

    // do the work...
    document.querySelectorAll('th').forEach(th => th.addEventListener('click', (() => {
        const table = th.closest('table');
        Array.from(table.querySelectorAll('tr:nth-child(n+2)'))
            .sort(comparer(Array.from(th.parentNode.children).indexOf(th), this.asc = !this.asc))
            .forEach(tr => table.appendChild(tr) );
    })));
		function initMap(){
			var map = new google.maps.Map(document.getElementById('map'),{
				center: new google.maps.LatLng(22.7196,75.8577),zoom:12
			});
      var lan,lon;
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          (position) => {
            lan = parseFloat(position.coords.latitude);
            lon = parseFloat(position.coords.longitude);
            point = new google.maps.LatLng(
                    parseFloat(position.coords.latitude),
                    parseFloat(position.coords.longitude)
                    );           
            var marker = new google.maps.Marker({
              map: map,
              position: point,
              label: 'User'
            });
            const pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude,
            };
            map.setCenter(pos);
            },
            () => {
              alert("Allow location access.");
              window.location = "http://localhost:8080/hospital_bed/main.php";
            }
          );
      } 
      else {
        alert("Browser doent support map.");
        // window.location = "http://localhost:8080/hospital_bed/main.php";
      }
			var infowindow = new google.maps.InfoWindow;
			downloadUrl('http://localhost:8080/hospital_bed/marker_from_mysql.php',function(data){
				var xml = data.responseXML;
				var markers = xml.documentElement.getElementsByTagName('marker');
        // var hid = xml.documentElement.getElementsByTagName('');
				Array.prototype.forEach.call(markers,function(markerElement){
					var id = markerElement.getAttribute('id');
					var name = markerElement.getAttribute('name');
					var point = new google.maps.LatLng(
                  parseFloat(markerElement.getAttribute('lat')),
                  parseFloat(markerElement.getAttribute('lng'))
                  );          
          var marker = new google.maps.Marker({
            map: map,
            position: point,
            label: 'H'
          });	
          var link = 'https://www.google.com/maps/dir/?api=1&origin='+String(lan)+','+String(lon)+'&destination='+String(markerElement.getAttribute('lat'))+','+String(markerElement.getAttribute('lng'))+'&travelmode=driving';
          document.getElementById('link'+String(markerElement.getAttribute('id'))).href= link;
	
          $.getJSON('https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins='+String(lan)+','+String(lon)+'&destinations='+String(markerElement.getAttribute('lat'))+','+String(markerElement.getAttribute('lng'))+'&key=AIzaSyAh5C6eO57f7niZB8Pjmcgwazhg9F-eKfM', function(data) {
              var t = data['rows'][0]['elements'][0]['duration']['text'];
              var d = data['rows'][0]['elements'][0]['distance']['value'];
              if(d>300){
                document.getElementById('a' + String(markerElement.getAttribute('id'))).style.visibility = "hidden";
              }
              document.getElementById(String(markerElement.getAttribute('id'))).innerHTML = t;
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