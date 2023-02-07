<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    
    protected $commands = [
        //
        \App\Console\Commands\InstanceStatus::class,
        \App\Console\Commands\PushChannelSetting::class,
        \App\Console\Commands\TransferDays::class,
        \App\Console\Commands\SetInvoices::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $tenants = \DB::table('tenants')->get();
        foreach ($tenants as $tenant) {
            $schedule->command('tenants:run instance:status --tenants=' . $tenant->id)->everyFiveMinutes()->withoutOverlapping();
        }
        $schedule->command('set:invoices')->twiceDaily(9, 12);
        $schedule->command('push:channelSetting')->hourly(1, 13);
        $schedule->command('transfer:days')->dailyAt('03:00');
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
