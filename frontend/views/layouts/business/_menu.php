<?php

use common\models\business\BusinessClient as Client;
use common\models\ClientInterface;
use yii\widgets\Menu;

$user = Yii::$app->user->identity;
$twoFaLabel = $user->hasTwoFaEnabled() ? 'Disable 2FA' : 'Enable 2FA';
$twoFaUrl = $user->hasTwoFaEnabled() ? '/business/profile/disable-two-fa' : '/business/profile/enable-two-fa';

$counter = Yii::$app->params['unreadBillsCounter'];
$isDca = isset(Yii::$app->params['client']->type) && Yii::$app->params['client']->type == Client::CATEGORY_DCA;
$isCollector = isset(Yii::$app->params['client']->type) && Yii::$app->params['client']->type == Client::CATEGORY_COLLECTOR;

$menuItemsAdmin = [
    ['label' => 'Ecosystem', 'url' => ['/business/ecosystem']],
    ['label' => 'Tasks', 'url' => '#',
        'options' => [
            'class' => 'dropdown',
        ],
        'items' => [
            ['label' => 'Task Management', 'url' => ['/business/task']],
            ['label' => 'Setup', 'url' => ['/system/setup']],
            ['label' => 'Category', 'url' => ['/business/catalog/category']],
            ['label' => 'Catalogue', 'url' => ['/business/catalog/product']],
            ['label' => 'Vet Biller', 'url' => ['/business/vetting'] ],
            ['label' => 'Create Quote', 'url' => ['/business/quote/create'] ],
            ['label' => 'Create Tax Invoice', 'url' => ['/business/invoice/create'] ],
            ['label' => 'Create Cash Invoice', 'url' => ['/business/cash-invoice/create'] ],
            ['label' => 'Update Profile', 'url' => ['/business/profile/update'] ],
            ['label' => 'Make Payments', 'url' => ['/business/creditor/unpaid'] ],
            ['label' => 'Load Vault', 'url' => ['/business/vault'] ],
            ['label' => 'Contact Counsellor', 'url' => ['/business/profile/assistance', 'tab' => 6] ],
        ]
    ],
    ['label' => 'Profile', 'url' => '#',
        'options' => [
            'class' => 'dropdown',
        ],
        'items' => [
            ['label' => 'Update Profile', 'url' => ['/business/profile/update'] ],
            ['label' => 'Manage Users', 'url' => ['/business/user'] ],
            ['label' => 'Manage Customers', 'url' => ['/business/customer'] ],
            ['label' => 'Manage Employees', 'url' => ['/business/employee'] ],
            //['label' => 'Manage Payroll', 'url' => ['business/payroll'] ],
            ['label' => 'Marketing Message', 'url' => ['/business/profile/message'] ],
            ['label' => 'Upgrade Profile', 'url' => ['/business/profile/upgrade'] ],
            ['label' => $twoFaLabel , 'url' => [$twoFaUrl] ],
        ]
    ],
    ['label' => 'Quotes', 'url' => ['/business/quote/'],
        'options' => [
            'class' => 'dropdown',
        ],
        'items' => [
            ['label' => 'New', 'url' => ['/business/quote/create'] ],
            ['label' => 'Rejected', 'url' => ['/business/quote/rejected'] ],
            ['label' => 'Sent', 'url' => ['/business/quote/'] ],
            ['label' => 'Received', 'url' => ['/business/quote/received'] ],
        ],
        'template' => empty($counter['QTN']) ? '<a href="{url}" class="bg-primary">{label}</a>' : '<a href="{url}" class="bg-primary">{label}<span class="button__badge">' . $counter['QTN'] . '</span></a>'
    ],
    ['label' => 'Debtors', 'url' => ['/business/invoice'],
        'options' => [
            'class' => 'dropdown',
        ],
        'items' => [
            ['label' => 'New Tax Invoice', 'url' => ['/business/invoice/create'] ],
            ['label' => 'New Cash Invoice', 'url' => ['/business/cash-invoice/create'] ],
            ['label' => 'Paid Invoice', 'url' => ['/business/invoice/paid'] ],
            ['label' => 'Unpaid Invoice', 'url' => ['/business/invoice'] ],
            ['label' => 'Refunded Invoice', 'url' => ['/business/invoice/refund'] ],
            ['label' => 'Disputed Invoice', 'url' => ['/business/invoice/disputed'] ],
            ['label' => 'Pending Invoice', 'url' => ['/business/invoice/pending'] ],
            ['label' => 'Debtors Statement', 'url' => ['/business/statement/debtor'] ],
        ],
        'template' => empty($counter['INV'])
            ? '<a href="{url}" class="bg-primary">{label}</a>'
            : '<a href="{url}" class="bg-primary">{label}<span class="button__badge">' . $counter['INV'] . '</span></a>'
    ],
    ['label' => 'Creditors', 'url' => ['#'],
        'options' => [
            'class' => 'dropdown',
        ],
        'items' => [
            ['label' => 'Unpaid Creditors', 'url' => ['/business/creditor/unpaid'] ],
            ['label' => 'Paid Creditors', 'url' => ['/business/creditor/paid'] ],
            ['label' => 'Refunded', 'url' => ['/business/creditor/refund'] ],
            ['label' => 'Creditors Statement', 'url' => ['/business/statement/creditor'] ],
        ],
        'template' => empty($counter['CR'])
            ? '<a href="{url}" class="bg-primary">{label}</a>'
            :  '<a href="{url}" class="bg-primary">{label}<span class="button__badge">' . $counter['CR'] . '</span></a>'
    ],
    ['label' => 'Tickets', 'url' => ['#'],
        'options' => [
            'class' => 'dropdown',
        ],
        'items' => [
            ['label' => 'New ticket', 'url' => ['/business/ticket/create'] ],
            ['label' => 'Planning', 'url' => ['/business/ticket/planning'] ],
            ['label' => 'Processing', 'url' => ['/business/ticket/processing'] ],
            ['label' => 'Finalised', 'url' => ['/business/ticket/finalized'] ],
            ['label' => 'Completed', 'url' => ['/business/ticket/completed'] ],
        ],
        'template' => empty($counter['TCK']) ? '<a href="{url}" class="bg-primary">{label}</a>' : '<a href="{url}" class="bg-primary">{label}<span class="button__badge">' . $counter['TCK'] . '</span></a>'
    ],
    ['label' => 'Payslips', 'url' => ['#'],
        'options' => [
            'class' => 'dropdown',
        ],
        'items' => [
            ['label' => 'New payslip', 'url' => ['/business/payslip/create'] ],
            ['label' => 'My payslips', 'url' => ['/business/payslip/'] ],
        ]
    ],
    ['label' => 'Reports', 'url' => ['#'],
        'options' => [
            'class' => 'dropdown',
        ],
        'items' => [
            ['label' => 'Invoice Count', 'url' => ['/business/report/invoice-log'] ],
            ['label' => 'SMS Count', 'url' => ['/business/report/sms-log'] ],
        ]
    ],
    ['label' => 'Shortcut', 'url' => ['#'],
        'options' => [
            'class' => 'quicklinks dropdown',
        ],
        'items' => [
            ['label' => 'Dashboard', 'url' => ['/business/profile']],
            ['label' => 'Contact us', 'url' => ['/business/profile/contact'] ],
            ['label' => 'Debtors', 'url' => ['/business/invoice/debtor'] ],
            ['label' => 'Creditors', 'url' => ['/business/creditor/'] ],
            ['label' => 'Knowledge Base', 'url' => '#' ],
        ]
    ],
];

if ($isDca === true) {
    $menuItemsAdmin[1]['items'][] = ['label' => 'Manage Billers', 'url' => ['/business/biller'] ];
}

if ($isCollector === true) {
    $menuItemsAdmin[4]['items'][] = ['label' => 'Collectors Bin', 'url' => ['/business/collector'] ];
}
?>
<?php if (Yii::$app->params['__role'] === ClientInterface::ROLE_SINGLEUSER_ADMIN
    || Yii::$app->params['__role'] === ClientInterface::ROLE_BUSINESS_ADMIN
) : ?>
    <?= Menu::widget([
		'items' => $menuItemsAdmin,
		'activeCssClass' => 'current',
	    'options' => ['class' => 'nav nav-tabs hidden-xs'],
	    'labelTemplate' =>'{label}',
	    'linkTemplate' => '<a href="{url}" class="bg-primary">{label}</a>',
	    'submenuTemplate' => "\n<ul class='dropdown' role='menu'>\n{items}\n</ul>\n",
    ]); ?>
<?php elseif (ClientInterface::ROLE_LOADER === Yii::$app->params['__role']) : ?>
    <?= Menu::widget([
        'items' => [
            ['label' => 'Dashboard', 'url' => ['/business/profile']],
            ['label' => 'Tasks', 'url' => '#',
                'options' => [
                    'class' => 'dropdown',
                ],
                'items' => [
                    ['label' => 'Catalogue Category', 'url' => ['/business/catalog/category']],
                    ['label' => 'Catalogue Product', 'url' => ['/business/catalog/product']],
                    ['label' => 'Vetting', 'url' => ['/business/vetting']],
                    ['label' => 'Manage Customer', 'url' => ['/business/customer']],
                    ['label' => 'Create Quote', 'url' => ['/business/quote/create']],
                    ['label' => 'Create Invoice', 'url' => ['/business/invoice/create']],
                    ['label' => 'Create Cash Invoice', 'url' => ['/business/cash-invoice/create']],
                    ['label' => 'Update Profile', 'url' => ['/business/profile/update']],
                    ['label' => 'Make Payments', 'url' => ['/business/invoice/creditor']],
                ]
            ],
            ['label' => 'Profile', 'url' => '#',
                'options' => [
                    'class' => 'dropdown',
                ],
                'items' => [
                    ['label' => 'Update Profile', 'url' => ['/business/profile/update']],
                    ['label' => 'Credit Policy', 'url' => ['/business/profile/credit-policy']],
                    ['label' => 'Upgrade', 'url' => ['/business/profile/upgrade']],
                ]
            ],
            ['label' => 'Quotes', 'url' => ['/business/quote/'],
                'options' => [
                    'class' => 'dropdown',
                ],
                'items' => [
                    ['label' => 'New', 'url' => ['/business/quote/create']],
                    ['label' => 'Sent', 'url' => ['/business/quote/']],
                    ['label' => 'Received', 'url' => ['/business/quote/received']],
                ]
            ],
            ['label' => 'Debtors', 'url' => ['/business/invoice'],
                'options' => [
                    'class' => 'dropdown',
                ],
                'items' => [
                    ['label' => 'New Tax Invoice', 'url' => ['/business/invoice/create']],
                    ['label' => 'New Cash Invoice', 'url' => ['/business/cash-invoice/create']],
                    ['label' => 'Paid Invoice', 'url' => ['/business/invoice/paid']],
                    ['label' => 'Unpaid Invoice', 'url' => ['/business/invoice/unpaid']],
                    ['label' => 'Refunded Invoice', 'url' => ['/business/invoice/refund']],
                    ['label' => 'Debtors Statement', 'url' => ['/business/statement/debtor']],
                ]
            ],
            ['label' => 'Creditors', 'url' => ['#'],
                'options' => [
                    'class' => 'dropdown',
                ],
                'items' => [
                    ['label' => 'Unpaid Creditors', 'url' => ['/business/creditor/unpaid']],
                    ['label' => 'Paid Creditors', 'url' => ['/business/creditor/paid']],
                    ['label' => 'Creditor Statement', 'url' => ['/business/statement/creditor']],
                ]
            ],
            ['label' => 'Contact Us', 'url' => ['/business/profile/contact']],
        ],
        'activateParents'=>true,
        'activeCssClass' => 'current',
        'options' => ['class' => 'nav nav-tabs hidden-xs'],
        'labelTemplate' => '{label}',
        'linkTemplate' => '<a href="{url}" class="bg-primary">{label}</a>',
        'submenuTemplate' => "\n<ul class='dropdown' role='menu'>\n{items}\n</ul>\n",
    ]);
    ?>
<?php else : ?>
    <?= Menu::widget([
        'items' => [
            ['label' => 'Quotes Sent', 'url' => ['/business/quote']],
            ['label' => 'My Debtors', 'url' => ['/business/invoice/debtor']],
            ['label' => 'Quotes Received', 'url' => ['/business/quote/received']],
            ['label' => 'Creditors', 'url' => ['#'],
                'options' => [
                    'class' => 'dropdown',
                ],
                'items' => [
                    ['label' => 'Paid Bills', 'url' => ['/business/creditor/paid']],
                    ['label' => 'Unpaid Bills', 'url' => ['/business/creditor/unpaid']],
                    ['label' => 'Creditor Statement', 'url' => ['/business/statement/creditor']],
                ],
            ],
            ['label' => 'Vetting', 'url' => ['/business/vetting']],
            ['label' => 'Contact Us', 'url' => ['/business/profile/contact']],
        ],
        'activateParents'=>true,
        'activeCssClass' => 'current',
        'options' => ['class' => 'nav nav-tabs hidden-xs'],
        'labelTemplate' => '{label}',
        'linkTemplate' => '<a href="{url}" class="bg-primary">{label}</a>',
        'submenuTemplate' => "\n<ul class='dropdown' role='menu'>\n{items}\n</ul>\n",
    ]);
    ?>
<?php endif; ?>
