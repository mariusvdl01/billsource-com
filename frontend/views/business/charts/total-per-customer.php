<?php
/**
 * Created by PhpStorm.
 * User: netcraft
 * Date: 4/3/16
 * Time: 1:00 AM
 */
$pieData = array();
foreach($customersData as $customerData) {
    if(is_array($customerData));
    foreach($customerData as $key => $data) {
        if('grand' != $key) {
            $pieData[] = array(
                'y' => $data,
                'color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
                //'highlight' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
                'name' => $key,
            );
        }
    }

}
?>

<?= \miloschuman\highcharts\Highcharts::widget([
    'scripts' => [
        'modules/exporting',
        'themes/grid-light',
    ],
    'options' => [
        'title' => [
            'text' => 'Total Outstanding (per customer)'
        ],
        'tooltip' => [
            'pointFormat' => '{series.name}: <b>R{point.y}</b>',
        ],
        'credits' => ['enabled' => false],
        'series' => [
            [
                'type' => 'pie',
                'name' => 'Total',
                'data' => $pieData,
                //'center' => ["50%", "50%"],
                'size' => "60%",
                'showInLegend' => true,
                'dataLabels' => [
                    'enabled' => false,
                ],
            ]
        ]
    ]
]) ?>
