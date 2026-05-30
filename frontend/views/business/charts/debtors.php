<?php
    /**
     * Created by PhpStorm.
     * User: netcraft
     * Date: 4/3/16
     * Time: 1:00 AM
     */

$pieData = [
    [
        'y' => isset($debtorsData['total']['-9000']) ? $debtorsData['total']['-9000'] : 0,
        'color' => "#0000FF",
        //'highlight' => "#ADD8A6",
        'name' => "Current"
    ],
    [
        'y' => isset($debtorsData['total']['31']) ? $debtorsData['total']['31'] : 0,
        'color' => "#FFA500",
        //'highlight' => "#FF5A5E",
        'name' => "30 days overdue"
    ],
    [
        'y' => isset($debtorsData['total']['61']) ? $debtorsData['total']['61'] : 0,
        'color' => "#F7464A",
        //'highlight' => "#FF5A5E",
        'name' => "60 days overdue"
    ],
    [
        'y' => isset($debtorsData['total']['91']) ? $debtorsData['total']['91'] : 0,
        'color' => "#CCCCCC",
        //'highlight' => "#FF5A5E",
        'name' => "90 days overdue"
    ],
    [
        'y' => isset($debtorsData['total']['121']) ? $debtorsData['total']['121'] : 0,
        'color' => "#000000",
        //'highlight' => "#FF5A5E",
        'name' => "120 days overdue"
    ],
    [
        'y' => isset($debtorsData['total']['0']) ? $debtorsData['total']['0'] : 0,
        'color' => "#00FF00",
        //'highlight' => "#5AD3D1",
        'sliced' => true,
        'name' => "Paid"
    ],
];
?>
<?= \miloschuman\highcharts\Highcharts::widget([
    'scripts' => [
        'modules/exporting',
        'themes/grid-light',
    ],
    'options' => [
        'title' => [
            'text' => 'Aggregated Debtors Overview'
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
