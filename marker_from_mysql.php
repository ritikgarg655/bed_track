<?php
	require_once 'pdo.php';
	$dom = new DOMDocument("1.0");
	$node = $dom->createElement("markers");
	$parnode = $dom->appendChild($node);
	$sql = "SELECT * FROM bed_trcker.hospital_details where 1";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		// Add to XML document node
		// echo $row;
		// echo 1;
		// var_dump($row/);/*
		// echo $row['id'];*/
		$node = $dom->createElement("marker");
		$newnode = $parnode->appendChild($node);
		$newnode->setAttribute("id",$row['hospital_id']);
		$newnode->setAttribute("name",$row['hospital_name']);
		// $newnode->setAttribute("address", $row['address']);
		$newnode->setAttribute("lat", $row['hospital_long']);
		$newnode->setAttribute("lng", $row['hospital_lat']);
		// $newnode->setAttribute("type", $row['type']);
	}
	// echo 1;
	header("Content-type: text/xml");
	echo $dom->saveXML();
// 	echo '<markers>
// <marker id="1" name="Love.Fish" address="580 Darling Street, Rozelle, NSW" lat="-33.861034" lng="151.171936" type="restaurant"/>
// <marker id="2" name="Young Henrys" address="76 Wilford Street, Newtown, NSW" lat="-33.898113" lng="151.174469" type="bar"/>
// <marker id="3" name="Hunter Gatherer" address="Greenwood Plaza, 36 Blue St, North Sydney NSW" lat="-33.840282" lng="151.207474" type="bar"/>
// <marker id="4" name="The Potting Shed" address="7A, 2 Huntley Street, Alexandria, NSW" lat="-33.910751" lng="151.194168" type="bar"/>
// <marker id="5" name="Nomad" address="16 Foster Street, Surry Hills, NSW" lat="-33.879917" lng="151.210449" type="bar"/>
// <marker id="6" name="Three Blue Ducks" address="43 Macpherson Street, Bronte, NSW" lat="-33.906357" lng="151.263763" type="restaurant"/>
// <marker id="7" name="Single Origin Roasters" address="60-64 Reservoir Street, Surry Hills, NSW" lat="-33.881123" lng="151.209656" type="restaurant"/>
// <marker id="8" name="Red Lantern" address="60 Riley Street, Darlinghurst, NSW" lat="-33.874737" lng="151.215530" type="restaurant"/>
// </markers>
// ';

?>