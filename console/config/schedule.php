<?php 

/**
 * @var \omnilight\scheduling\Schedule $schedule
 */

// Place here all of your cron jobs

// This command will execute ls command every five minutes
//$schedule->exec('ls')->everyMinute();

//$schedule->command('payment-reminder/notify')->cron('* 2 25 * * *');
$schedule->command('debit-order/run')->everyThirtyMinutes();
$schedule->command('report/debit-order')->weekdays();
$schedule->command('collector/hand-over')->everyThirtyMinutes();
$schedule->command('queue/perform')->everyFiveMinutes();
$schedule->command('ecosystem/crunch-data')->everyTenMinutes();

// This command will call callback function every day at 10:00
//$schedule->call(function(\yii\console\Application $app) {
	// Some code here...
//})->dailyAt('10:00');
