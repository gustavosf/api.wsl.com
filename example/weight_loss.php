<?php

require '../src/autoloader.php';

$accessCode = (include 'accessCode.inc');
$weight_loss = \WSL\Calorific::forge($accessCode)->weight;
$data = $weight_loss->order(function($a, $b) {
	return $a->userProfile->timestamp > $b->userProfile->timestamp ? 1 : -1;
})->map(function($entry) {
	return array(
		/* date */	 date_create($entry->userProfile->timestamp)->format('d/m/Y'),
		/* weight */ $entry->userProfile->weightInKg
	);
})->to_array();
$data = array_values($data); /* removing keys */

?>

<html>
	<head>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script type="text/javascript">
			google.load("visualization", "1", {packages:["corechart"]});
			google.setOnLoadCallback(drawChart);
			function drawChart() {
				var data = new google.visualization.DataTable();
				data.addColumn('string', 'Date');
				data.addColumn('number', 'Weight');
				data.addRows(<?php echo json_encode($data) ?>);
				var options = { title: 'Weight Loss' };
				var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
				chart.draw(data, options);
			}
		</script>
	</head>
	<body>
		<div id="chart_div" style="width: 900px; height: 500px;"></div>
	</body>
</html>