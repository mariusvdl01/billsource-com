<?php

use common\models\invoice\Invoice;

$data = new \SplFixedArray(count(Invoice::getMonths()));
$barData = array();
$series = array();

foreach($bars as $key => $bar) {
	if(is_array($bar)) {
		foreach ($bar as $month => $total) {
			$data->offsetSet($month-1, $total);
		}
	}
	$series[] = [
		'type'  => 'column',
		'name'  => $key,
		'data'  => $data->toArray(),
	];
	$data = new \SplFixedArray(count(Invoice::getMonths()));
}
?>

<?= \miloschuman\highcharts\Highcharts::widget([
	'scripts' => [
		'modules/exporting',
		'themes/grid-light',
	],
	'options' => [
		'title' => ['text' => 'Monthly Aggregated overview (Per Account/Per Customer)'],
		'xAxis' => [
			'categories' => Invoice::getMonths()
		],
		'yAxis' => [
			'title' => ['text' => 'Total for the month']
		],
		'plotOptions' => [
			'column' => ['stacking' => 'normal']
		],
		'series' => $series
	]
]);  ?>