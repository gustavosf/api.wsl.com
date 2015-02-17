<?php

require '../src/autoloader.php';

$accessCode = (include 'accessCode.inc');
$workouts = \WSL\CardioTrainer::forge($accessCode)
	->workouts
	->filter(array('exerciseType' => 'exercise_type_biking'));

/* group workout distance by week */
$workouts_by_week = array();
$week = date_create(date('c', $workouts[0]->startTime / 1000));
$actual_week = date_create()->format('Y:W');

/* fill in $workouts_by_week with every week since first to now */
while ($week->format('Y:W') <= $actual_week)
{
	$workouts_by_week[$week->format('Y:W')] = array($week->format('Y:W'), 0);
	$week->add(new DateInterval('P1W'));
}

/* fill it with real data */
foreach ($workouts as $workout)
{
	$week = date('Y:W', $workout->startTime / 1000);
	$workouts_by_week[$week] = array(
		$week,
		@$workouts_by_week[$week][1] + $workout->distance / 1000 
	);
}

$data = array_values($workouts_by_week); /* removing keys */

?>

<html>
	<head>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script type="text/javascript">
			google.load("visualization", "1", {packages:["corechart"]});
			google.setOnLoadCallback(drawChart);
			function drawChart() {
				var data = new google.visualization.DataTable();
				data.addColumn('string', 'Week');
				data.addColumn('number', 'Km');
				data.addRows(<?php echo json_encode($data) ?>);
				var options = { title: 'Biking per week' };
				var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
				chart.draw(data, options);
			}
		</script>
	</head>
	<body>
		<div id="chart_div" style="width: 900px; height: 500px;"></div>
	</body>
</html>