<?php
  /**
   * Created by PhpStorm.
   * User: netcraft
   * Date: 4/3/16
   * Time: 1:00 AM
   */

$pieData = [
    [
        'y' => isset($creditorsData['total']['-9000']) ? $creditorsData['total']['-9000'] : 0,
        'color' => "#F7464A",
        //'highlight' => "#0000FF",
        'sliced' => true,
        'name' => "Current"
    ],
    [
        'y' => isset($creditorsData['total']['31']) ? $creditorsData['total']['31'] : 0,
        'color' => "#FFA500",
        //'highlight' => "#FF5A5E",
        'name' => "30 days overdue"
    ],
    [
        'y' => isset($creditorsData['total']['61']) ? $creditorsData['total']['61'] : 0,
        'color' => "#F7464A",
        //'highlight' => "#FF0000",
        'name' => "60 days overdue"
    ],
    [
        'y' => isset($creditorsData['total']['91']) ? $creditorsData['total']['91'] : 0,
        'color' => "#CCCCCC",
        //'highlight' => "#FF5A5E",
        'name' => "90 days overdue"
    ],
    [
        'y' => isset($creditorsData['total']['121']) ? $creditorsData['total']['121'] : 0,
        'color' => "#000000",
        //'highlight' => "#00000F",
        'name' => "120 days overdue"
    ],
    [
        'y' => isset($creditorsData['total']['0']) ? $creditorsData['total']['0'] : 0,
        'color' => "#00FF00",
        //'highlight' => "#5AD3D1",
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
            'text' => 'Aggregated Creditors Overview'
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
