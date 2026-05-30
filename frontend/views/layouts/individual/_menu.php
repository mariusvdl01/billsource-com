<?php

use yii\widgets\Menu;

$counter = Yii::$app->params['unreadBillsCounter'];
?>
<?= Menu::widget([
		'items' => [
            ['label' => 'Dashboard', 'url' => ['/individual/profile/dashboard']],
            ['label' => 'Tasks', 'url' => '',
                'options' => [
                    'class' => 'dropdown',
                ],
                'items' => [
                    ['label' => 'Update Profile', 'url' => ['/individual/profile/update'] ],
                    ['label' => 'Update Financial', 'url' => ['/individual/profile/financial'] ],
                    ['label' => 'See My Bills', 'url' => ['/individual/bill/unpaid'] ],
                    ['label' => 'Load My Vault', 'url' => ['/individual/vault'] ],
                    ['label' => 'Manage Utilities', 'url' => ['/individual/reading/index'] ],
                    ['label' => 'View Statement', 'url' => ['/individual/statement/creditor'] ],
                    ['label' => 'Get a Loan', 'url' => ['/individual/profile/assistance', 'tab' => 7] ],
                    ['label' => 'Contact Counsellor', 'url' => ['/individual/profile/assistance', 'tab' => 6] ],
                ]
            ],
            ['label' => 'Quotes', 'url' => '',
                'options' => [
                        'class' => 'dropdown',
                ],
                'items' => [
                    ['label' => 'Received', 'url' => ['/individual/quote'] ],
                    ['label' => 'Accepted', 'url' => ['/individual/quote/accept'] ],
                    ['label' => 'Rejected', 'url' => ['/individual/quote/reject'] ],
                ],
                'template' => empty($counter['QTN']) ? '<a href="{url}" class="bg-primary">{label}</a>' : '<a href="{url}" class="bg-primary">{label}<span class="button__badge">'. $counter['QTN'] . '</span></a>'
            ],
            ['label' => 'Creditors', 'url' => '',
                'options' => [
                        'class' => 'dropdown',
                ],
                'items' => [
                    ['label' => 'Unpaid', 'url' => ['/individual/bill/unpaid'] ],
                    ['label' => 'Paid', 'url' => ['/individual/bill/paid'] ],
                    ['label' => 'Pending', 'url' => ['/individual/bill/pending'] ],
                    ['label' => 'Refunded', 'url' => ['/individual/bill/refund'] ],
                    ['label' => 'Disputed', 'url' => ['/individual/bill/dispute'] ],
                ],
                'template' => empty($counter['CR']) ? '<a href="{url}" class="bg-primary">{label}</a>' : '<a href="{url}" class="bg-primary">{label}<span class="button__badge">'. $counter['CR'] . '</span></a>'
            ],
            ['label' => 'Payslips', 'url' => '',
                'options' => [
                    'class' => 'dropdown',
                ],
                'items' => [
                    ['label' => 'My Payslips', 'url' => ['/individual/payslip/paid'] ],
                ]
            ],
            ['label' => 'Tickets', 'url' => '',
                'options' => [
                    'class' => 'dropdown',
                ],
                'items' => [
                    ['label' => 'Planning', 'url' => ['/individual/ticket/planning'] ],
                    ['label' => 'Processing', 'url' => ['/individual/ticket/processing'] ],
                    ['label' => 'Finalized', 'url' => ['/individual/ticket/finalized'] ],
                    ['label' => 'Completed', 'url' => ['/individual/ticket/completed'] ],
                ],
                'template' => empty($counter['TCK']) ? '<a href="{url}" class="bg-primary">{label}</a>' : '<a href="{url}" class="bg-primary">{label}<span class="button__badge">'. $counter['TCK'] . '</span></a>'
            ],
				/*['label' => 'Social', 'url' => '',
                    'options' => [
                        'class' => 'dropdown',
                    ],
                    'items' => [
                        ['label' => 'Invite', 'url' => ['/individual/social/invite'] ],
                        ['label' => 'Connect', 'url' => ['/individual/social/connect'] ],
                        ['label' => 'Post', 'url' => ['/individual/social/posts'] ],
                    ]
				],*/
				['label' => 'Assistance', 'url' => '', 
                    'options' => [
                        'class' => 'dropdown',
                    ],
                    'items' => [
                        ['label' => 'Contact Counsellor', 'url' => ['/individual/profile/assistance', 'tab' => 6] ],
                        ['label' => 'Get a Loan', 'url' => ['/individual/profile/assistance', 'tab' => 7] ],
                    ]
				],
				['label' => 'Contact us', 'url' => ['/individual/profile/contact']],
		],
		//'activeCssClass' => 'current',
	    'options' => ['class' => 'nav nav-tabs hidden-xs'],
	    'labelTemplate' =>'{label} Label',
	    'linkTemplate' => '<a href="{url}" class="bg-primary">{label}</a>',
]);