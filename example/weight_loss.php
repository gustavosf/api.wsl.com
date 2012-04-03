<?php

require '../src/wsl.php';

$accessCode = (include 'accessCode.inc');
$weight_loss = \wsl\Calorific::forge($accessCode)->weight_entries();
$data = array_map(function($entry) {
	return array(
		/* date */	 date_create($entry->timestamp)->format('d/m/Y'),
		/* weight */ $entry->weightInKg
	);
}, $weight_loss);
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