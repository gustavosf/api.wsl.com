<?php

require '../src/wsl.php';

$accessCode = (include 'accessCode.inc');
$weight_loss = \WSL\Calorific::forge($accessCode)->weight;
$data = $weight_loss->map(function($entry) {
	return array(
		/* date */     date_create($entry->userProfile->timestamp)->format('d/m/Y'),
		/* bmi */      $entry->userProfile->weightInKg / (($entry->userProfile->heightInCm/100) ^ 2),
		/* under */    18.5,
		/* ideal */    25,
		/* over  */    30,
		/* obese I */  35,
		/* obese II */ 40,
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
				data.addColumn('number', 'Actual BMI');
				data.addColumn('number', 'Underweight');
				data.addColumn('number', 'Normal');
				data.addColumn('number', 'Overweight');
				data.addColumn('number', 'Obese');
				data.addColumn('number', 'Morbid');
				data.addRows(<?php echo json_encode($data) ?>);
				var options = { title: 'BMI progression' };
				var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
				chart.draw(data, options);
			}
		</script>
	</head>
	<body>
		<div id="chart_div" style="width: 900px; height: 500px;"></div>
	</body>
</html>