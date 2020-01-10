<?php

namespace App\Console;

use App\Services\AdTaskDetailService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /*每天上午10点和下午3点自动采纳回答*/
        $schedule->command('adoptAnswer')->twiceDaily(10, 15);

        // 每隔十分钟检查一次广告是否有效
        $schedule->call(function () {
            $task_detail_server = new AdTaskDetailService();
            $device_service->check();
        })->everyTenMinutes();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
