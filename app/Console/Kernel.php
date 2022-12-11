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

        \App\Console\Commands\DelayedGroupMessages::class,
        \App\Console\Commands\SyncMessages::class,
        \App\Console\Commands\SyncDialogs::class,
        \App\Console\Commands\SetInvoices::class,
        \App\Console\Commands\PushChannelSetting::class,
        \App\Console\Commands\PushAddonSetting::class,
        \App\Console\Commands\TransferDays::class,
        \App\Console\Commands\SetAddonReports::class,
        \App\Console\Commands\SyncWhmcs::class,
        \App\Console\Commands\SendScheduleCarts::class,
        \App\Console\Commands\SyncZidAbandonedCarts::class,
        \App\Console\Commands\FixGroupMsg::class,
    ];

    protected function schedule(Schedule $schedule)
    {
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
